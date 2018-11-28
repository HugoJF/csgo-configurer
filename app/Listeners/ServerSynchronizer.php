<?php

namespace App\Listeners;

use App\Classes\SmartLog;
use App\Events\GenericBroadcastEvent;
use App\Events\ServerSynchronizationRequest;
use App\File;
use App\Server;
use App\Synchronization;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ServerSynchronizer implements ShouldQueue
{
	use InteractsWithQueue;

	/** @var FilesystemAdapter */
	private $destination_server;

	private $logs;
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->logs = new SmartLog();
	}

	/**
	 * Handle the event.
	 *
	 * @param  ServerSynchronizationRequest $event
	 *
	 * @return void
	 */
	public function handle(ServerSynchronizationRequest $event)
	{
		$startTime = round(microtime(true) * 1000);

		event(new GenericBroadcastEvent('Server synchronization started!', "Server <code>{$event->server->name}</code> synchronization started!"));

		$server = $event->server;

		$this->logs->addMeasure('Creating FTP connection to server');
		$this->createFtpConnection($server);

		$this->logs->addMeasure('Preparing file list');
		$files = $this->prepareFileList($server);

		$this->logs->addMeasure('Preparing deletion list');
		$deletionList = $this->prepareDeletionList($server, $files);

		$this->logs->addMeasure('Deleting files');
		$this->deleteFiles($deletionList);

		$this->logs->addMeasure('Restoring backup files');
		$this->restoreBackupFiles($server, $deletionList);

		$this->logs->addMeasure('Wiping sync files');
		$this->wipeSyncFiles($server);

		$this->logs->addMeasure('Syncing files');
		$this->syncFiles($server, $files);

		$this->logs->addMeasure('Finishing synchronization');
		$event->server->synced_at = Carbon::now();
		$event->server->save();

		$endTime = round(microtime(true) * 1000);
		$duration = $endTime - $startTime;

		$sync = Synchronization::make();

		$sync->duration = $duration;
		$sync->log = $this->logs;
		$sync->server()->associate($event->server);

		event(new GenericBroadcastEvent('Server synchronization finished!', "Server <code>{$event->server->name}</code> synchronization finished successfully!"));
	}

	private function wipeSyncFiles(Server $server)
	{
		$server->files()->synced()->delete();
	}

	public function createFtpConnection($server)
	{
		$this->destination_server = Storage::createFtpDriver([
			'host'     => $server->ftp_host,
			'username' => $server->ftp_user,
			'password' => $server->ftp_password,
			'root'     => $server->ftp_root,
		]);
	}

	public function syncFiles(Server $server, $files)
	{
		foreach ($files as $file) {
			$this->syncFile($server, $file);
		}
	}

	public function deleteFiles($files)
	{
		foreach ($files as $file) {
			$this->deleteFile($file);
		}
	}

	public function deleteFile(File $file)
	{
		$this->destination_server->delete($file->path);
		$file->delete();
	}

	private function backupFile(Server $server, string $destinationPath)
	{
		$backupPath = $destinationPath;
		$fileContent = $this->destination_server->get($destinationPath);

		Storage::disk('backup')->put($this->getServerFolder($server) . $backupPath, $fileContent);

		$file = File::make();

		$file->path = $backupPath;
		$file->type = File::TYPE_BACKUP;
		$file->owner()->associate($server);

		$file->save();
	}

	public function syncFile(Server $server, File $file)
	{
		$destinationPath = $this->stripFirstFolder($file->path);
		$renderPath = $this->getServerFolder($server) . $destinationPath;

		$renderedContent = Storage::disk('renders')->get($renderPath);
		$renderedSize = Storage::disk('renders')->size($renderPath);

		$destinationExists = $this->destination_server->exists($destinationPath);
		if ($destinationExists) {
			$destinationSize = $this->destination_server->size($destinationPath);;
		}

		$shouldSync = $this->forced
			|| !$destinationExists
			|| $renderedSize != $destinationSize;

		$shouldBackup = $shouldSync && !$server->files()->backup()->where('path', $destinationPath)->exists();

		if ($shouldBackup) {
			$this->backupFile($server, $destinationPath);
		}

		if ($shouldSync) {
			$this->destination_server->put($destinationPath, $renderedContent);
		}

		$this->attachSyncedFile($server, $file);
	}

	private function attachSyncedFile(Server $server, File $file)
	{
		$server_file = File::make();

		$server_file->path = $this->stripFirstFolder($file->path);
		$server_file->type = File::TYPE_SYNC;
		$server_file->owner()->associate($server);

		$server_file->save();
	}


	public function stripFirstFolder($path)
	{
		$split_path = explode(DIRECTORY_SEPARATOR, $path);
		unset($split_path[0]);
		$path = implode('/', $split_path);

		return $path;
	}

	private function prepareFileList(Server $server)
	{

		$installation = $server->installation;

		$installation->load(['plugins' => function ($q) {
			$q->orderBy('installation_plugin.priority', 'ASC');
		}]);

		$files = [];

		foreach ($installation->plugins as $plugin) {
			foreach ($plugin->files as $file) {
				$files[] = $file;
			}
		}

		return $files;
	}

	private function prepareDeletionList(Server $server, $files)
	{
		/** @var Collection $serverFiles */
		$serverFiles = $server->files()->synced()->get();

		$files = collect($files);

		$deletionList = $serverFiles->reject(function ($value, $key) use ($serverFiles, $files) {
			return $files->contains(function ($v, $k) use ($value) {
				return $this->stripFirstFolder($v->path) == $value->path;
			});
		});

		return $deletionList;
	}

	private function restoreBackupFiles(Server $server, Collection $deletionList)
	{
		foreach ($deletionList as $deleted) {
			$backup = $server->files()->backup()->where('path', $deleted->path)->first();
			if ($backup) {
				$this->restoreBackupFile($server, $backup);
			}
		}
	}

	private function restoreBackupFile(Server $server, File $backup)
	{
		$filePath = $this->getServerFolder($server) . $backup->path;

		$backupContent = Storage::disk('backup')->get($filePath);

		$this->destination_server->put($backup->path, $backupContent);

		$backup->delete();

		Storage::disk('backup')->delete($this->getServerFolder($server) . $backup->path);
	}

	private function getServerFolder(Server $server)
	{
		return $server->id . DIRECTORY_SEPARATOR;
	}
}

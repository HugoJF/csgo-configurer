<?php

namespace App\Listeners;

use App\Events\ServerSynchronizationRequest;
use App\File;
use App\Server;
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

	private $forced;

	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$forced = false;
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
		$server = $event->server;

		$this->createFtpConnection($server);

		$files = $this->prepareFileList($server);

		$deletionList = $this->prepareDeletionList($server, $files);

		$this->deleteFiles($deletionList);

		$this->syncFiles($server, $files);


		$event->server->synced_at = Carbon::now();
		$event->server->save();
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

	public function syncFile(Server $server, File $file)
	{
		$destinationPath = $this->stripFirstFolder($file->path);
		$renderPath = $server->id . DIRECTORY_SEPARATOR . $destinationPath;

		$renderedContent = Storage::disk('renders')->get($renderPath);
		$renderedContentSize = Storage::disk('renders')->size($renderPath);
		$renderedContentLastModified = Storage::disk('renders')->lastModified($renderPath);

		$destinationExists = $this->destination_server->exists($destinationPath);
		if ($destinationExists) {
			$destinationSize = $this->destination_server->size($destinationPath);
			$destinationLastModified = $this->destination_server->lastModified($destinationPath);
		}

		$shouldSync = $this->forced
			|| !$destinationExists
			|| $renderedContentSize != $destinationSize
			|| $renderedContentLastModified != $destinationLastModified;

		if ($shouldSync) {
			$this->destination_server->put($destinationPath, $renderedContent);
		}
		if (!$destinationExists) {
			$this->attachSyncedFile($server, $file);
		}
	}

	private function attachSyncedFile(Server $server, File $file)
	{
		$server_file = File::make();

		$server_file->path = $this->stripFirstFolder($file->path);
		$server_file->renderable = true;

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
		$serverFiles = $server->files;

		$files = collect($files);

		$deletionList = $serverFiles->filter(function ($value, $key) use ($serverFiles, $files) {
			return !$files->contains(function ($v, $k) use ($value) {
				return $this->stripFirstFolder($v->path) == $value->path;
			});
		});

		return $deletionList;
	}
}

<?php

namespace App\Listeners;

use App\Events\ServerSynchronizationRequest;
use App\File;
use App\Server;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ServerSynchronizer implements ShouldQueue
{
	use InteractsWithQueue;

	private $destination_server;

	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
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

	public function syncFile(Server $server, File $file)
	{
		$destination_path = $this->stripFirstFolder($file->path);
		$render_path = $server->id . DIRECTORY_SEPARATOR . $destination_path;

		$rendered_content = Storage::disk('renders')->get($render_path);

		$this->destination_server->put($destination_path, $rendered_content);
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
}

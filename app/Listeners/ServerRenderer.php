<?php

namespace App\Listeners;

use App\Events\ServerRenderRequest;
use App\File;
use App\Server;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ServerRenderer implements ShouldQueue
{
	use InteractsWithQueue;

	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	private $user;

	/**
	 * Handle the event.
	 *
	 * @param  ServerRenderRequest $event
	 *
	 * @return void
	 */
	public function handle(ServerRenderRequest $event)
	{
		$this->user = $event->user;

		$server = $event->server;

		$files = $this->prepareFileList($server);

		$this->prepareDirectory($server->id);

		$this->eraseServerFiles($server);

		$this->renderFiles($server, $files);

		$server->rendered_at = Carbon::now();

		$server->save();
	}

	private function prepareFileList(Server $server)
	{

		$installation = $server->installation;
		if (!$installation) {
			return [];
		}

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

	private function prepareDirectory($directory)
	{
		Storage::disk('renders')->makeDirectory($directory);
	}

	private function eraseServerFiles(Server $server)
	{
		foreach ($server->files as $file) {
			$file->delete();
		}
	}

	private function renderFiles($server, $files)
	{
		foreach ($files as $file) {
			$this->renderFile($server, $file);
		}
	}

	public function stripFirstFolder($path)
	{
		$split_path = explode(DIRECTORY_SEPARATOR, $path);
		unset($split_path[0]);
		$path = implode('/', $split_path);

		return $path;
	}

	public function renderFile(Server $server, File $file)
	{
		$destination_path = $server->id . DIRECTORY_SEPARATOR . $this->stripFirstFolder($file->path);

		$raw_content = Storage::disk('plugins')->get($file->path);

		if ($file->renderable) {
			$content = view(['template' => $raw_content,], $server->renderConfig())->render();
		} else {
			$content = $raw_content;
		}

$		Storage::disk('renders')->put($destination_path, $content);
	}
}

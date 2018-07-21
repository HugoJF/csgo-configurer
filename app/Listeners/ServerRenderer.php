<?php

namespace App\Listeners;

use App\Events\GenericBroadcastEvent;
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
		event(new GenericBroadcastEvent("Server rendering started!", "Server <code>{$event->server->name}</code> started template rendering!"));

		$this->user = $event->user;

		$server = $event->server;

		$files = $this->prepareFileList($server);

		$this->prepareDirectory($server->id);

		$this->eraseServerFiles($server);

		$this->renderFiles($server, $files);

		$server->rendered_at = Carbon::now();

		$server->save();

		event(new GenericBroadcastEvent("Server rendering finished!", "Server <code>{$event->server->name}</code> finished template rendering successfully!"));
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
		Storage::disk('renders')->deleteDirectory($directory);
		Storage::disk('renders')->makeDirectory($directory);
	}

	private function eraseServerFiles(Server $server)
	{
		// TODO: delete as SQL
		foreach ($server->files()->rendered()->get() as $file) {
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
		$destinationPath = $server->id . DIRECTORY_SEPARATOR . $this->stripFirstFolder($file->path);

		$rawContent = Storage::disk('plugins')->get($file->path);

		if ($file->type == File::TYPE_RENDERABLE) {
			$content = view(['template' => $rawContent,], $server->renderConfig())->render();
		} else {
			$content = $rawContent;
		}

		Storage::disk('renders')->put($destinationPath, $content);

		$this->attachRenderFile($server, $destinationPath);
	}

	public function attachRenderFile(Server $server, $renderPath)
	{
		$file = File::make();

		$file->path = $renderPath;
		$file->type = File::TYPE_RENDER;
		$file->owner()->associate($server);

		$file->save();
	}
}

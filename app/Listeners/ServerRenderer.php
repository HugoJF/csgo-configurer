<?php

namespace App\Listeners;

use App\Classes\SmartLog;
use App\Events\GenericBroadcastEvent;
use App\Events\ServerRenderRequest;
use App\File;
use App\Render;
use App\Server;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ServerRenderer implements ShouldQueue
{
	use InteractsWithQueue;

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
		$startTime = round(microtime(true) * 1000);

		event(new GenericBroadcastEvent("Server rendering started!", "Server <code>{$event->server->name}</code> started template rendering!"));

		$this->user = $event->user;

		$server = $event->server;

		$this->logs->addMeasure('Preparing file list');
		$files = $this->prepareFileList($server);

		$this->logs->addMeasure('Preparing render directory');
		$this->prepareDirectory($server->id);

		$this->logs->addMeasure('Erasing server files');
		$this->eraseServerFiles($server);

		$this->logs->addMeasure('Rendering files');
		$this->renderFiles($server, $files);

		$server->rendered_at = Carbon::now();

		$server->save();

		event(new GenericBroadcastEvent("Server rendering finished!", "Server <code>{$event->server->name}</code> finished template rendering successfully!"));
		$this->logs->addMeasure('Finishing');

		$endTime = round(microtime(true) * 1000);
		$duration = $endTime - $startTime;

		$render = Render::make();

		$render->duration = $duration;
		$render->logs = $this->logs;
		$render->server()->associate($server);

		$render->save();
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
		// TODO: Not tested
		$server->files()->rendered()->delete();
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
			$content = html_entity_decode($content);
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

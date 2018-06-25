<?php

namespace App\Http\Controllers;

use App\File;
use App\Server;
use App\Plugin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\FormBuilder;

class FileController extends Controller
{
	public function index(Plugin $plugin)
	{

	}

	public function show_server_file(Server $server, File $file)
	{
		$content = null;

		if ($file->renderable) {
			$content = Storage::disk('renders')->get($server->id . DIRECTORY_SEPARATOR . $file->path);
		}

		return $this->show($file, $content);
	}

	public function show_plugin_file(Plugin $plugin, File $file)
	{
		$content = null;

		if ($file->renderable) {
			$content = Storage::disk('plugins')->get($file->path);
		}

		return $this->show($file, $content);
	}

	private function show(File $file, $content)
	{
		return view('file.show', [
			'file'    => $file,
			'content' => $content,
		]);
	}

	public function edit(FormBuilder $formBuilder, File $file)
	{
		$form = $formBuilder->create('App\Forms\FileForm', [
			'method' => 'PATCH',
			'url'    => route('file.update', $file),
			'model'  => $file,
		]);

		return view('generics.form', [
			'title'       => 'File update form',
			'form'        => $form,
			'submit_text' => 'Update File',
		]);
	}

	public function update(Request $request, File $file)
	{

		$file->fill($request->all() + ['renderable' => 0]);
		$file->save();

		return redirect()->route('file.show', [$file->owner->slug, $file]);
	}

	public function sync_folders()
	{
		$plugins = Plugin::all();

		$dir_plugin_list = Storage::disk('plugins')->directories();

		$db_plugin_list = $plugins->pluck('folder');

		foreach ($dir_plugin_list as $p) {
			if (!in_array($p, $db_plugin_list->toArray())) {
				$plugin = Plugin::make();

				$plugin->slug = str_slug($p);
				$plugin->name = $p;
				$plugin->description = 'No description yet';

				$plugin->folder = $p;

				$plugin->modified_at = Carbon::now();

				$plugin->save();
			}
		}

		foreach ($db_plugin_list as $temp) {
			if (!in_array($temp, $dir_plugin_list)) {
				// $t = Plugin::where('folder', $temp)->first()->delete();
				Storage::disk('plugins')->makeDirectory($temp);
			}
		}
	}

	public function sync_plugins_files()
	{
		$plugin_list = Storage::disk('plugins')->directories();

		foreach ($plugin_list as $p) {
			$plugin_files = Storage::disk('plugins')->allFiles($p);

			$plugin = Plugin::where('folder', $p)->first();

			$current_files = $plugin->files()->withTrashed()->get();

			$current_files_list = $current_files->pluck('path');

			foreach ($plugin_files as $file) {
				if (!in_array($file, $current_files_list->toArray())) {
					$f = File::make();

					$f->path = $file;
					$f->renderable = false;
					$f->owner()->associate($plugin);

					$f->save();
				} else {
					$f = $plugin->files()->withTrashed()->where('path', $file)->first();
					if ($f && $f->trashed()) {
						$f->restore();
					}
				}
			}

			foreach ($current_files_list as $c) {
				if (!in_array($c, $plugin_files)) {
					$f = $plugin->files()->where('path', $c)->first()->delete();
				}
			}
		}
	}
}

<?php

namespace App\Http\Controllers;

use App\FieldList;
use App\File;
use App\Server;
use App\Plugin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\FormBuilder;

class FileController extends Controller
{
	public function makeStatic(File $file)
	{
		$file->type = File::TYPE_STATIC;

		$file->save();

		flash()->success('Marked file as static!');

		return redirect()->back();
	}

	public function makeRenderable(File $file)
	{
		$file->type = File::TYPE_RENDERABLE;

		$file->save();

		flash()->success('Marked file as renderable!');

		return redirect()->back();
	}

	public function showServerFile(Server $server, File $file)
	{
		if ($file->isRenderable()) {
			$content = Storage::disk('renders')->get($server->id . DIRECTORY_SEPARATOR . $file->path);
		} else {
			$content = null;
		}

		$breadcrumb = $file->showBreadcrumb();

		return $this->show($file, $content, $breadcrumb);
	}

	public function showPluginFile(Plugin $plugin, File $file)
	{
		$content = null;

		if ($file->isRenderable()) {
			$content = Storage::disk('plugins')->get($file->path);
		}

		$breadcrumb = $file->showBreadcrumb();

		return $this->show($file, $content, $breadcrumb);
	}

	private function show(File $file, $content, $breadcrumb)
	{
		return view('file.show', [
			'file'       => $file,
			'content'    => $content,
			'breadcrumb' => $breadcrumb,
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
			'breadcrumb'  => $file->showBreadcrumb()->addCurrent("Editing file {$file->name}"),
		]);
	}

	public function update(Request $request, File $file)
	{
		$file->fill($request->all());
		$file->type = $request->get('renderable') ? File::TYPE_RENDERABLE : File::TYPE_STATIC;

		$file->save();

		return redirect()->route('file.show', [$file->owner->slug, $file]);
	}

	public function syncFolders()
	{
		$plugins = Plugin::all();

		$dir_plugin_list = Storage::disk('plugins')->directories();

		$db_plugin_list = $plugins->pluck('folder');

		foreach ($dir_plugin_list as $p) {
			if (in_array($p, $db_plugin_list->toArray()))
				continue;

			$plugin = Plugin::make();

			$plugin->slug = str_slug($p);
			$plugin->name = $p;
			$plugin->description = 'No description yet';

			$plugin->folder = $p;

			$plugin->modified_at = Carbon::now();

			$fieldList = FieldList::create([
				'name' => "Plugin {$plugin->name} data field list",
			]);

			$plugin->data()->associate($fieldList);

			$plugin->save();
		}

		foreach ($db_plugin_list as $temp) {
			if (!in_array($temp, $dir_plugin_list)){
				Storage::disk('plugins')->makeDirectory($temp);
			}
		}
	}

	public function syncPluginsFiles()
	{
		$plugin_list = Storage::disk('plugins')->directories();

		foreach ($plugin_list as $p) {
			$plugin_files = Storage::disk('plugins')->allFiles($p);

			$plugin = Plugin::where('folder', $p)->first();

			$current_files = $plugin->files()->get();

			$current_files_list = $current_files->pluck('path');

			foreach ($plugin_files as $file) {
				if (!in_array($file, $current_files_list->toArray())) {
					$f = File::make();

					$f->path = $file;
					$f->type = File::TYPE_RENDERABLE;
					$f->owner()->associate($plugin);

					$f->save();
				} else {
					$f = $plugin->files()->where('path', $file)->first();
					if ($f && $f->trashed()) {
						$f->restore();
					}
				}
			}

			foreach ($current_files_list as $c) {
				if (!in_array($c, $plugin_files)) {
					$f = $plugin->files()->where('path', $c)->first();
					if ($f)
						$f->delete();
				}
			}
		}
	}
}

<?php

namespace App\Http\Controllers;

use App\File;
use App\Server;
use App\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\FormBuilder;

class FileController extends Controller
{
	public function index(Template $template)
	{

	}

	public function show(Template $template, File $file)
	{
		$content = null;

		if ($file->renderable) {
			$content = Storage::disk('plugins')->get($file->path);
		}

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
		$templates = Template::all();

		$plugin_list = Storage::disk('plugins')->directories();

		$templates_list = $templates->pluck('folder');

		foreach ($plugin_list as $plugin) {
			if (!in_array($plugin, $templates_list->toArray())) {
				$template = Template::make();

				$template->slug = str_slug($plugin);
				$template->name = $plugin;
				$template->description = 'No description yet';

				$template->folder = $plugin;

				$template->modified_at = Carbon::now();

				$template->save();
			}
		}

		foreach ($templates_list as $temp) {
			if (!in_array($temp, $plugin_list)) {
				// $t = Template::where('folder', $temp)->first()->delete();
				Storage::disk('plugins')->makeDirectory($temp);
			}
		}
	}

	public function sync_plugins_files()
	{
		$plugin_list = Storage::disk('plugins')->directories();

		foreach ($plugin_list as $plugin) {
			$plugin_files = Storage::disk('plugins')->allFiles($plugin);

			$template = Template::where('folder', $plugin)->first();

			$current_files = $template->files()->withTrashed()->get();

			$current_files_list = $current_files->pluck('path');

			foreach ($plugin_files as $file) {
				if (!in_array($file, $current_files_list->toArray())) {
					$f = File::make();

					$f->path = $file;
					$f->renderable = false;
					$f->owner()->associate($template);

					$f->save();
				} else {
					$f = $template->files()->withTrashed()->where('path', $file)->first();
					if ($f && $f->trashed()) {
						$f->restore();
					}
				}
			}

			foreach ($current_files_list as $c) {
				if (!in_array($c, $plugin_files)) {
					$f = $template->files()->where('path', $c)->first()->delete();
				}
			}
		}
	}
}

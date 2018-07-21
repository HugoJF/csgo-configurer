<?php

namespace App\Http\Controllers;

use App\Config;
use App\Server;
use App\Plugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;

class ConfigController extends Controller
{
	public function create(FormBuilder $formBuilder, $type = 'user', $id = null)
	{
		$form = $formBuilder->create('App\Forms\ConfigForm', [
			'method' => 'POST',
			'url'    => route('config.store', [$type, $id]),
		]);

		$titles = [
			'user'   => 'User Config Form',
			'plugin' => 'Plugin Config Form',
			'server' => 'Server Config Form',
		];

		$title = $titles[ $type ] ?? 'Unknown Config Form';

		return view('generics.form', [
			'title'       => $title,
			'form'        => $form,
			'type'        => $type,
			'id'          => $id,
			'submit_text' => 'Create',
			'breadcrumbs' => Config::indexBreadcrumb()->addCurrent('Creating new config'),
		]);
	}

	public function store($type = null, $id = null)
	{
		$config = Config::make();

		$config->fill(Input::all());

		if ($type == 'user' || $type == null) {
			$owner = Auth::user();
		} else if ($type == 'server') {
			$owner = Server::where('id', $id)->first();
		} else if ($type == 'plugin') {
			$owner = Plugin::where('slug', $id)->first();
		} else {
			$owner = null;
		}

		$config->owner()->associate($owner);

		$config->save();

		return redirect()->route('config.show', $config);
	}

	public function index()
	{
		$configs = Config::all();

		return view('config.index', [
			'configs'    => $configs,
			'breadcrumb' => Config::indexBreadcrumb(),
		]);
	}

	public function delete(Config $config)
	{
		flash()->success("Config {$config->name} deleted!");

		$config->delete();

		return redirect()->back();
	}

	public function show(Config $config)
	{
		$config->load('constants');

		return view('config.show', [
			'config'     => $config,
			'breadcrumb' => $config->showBreadcrumb(),
		]);
	}

	public function edit(FormBuilder $formBuilder, Config $config)
	{
		$form = $formBuilder->create('App\Forms\ConfigForm', [
			'method' => 'PATCH',
			'url'    => route('config.update', $config),
			'model'  => $config,
		]);

		return view('generics.form', [
			'title'       => 'Config Update Form',
			'form'        => $form,
			'submit_text' => 'Update',
			'breadcrumbs' => Config::indexBreadcrumb()->addCurrent('Editing config'),
		]);
	}

	public function update(Request $request, Config $config)
	{
		$config->fill($request->all());

		$config->save();

		flash()->success("Config {$config->name} updated successfully!'");

		return redirect()->route('config.show', $config);
	}
}

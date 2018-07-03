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
	public function create(FormBuilder $formBuilder, $type = null, $id = null)
	{
		$form = $formBuilder->create('App\Forms\ConfigForm', [
			'method' => 'POST',
			'url'    => route('config.store', [$type, $id]),
		]);

		switch ($type) {
			case null:
			case 'user':
				$title = 'User Config Form';
				break;
			case 'plugin':
				$title = 'Plugin Config Form';
				break;
			case 'server':
				$title = 'Server Config Form';
				break;
			default:
				$title = 'Unknown Config Form';
		}

		return view('generics.form', [
			'title'       => $title,
			'form'        => $form,
			'type'        => $type,
			'id'          => $id,
			'submit_text' => 'Create',
			'breadcrumbs' => [
				[
					'text'  => 'Home',
					'route' => 'home',
				],
				[
					'text'  => 'Configs',
					'route' => 'config.index',
				],
				[
					'text' => 'Creating new config',
					'url'  => url()->current(),
				],
			],
		]);
	}

	public function store($type = null, $id = null)
	{

		$config = Config::make();

		$config->fill(Input::all());

		$owner = null;

		if ($type == 'user' || $type == null) {
			$owner = Auth::user();
		} else if ($type == 'server') {
			$owner = Server::where('id', $id)->first();
		} else if ($type == 'plugin') {
			$owner = Plugin::where('slug', $id)->first();
		}

		$config->owner()->associate($owner);

		$config->save();

		return redirect()->route('config.show', $config);
	}

	public function index()
	{
		$configs = Config::all();

		return view('config.index', [
			'configs' => $configs,
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
			'config' => $config,
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
			'breadcrumbs' => [
				[
					'text'  => 'Home',
					'route' => 'home',
				],
				[
					'text'  => 'Configs',
					'route' => 'config.index',
				],
				[
					'text' => 'Editing config',
					'url'  => url()->current(),
				],
			],
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

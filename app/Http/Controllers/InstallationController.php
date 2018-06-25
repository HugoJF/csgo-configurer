<?php

namespace App\Http\Controllers;

use App\Config;
use App\Installation;
use App\Selection;
use App\Plugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;

class InstallationController extends Controller
{
	public function index()
	{
		$installations = Installation::all();

		return view('installation.index', [
			'installations' => $installations,
		]);
	}

	public function add_plugin(Installation $installation)
	{
		$plugins = Plugin::all();

		return view('installation.add_plugin', [
			'installation' => $installation,
			'plugins'      => $plugins,
		]);
	}

	public function store_plugin(Installation $installation, Plugin $plugin)
	{
		$installation->plugins()->attach($plugin);

		return redirect()->route('installation.show', [
			'installation' => $installation,
		]);
	}

	public function remove_plugin(Installation $installation, Plugin $plugin)
	{
		$installation->plugins()->detach($plugin);

		return redirect()->back();
	}

	public function create_selection(FormBuilder $formBuilder, Installation $installation, Plugin $plugin)
	{
		$configs = $plugin->configs;

		$selection = $installation->plugins()->where('plugin_id', $plugin->id)->first()->pivot;

		$form = $formBuilder->create('App\Forms\SelectionForm', [
			'method' => 'POST',
			'url'    => route('installation.store-selection', [$installation, $plugin]),
			'model' => $selection,
		], [
			'installations'         => [$installation],
			'installation_selected' => $installation->id,
			'plugins'               => [$plugin],
			'plugin_selected'       => $plugin->id,
			'configs'               => $configs,
		]);

		return view('generics.form', [
			'title'       => 'Plugin config selection form',
			'form'        => $form,
			'configs'     => $configs,
			'submit_text' => 'Make config selection',
		]);
	}

	public function store_selection(Request $request, Installation $installation, Plugin $plugin)
	{
		$pivot = $installation->plugins()->where('plugin_id', $plugin->id)->first()->pivot;

		$pivot->selection()->associate(Config::find($request->input('config_id')));

		$pivot->priority = $request->input('priority');

		$pivot->save();

		return redirect()->route('installation.show', $installation);
	}

	public function create(FormBuilder $formBuilder)
	{
		$form = $formBuilder->create('App\Forms\InstallationForm', [
			'method' => 'POST',
			'url'    => route('installation.store'),
		]);

		return view('generics.form', [
			'title'       => 'Installation Form',
			'form'        => $form,
			'submit_text' => 'Submit new installation',
		]);
	}

	public function store()
	{
		$installation = Installation::make();

		$installation->fill(Input::all());

		$installation->save();

		return redirect()->route('installation.show', $installation);
	}

	public function show(Installation $installation)
	{
		return view('installation.show', [
			'installation' => $installation,
		]);
	}

	public function edit(FormBuilder $formBuilder, Installation $installation)
	{
		$form = $formBuilder->create('App\Forms\InstallationForm', [
			'method' => 'PATCH',
			'url'    => route('installation.update', $installation),
			'model'  => $installation,
		]);

		return view('generics.form', [
			'title'       => 'Installation update form',
			'form'        => $form,
			'submit_text' => 'Update installation',
		]);
	}

	public function update(Request $request, Installation $installation)
	{
		$installation->fill($request->all());

		$installation->save();

		flash()->success("Installation {$installation->name} was updated!");

		return redirect()->route('installation.show', $installation);
	}

	public function delete(Installation $installation)
	{
		$installation->delete();

		flash()->success("Installation {$installation->name} was deleted!");

		return redirect()->back();
	}
}

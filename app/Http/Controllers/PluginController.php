<?php

namespace App\Http\Controllers;

use App\Events\PluginsSynchronizationRequest;
use App\Plugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;

class PluginController extends Controller
{
	public function index()
	{
		$plugins = Plugin::all();

		return view('plugin.index', [
			'plugins' => $plugins,
		]);
	}

	public function create(FormBuilder $formBuilder)
	{
		$form = $formBuilder->create('App\Forms\PluginForm', [
			'method' => 'POST',
			'url'    => route('plugin.store'),
		]);

		return view('generics.form', [
			'title'       => 'Plugin Form',
			'form'        => $form,
			'submit_text' => 'Create',
			'breadcrumbs' => [
				[
					'text'  => 'Home',
					'route' => 'home',
				],
				[
					'text'  => 'Plugins',
					'route' => 'plugin.index',
				],
				[
					'text' => 'Creating new plugins',
					'url'  => url()->current(),
				],
			],
		]);
	}

	public function sync()
	{
		event(new PluginsSynchronizationRequest());

		flash()->success('Plugin synchronization requested!');

		return redirect()->back();
	}

	public function store(Request $request)
	{
		$plugin = Plugin::make();

		$plugin->fill($request->all());

		$plugin->save();

		return redirect()->route('plugin.show', $plugin);
	}

	public function show(Plugin $plugin)
	{
		return view('plugin.show', [
			'plugin' => $plugin,
		]);
	}
}

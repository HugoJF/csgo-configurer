<?php

namespace App\Http\Controllers;

use App\Events\PluginsSynchronizationRequest;
use App\FieldList;
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
			'plugins'    => $plugins,
			'breadcrumb' => Plugin::indexBreadcrumb(),
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
			'breadcrumb'  => Plugin::indexBreadcrumb()->addCurrent('Creating new plugins'),
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
		$plugin->slug = str_slug($request->get('name'));

		$fieldList = FieldList::create([
			'name' => "Plugin {$plugin->name} data field list",
		]);

		$plugin->data()->associate($fieldList);

		$plugin->save();

		return redirect()->route('plugin.show', $plugin);
	}

	public function show(Plugin $plugin)
	{
		return view('plugin.show', [
			'plugin'     => $plugin,
			'breadcrumb' => $plugin->showBreadcrumb(),
		]);
	}
}

<?php

namespace App\Http\Controllers;

use App\Bundle;
use App\Installation;
use App\Selection;
use App\Template;
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

	public function add_template(Installation $installation)
	{
		$templates = Template::all();

		return view('installation.add_template', [
			'installation' => $installation,
			'templates'    => $templates,
		]);
	}

	public function store_template(Installation $installation, Template $template)
	{
		$installation->templates()->attach($template);

		return redirect()->route('installation.show', [
			'installation' => $installation,
		]);
	}

	public function remove_template(Installation $installation, Template $template)
	{
		$installation->templates()->detach($template);

		return redirect()->back();
	}

	public function create_selection(FormBuilder $formBuilder, Installation $installation, Template $template)
	{
		$bundles = $template->bundles;

		$form = $formBuilder->create('App\Forms\SelectionForm', [
			'method' => 'POST',
			'url'    => route('installation.store-selection', [$installation, $template]),
		], [
			'installations'         => [$installation],
			'installation_selected' => $installation->id,
			'templates'             => [$template],
			'template_selected'     => $template->id,
			'bundles'               => $bundles,
		]);

		return view('generics.form', [
			'title'       => 'Template bundle selection form',
			'form'        => $form,
			'bundles'     => $bundles,
			'submit_text' => 'Make bundle selection',
		]);
	}

	public function store_selection(Request $request, Installation $installation, Template $template)
	{
		$pivot = $installation->templates()->where('template_id', $template->id)->get()->first()->pivot;

		$pivot->selection()->associate(Bundle::find($request->input('bundle_id')));

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

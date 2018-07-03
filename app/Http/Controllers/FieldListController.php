<?php

namespace App\Http\Controllers;

use App\FieldList;
use App\Plugin;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class FieldListController extends Controller
{
	public function create(FormBuilder $formBuilder, Plugin $plugin)
	{
		$form = $formBuilder->create('App\Forms\FieldListForm', [
			'method' => 'POST',
			'route'  => ['field-list.store', $plugin],
		]);

		return view('generics.form', [
			'title'       => 'Field list form',
			'form'        => $form,
			'submit_text' => 'Submit field list',
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
					'text'  => $plugin->name,
					'route' => ['plugin.show', $plugin],
				],
				[
					'text' => 'Creating new field list',
					'url'  => url()->current(),
				],
			],
		]);
	}

	public function edit(FormBuilder $formBuilder, FieldList $fieldList)
	{
		$form = $formBuilder->create('App\Forms\FieldListForm', [
			'method' => 'PATCH',
			'route'  => ['field-list.update', $fieldList],
			'model'  => $fieldList,
		]);

		return view('generics.form', [
			'title'       => 'Field list update form',
			'form'        => $form,
			'submit_text' => 'Update field list',
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
					'text'  => $fieldList->plugin->name,
					'route' => ['plugin.show', $fieldList->plugin],
				],
				[
					'text' => 'Editing field list',
					'url'  => url()->current(),
				],
			],
		]);
	}

	public function store(Request $request, Plugin $plugin)
	{
		$fieldList = FieldList::make();

		$fieldList->fill($request->all());

		$fieldList->plugin()->associate($plugin);

		$fieldList->save();

		flash()->success('Field list was created!');

		return redirect()->route('plugin.show', $plugin);
	}

	public function update(Request $request, FieldList $fieldList)
	{
		$fieldList->fill($request->all());

		$fieldList->save();

		flash()->success('Field list was updated!');

		return redirect()->route('plugin.show', $fieldList->plugin);
	}

	public function delete(FieldList $fieldList)
	{
		$fieldList->delete();

		flash()->success('Field list was deleted!');

		return redirect()->back();
	}
}

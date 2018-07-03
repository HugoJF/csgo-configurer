<?php

namespace App\Http\Controllers;

use App\Field;
use App\FieldList;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class FieldController extends Controller
{
	public function create(FormBuilder $formBuilder, FieldList $fieldList)
	{
		$form = $formBuilder->create('App\Forms\FieldForm', [
			'method' => 'POST',
			'route'  => ['field.store', $fieldList],
		]);

		return view('generics.form', [
			'title'       => 'Field form',
			'form'        => $form,
			'submit_text' => 'Create form',
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
					'text'  => 'Field lists',
					'route' => ['plugin.show', $fieldList->plugin],
				],
				[
					'text'  => $fieldList->name,
					'route' => ['plugin.show', $fieldList->plugin],
				],
				[
					'text' => 'Creating new field',
					'url'  => url()->current(),
				],
			],
		]);
	}

	public function store(Request $request, FieldList $fieldList)
	{
		$field = Field::make();

		$field->fill($request->all());

		$field->fieldList()->associate($fieldList);

		$saved = $field->save();

		if ($saved) {
			flash()->success('Field created!');
		} else {
			flash()->error('Field could not be created!');
		}

		return redirect()->route('plugin.show', $fieldList->plugin);
	}

	public function edit(FormBuilder $formBuilder, Field $field)
	{
		$form = $formBuilder->create('App\Forms\FieldForm', [
			'method' => 'PATCH',
			'route'  => ['field.update', $field],
			'model'  => $field,
		]);

		return view('generics.form', [
			'title'       => 'Field update form',
			'form'        => $form,
			'submit_text' => 'Update field',
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
					'text'  => $field->fieldList->plugin->name,
					'route' => ['plugin.show', $field->fieldList->plugin],
				],
				[
					'text'  => 'Field lists',
					'route' => ['plugin.show', $field->fieldList->plugin],
				],
				[
					'text'  => $field->fieldList->name,
					'route' => ['plugin.show', $field->fieldList->plugin],
				],
				[
					'text' => 'Editing field',
					'url'  => url()->current(),
				],
			],
		]);
	}

	public function update(Request $request, Field $field)
	{
		$field->fill($request->all());

		$saved = $field->save();

		if ($saved) {
			flash()->success('Field updated!');
		} else {
			flash()->error('Field could not be updated!');
		}

		return redirect()->route('plugin.show', $field->fieldList->plugin);
	}

	public function delete(Field $field)
	{
		$deleted = $field->delete();

		if ($deleted) {
			flash()->success('Field deleted!');
		} else {
			flash()->error('Field could not be deleted!');
		}

		return redirect()->back();
	}
}

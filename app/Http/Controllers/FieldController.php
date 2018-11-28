<?php

namespace App\Http\Controllers;

use App\Field;
use App\FieldList;
use App\File;
use App\Plugin;
use App\Server;
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

		$breadcrumbs = $fieldList->showBreadcrumb()->addCurrent('Creating new field');

		return view('generics.form', [
			'title'       => 'Field form',
			'form'        => $form,
			'submit_text' => 'Create form',
			'breadcrumbs' => $breadcrumbs,
		]);
	}

	public function store(Request $request, FieldList $fieldList)
	{
		$field = Field::make();

		$field->fill($request->all() + ['required' => 0]);

		$field->fieldList()->associate($fieldList);

		$saved = $field->save();

		if ($saved) {
			flash()->success('Field created!');
		} else {
			flash()->error('Field could not be created!');
		}

		return redirect($fieldList->routeShow());
	}

	public function edit(FormBuilder $formBuilder, Field $field)
	{
		$form = $formBuilder->create('App\Forms\FieldForm', [
			'method' => 'PATCH',
			'route'  => ['field.update', $field],
			'model'  => $field,
		]);

		// TODO: Breadcrumbs need update
		return view('generics.form', [
			'title'       => 'Field update form',
			'form'        => $form,
			'submit_text' => 'Update field',
			'breadcrumb'  => $field->showBreadcrumb()->addCurrent("Editing field {$field->name}"),
		]);
	}

	public function require(Field $field)
	{
		$field->required = true;
		$field->save();

		flash()->success("Field {$field->name} is now required!");

		return redirect()->back();
	}

	public function optional(Field $field)
	{
		$field->required = false;
		$field->save();

		flash()->success("Field {$field->name} is now optional!");

		return redirect()->back();
	}

	public function update(Request $request, Field $field)
	{
		$field->fill($request->all() + ['required' => 0]);

		$saved = $field->save();

		if ($saved) {
			flash()->success('Field updated!');
		} else {
			flash()->error('Field could not be updated!');
		}

		return redirect($field->owner->owner->routeShow());
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

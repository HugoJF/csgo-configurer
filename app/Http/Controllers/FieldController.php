<?php

namespace App\Http\Controllers;

use App\Field;
use App\FieldList;
use App\Plugin;
use App\Server;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class FieldController extends Controller
{
	public function createPlugin(FormBuilder $formBuilder, Plugin $plugin)
	{
		$form = $formBuilder->create('App\Forms\FieldForm', [
			'method' => 'POST',
			'route'  => ['field.plugin.store', $plugin],
		]);

		$breadcrumb = $plugin->showBreadcrumb()->addCurrent('Creating new field');

		return $this->create($form, $breadcrumb);
	}

	public function createFieldList(FormBuilder $formBuilder, FieldList $fieldList)
	{
		$form = $formBuilder->create('App\Forms\FieldForm', [
			'method' => 'POST',
			'route'  => ['field.field-list.store', $fieldList],
		]);

		$breadcrumbs = $fieldList->showBreadcrumb()->addCurrent('Creating new field');

		return $this->create($form, $breadcrumbs);
	}

	public function create($form, $breadcrumbs)
	{

		return view('generics.form', [
			'title'       => 'Field form',
			'form'        => $form,
			'submit_text' => 'Create form',
			'breadcrumbs' => $breadcrumbs,
		]);
	}

	public function storePlugin(Request $request, Plugin $plugin)
	{
		$this->store($request, $plugin);

		return redirect()->route('plugin.show', $plugin);
	}

	public function storeFieldList(Request $request, FieldList $fieldList)
	{
		$this->store($request, $fieldList);

		return redirect()->route('plugin.show', $fieldList->getPlugin());
	}

	public function store(Request $request, $owner)
	{
		$field = Field::make();

		$field->fill($request->all() + ['required' => 0]);

		$field->owner()->associate($owner);

		$saved = $field->save();

		if ($saved) {
			flash()->success('Field created!');
		} else {
			flash()->error('Field could not be created!');
		}
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

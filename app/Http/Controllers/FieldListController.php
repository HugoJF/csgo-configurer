<?php

namespace App\Http\Controllers;

use App\Classes\Breadcrumb;
use App\FieldList;
use App\File;
use App\Plugin;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

class FieldListController extends Controller
{
	public function show(FieldList $fieldList)
	{
		session()->reflash();

		if($fieldList->isRoot()) {
			return redirect($fieldList->plugin->routeShow());
		}

		return redirect($fieldList->parent->routeShow());
	}

	public function create(FieldList $fieldList, FormBuilder $formBuilder)
	{
		$form = $formBuilder->create('App\Forms\FieldListForm', [
			'method' => 'POST',
			'route'  => ['field-list.store', $fieldList],
		]);

		$breadcrumb = $fieldList->showBreadcrumb();

		return view('generics.form', [
			'title'       => 'Field list form',
			'form'        => $form,
			'submit_text' => 'Submit field list',
			'breadcrumbs' => $breadcrumb->addCurrent('Creating new field list'),
		]);
	}

	public function edit(FormBuilder $formBuilder, FieldList $fieldList)
	{
		$form = $formBuilder->create('App\Forms\FieldListForm', [
			'method' => 'PATCH',
			'route'  => ['field-list.update', $fieldList],
			'model'  => $fieldList,
		], [
			// TODO: check if root is correct here
			'files' => $fieldList->ancestors()->whereIsRoot()->first()->files()->where('type', File::TYPE_RENDERABLE)->get(),
		]);

		return view('generics.form', [
			'title'       => 'Field list update form',
			'form'        => $form,
			'submit_text' => 'Update field list',
			'breadcrumb' => $fieldList->showBreadcrumb()->addCurrent("Editing field list {$fieldList->name}"),
		]);
	}

	public function store(Request $request, FieldList $parent)
	{
		$route = route('field-list.show', $parent);

		$fieldList = FieldList::make();

		$fieldList->fill($request->all());

		$fieldList->parent()->associate($parent);

		if ($request->has('file_id')) {
			$fieldList->file()->associate(File::find($request->input('file_id')));
		}

		$fieldList->save();

		flash()->success('Field list was created!');

		return redirect($route);
	}

	public function update(Request $request, FieldList $fieldList)
	{
		$fieldList->fill($request->all());

		if ($request->has('file_id')) {
			$fieldList->file()->associate(File::find($request->input('file_id')));
		}

		if($request->get('renderable')) {
			$fieldList->type = File::TYPE_RENDERABLE;
		}

		$fieldList->save();

		flash()->success('Field list was updated!');

		return redirect()->route('plugin.show', $fieldList->owner);
	}

	public function delete(FieldList $fieldList)
	{
		$fieldList->delete();

		flash()->success('Field list was deleted!');

		return redirect()->back();
	}
}

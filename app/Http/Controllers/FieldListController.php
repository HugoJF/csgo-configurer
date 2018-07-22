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
		return redirect($fieldList->owner->routeShow());
	}

	public function fieldListCreate(FormBuilder $formBuilder, FieldList $fieldList)
	{
		$form = $formBuilder->create('App\Forms\FieldListForm', [
			'method' => 'POST',
			'route'  => ['field-list.self.store', $fieldList],
		]);

		$breadcrumb = $fieldList->showBreadcrumb();

		return $this->create($form, $breadcrumb);
	}

	public function pluginCreate(FormBuilder $formBuilder, Plugin $plugin)
	{
		$form = $formBuilder->create('App\Forms\FieldListForm', [
			'method' => 'POST',
			'route'  => ['field-list.plugin.store', $plugin],
		], [
			'files' => $plugin->files()->where('type', File::TYPE_RENDERABLE)->get(),
		]);

		$breadcrumb = $plugin->showBreadcrumb();

		return $this->create($form, $breadcrumb);
	}

	public function create($form, Breadcrumb $breadcrumb)
	{
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
			'files' => $fieldList->owner->files()->where('type', File::TYPE_RENDERABLE)->get(),
		]);

		return view('generics.form', [
			'title'       => 'Field list update form',
			'form'        => $form,
			'submit_text' => 'Update field list',
			'breadcrumb' => $fieldList->showBreadcrumb()->addCurrent("Editing field list {$fieldList->name}"),
		]);
	}

	public function pluginStore(Request $request, Plugin $plugin)
	{
		$route = route('plugin.show', $plugin);

		return $this->store($request, $plugin, $route);
	}

	public function fieldListStore(Request $request, FieldList $fieldList)
	{
		$route = route('field-list.show', $fieldList);

		return $this->store($request, $fieldList, $route);
	}

	public function store(Request $request, $owner, $route)
	{
		$fieldList = FieldList::make();

		$fieldList->fill($request->all());

		$fieldList->owner()->associate($owner);

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

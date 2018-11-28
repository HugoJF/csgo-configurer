<?php

namespace App\Http\Controllers;

use App\Classes\Breadcrumb;
use App\Config;
use App\Constant;
use App\FieldList;
use App\List_;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;

class ConstantController extends Controller
{
	public function create(List_ $list, FieldList $fieldList = null, FormBuilder $formBuilder)
	{
		$breadcrumb = $list->showBreadcrumb()->addCurrent('Creating new constant');

		if ($fieldList) {
			$fields = $fieldList->fields->pluck('key')->toArray();
		} else {
			$fields = [];
		}

		$form = $formBuilder->create('App\Forms\ConstantForm', [
			'method' => 'POST',
			'url'    => route('constant.store', [$list, 'fields' => $fields]),
		], [
			'customFields' => $fieldList ? $fieldList->fields : [],
			'list'         => $fieldList ? $fieldList->name : null,
		]);

		$title = 'Constant Form';
		$submit_text = 'Create Form';

		$config = List_::descendantsAndSelf($list)->where('parent_id', null)->first()->config;

		return view('generics.form', compact('title', 'config', 'form', 'submit_text', 'fieldList', 'breadcrumb'));
	}

	public function store(Request $request, List_ $list)
	{
		$const = Constant::make();

		$const->fill($request->all() + ['active' => 0, 'required' => 0]);

		$const->list_()->associate($list);

		$const->save();

		return redirect()->route('list.show', $list);
	}

	public function edit(FormBuilder $formBuilder, Constant $constant)
	{
		$title = 'Update constant form';
		$submit_text = 'Update constant';


		$form = $formBuilder->create('App\Forms\ConstantForm', [
			'method' => 'PATCH',
			'route'  => ['constant.update', $constant],
			'model'  => $constant,
		]);

		if ($constant->owner_type == 'App\\Config') {
			$config = $constant->owner;
		} else {
			$config = null;
		}

		$breadcrumb = $constant->owner->showBreadcrumb()->addCurrent("Editing constant {$constant->name}");

		return view('generics.form', compact('breadcrumb', 'title', 'config', 'form', 'submit_text'));
	}

	public function update(Request $request, Constant $constant)
	{
		$constant->fill($request->all() + ['active' => 0, 'required' => 0]);

		$constant->save();

		flash()->success('Constant was update successfully!');

		return redirect($constant->owner->routeShow());
	}

	public function delete(Constant $constant)
	{
		$deleted = $constant->delete();

		if ($deleted) {
			flash()->success('Constant was deleted successfully!');
		} else {
			flash()->error('Constant could not be deleted!');
		}

		return redirect()->back();
	}

	public function activate(Constant $constant)
	{
		$constant->active = true;
		$constant->save();

		flash()->success("Constant {$constant->key} was activated!");

		return redirect()->back();
	}

	public function deactivate(Constant $constant)
	{
		$constant->active = false;
		$constant->save();

		flash()->success("Constant {$constant->key} was deactivated!");

		return redirect()->back();
	}
}

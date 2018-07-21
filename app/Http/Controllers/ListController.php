<?php

namespace App\Http\Controllers;

use App\Classes\Breadcrumb;
use App\Config;
use App\Constant;
use App\FieldList;
use App\List_;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use Laracasts\Flash\Flash;

class ListController extends Controller
{
	public function show(List_ $list)
	{
		// Keeps flash messages from being consumed
		session()->reflash();

		return redirect($list->owner->routeShow());
	}

	public function listCreate(List_ $list, FieldList $fieldList)
	{
		$route = route('list.self.store', [$list, $fieldList]);

		$breadcrumb = $list->showBreadcrumb();

		return $this->create($route, $list->getConfig(), $fieldList, $breadcrumb);
	}

	public function configCreate(Config $config, FieldList $fieldList)
	{
		$route = route('list.config.store', [$config, $fieldList]);

		$breadcrumb = $config->showBreadcrumb();

		return $this->create($route, $config, $fieldList, $breadcrumb);
	}

	public function create($route, $config, $fieldList, Breadcrumb $breadcrumb)
	{
		$formBuilder = app(FormBuilder::class);

		$form = $formBuilder->create('App\Forms\ListForm', [
			'method' => 'POST',
			'url'    => $route,
		], [
			'customFields' => $fieldList->fields,
			'list'         => $fieldList->name,
		]);

		$breadcrumb->addCurrent('Creating new list');

		return view('generics.form', [
			'title'       => 'List form',
			'form'        => $form,
			'submit_text' => 'Create new list',
			'config'      => $config,
			'fieldList'   => $fieldList,
			'breadcrumb'  => $breadcrumb,
		]);
	}

	public function listStore(Request $request, List_ $list, FieldList $fieldList)
	{
		$route = route('config.show', $list->getConfig());

		return $this->store($request, $list, $fieldList, $route);
	}

	public function configStore(Request $request, Config $config, FieldList $fieldList)
	{
		$route = route('config.show', $config);

		return $this->store($request, $config, $fieldList, $route);
	}

	public function store(Request $request, $owner, FieldList $fieldList, $route)
	{
		$fields = $fieldList->fields->pluck('key')->toArray();

		$list = List_::make();

		$list->fill($request->all() + ['active' => 0]);

		$list->owner()->associate($owner);
		$list->fieldList()->associate($fieldList);

		$list->save();

		foreach ($request->only($fields) as $field => $value) {

			if ($value == '')
				continue;

			$const = Constant::make();

			$const->key = $field;
			$const->active = true;
			$const->value = $value;
			$const->owner()->associate($list);

			$const->save();
		}

		return redirect($route);
	}

	public function edit(FormBuilder $formBuilder, List_ $list)
	{
		$form = $formBuilder->create('App\Forms\ListForm', [
			'method' => 'PATCH',
			'route'  => ['list.update', $list],
			'model'  => $list,
		]);

		$breadcrumb = $list->showBreadcrumb()->addCurrent('oitat');

		return view('generics.form', [
			'title'       => 'Editing list',
			'form'        => $form,
			'submit_text' => 'Update list',
			'breadcrumb'  => $breadcrumb,
		]);
	}

	public function update(Request $request, List_ $list)
	{
		$list->fill($request->all() + ['active' => 0]);
		$list->overwrites = $request->has('overwrites');

		$saved = $list->save();

		if ($saved) {
			flash()->success('List was updated successfully!');
		} else {
			flash()->error('List could not be updated!');
		}

		return redirect()->route('config.show', $list->getConfig());
	}

	public function delete(List_ $list)
	{
		$errors = 0;
		$constantsDeleted = 0;

		foreach ($list->constants as $constant) {
			$deleted = $constant->delete();

			if ($deleted) {
				$constantsDeleted++;
			} else {
				$errors++;
			}
		}

		if ($errors === 0) {
			$deleted = $list->delete();
			if (!$deleted)
				$errors++;
		}

		if ($errors === 0) {
			flash()->success("List was deleted successfully!");
		} else {
			flash()->error("List could not be deleted, $constantsDeleted constants were deleted and $errors errors!");
		}

		return redirect()->back();
	}

	public function activate(List_ $list)
	{
		$list->active = true;
		$list->save();

		flash()->success("List {$list->name} was activated!");

		return redirect()->back();
	}

	public function deactivate(List_ $list)
	{
		$list->active = false;
		$list->save();

		flash()->success("List {$list->name} was deactivated!");

		return redirect()->back();
	}
}

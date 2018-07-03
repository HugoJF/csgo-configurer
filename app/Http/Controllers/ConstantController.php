<?php

namespace App\Http\Controllers;

use App\Config;
use App\Constant;
use App\FieldList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;

class ConstantController extends Controller
{
	public function create(Config $config, FormBuilder $formBuilder, FieldList $fieldList = null)
	{
		$fields = [];

		if ($fieldList) {
			$fields = $fieldList->fields->pluck('key')->toArray();
		}

		$form = $formBuilder->create('App\Forms\ConstantForm', [
			'method' => 'POST',
			'url'    => route('constant.store', [$config, 'fields' => $fields]),
		], [
			'customFields' => $fieldList ? $fieldList->fields : [],
			'list'         => $fieldList ? $fieldList->name : null,
		]);

		return view('generics.form', [
			'title'       => 'Constant Form',
			'form'        => $form,
			'submit_text' => 'Submit',
			'config'      => $config,
			'breadcrumbs' => [
				[
					'text'  => 'Home',
					'route' => 'home',
				],
				[
					'text'  => 'Configs',
					'route' => 'config.index',
				],
				[
					'text'  => $config->name,
					'route' => ['config.show', $config],
				],
				[
					'text' => 'Creating new constant',
					'url'  => url()->current(),
				],
			],
		]);
	}

	public function store(Request $request, Config $config)
	{
		$fields = $request->input('fields');

		$const = Constant::make();

		$const->fill($request->only(['key', 'list', 'value']));

		if ($fields) {
			$const->value = json_encode($request->only($fields));
		}

		$const->config()->associate($config);

		$const->save();

		return redirect()->route('config.show', $config);
	}
}

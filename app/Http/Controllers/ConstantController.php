<?php

namespace App\Http\Controllers;

use App\Config;
use App\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;

class ConstantController extends Controller
{
	public function create(Config $config, FormBuilder $formBuilder)
	{
		$form = $formBuilder->create('App\Forms\ConstantForm', [
			'method' => 'POST',
			'url'    => route('constant.store', $config),
		]);

		return view('generics.form', [
			'title'       => 'Constant Form',
			'form'        => $form,
			'submit_text' => 'Submit',
		]);
	}

	public function store(Config $config)
	{
		$const = Constant::make();

		$const->fill(Input::all());

		$const->config()->associate($config);

		$const->save();

		return redirect()->route('config.show', $config);
	}
}

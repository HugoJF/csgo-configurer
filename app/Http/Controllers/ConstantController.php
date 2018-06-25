<?php

namespace App\Http\Controllers;

use App\Bundle;
use App\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;

class ConstantController extends Controller
{
	public function create(Bundle $bundle, FormBuilder $formBuilder)
	{
		$form = $formBuilder->create('App\Forms\ConstantForm', [
			'method' => 'POST',
			'url'    => route('constant.store', $bundle),
		]);

		return view('generics.form', [
			'title'       => 'Constant Form',
			'form'        => $form,
			'submit_text' => 'Submit',
		]);
	}

	public function store(Bundle $bundle)
	{
		$const = Constant::make();

		$const->fill(Input::all());

		$const->bundle()->associate($bundle);

		$const->save();

		return redirect()->route('bundle.show', $bundle);
	}
}

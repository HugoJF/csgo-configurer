<?php

namespace App\Http\Controllers;

use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Kris\LaravelFormBuilder\FormBuilder;

class TemplateController extends Controller
{
	public function index()
	{
		$templates = Template::all();

		return view('template.index', [
			'templates' => $templates,
		]);
	}

	public function create(FormBuilder $formBuilder)
	{
		$form = $formBuilder->create('App\Forms\TemplateForm', [
			'method' => 'POST',
			'url'    => route('template.store'),
		]);

		return view('generics.form', [
			'title'       => 'Template Form',
			'form'        => $form,
			'submit_text' => 'Create',
		]);
	}

	public function store()
	{
		$template = Template::make();

		$template->fill(Input::all());

		$template->save();

		return redirect()->route('template.show', $template);
	}

	public function show(Template $template)
	{
		return view('template.show', [
			'template' => $template,
		]);
	}
}

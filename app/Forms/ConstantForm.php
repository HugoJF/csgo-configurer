<?php

namespace App\Forms;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\Form;

class ConstantForm extends Form
{
	public function buildForm()
	{
		$this->key();
		$this->value();
		$this->active();
	}

	private function key()
	{
		$request = app('request');

		if ($request->input('key')) {
			$options = [
				'attr'          => ['readonly'],
				'default_value' => $request->input('key'),
			];
		}

		$options['attr']['spellcheck'] = 'off';
		$options['attr']['autocomplete'] = 'off';

		$this->add('key', 'text', $options ?? []);
	}

	private function value()
	{
		$this->add('value', 'text', [
			'attr' => [
				'spellcheck'   => 'off',
				'autocomplete' => 'off',
			],
		]);
	}

	private function active()
	{
		if(!$this->getModel()) {
			$opts = [
				'checked' => true,
			];
		}
		$this->add('active', 'checkbox', $opts ?? []);
	}
}

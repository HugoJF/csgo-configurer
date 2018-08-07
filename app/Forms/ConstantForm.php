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
		$options['label'] = 'Key';
		$options['rules'] = ['required'];
		$options['help_block']['text'] = 'An identification for this constant.';

		$this->add('key', 'text', $options ?? []);
	}

	private function value()
	{
		$this->add('value', 'text', [
			'label'      => 'Value',
			'rules'      => ['required'],
			'attr'       => [
				'spellcheck'   => 'off',
				'autocomplete' => 'off',
			],
			'help_block' => [
				'text' => 'The actual value of the constant.',
			],
		]);
	}

	private function active()
	{
		if (!$this->getModel()) {
			$opts = [
				'checked' => true,
			];
		}
		$opts = $opts + [
				'label'      => 'Active',
				'rules'      => ['required'],
				'help_block' => [
					'text' => 'If this key-value constant is active to be used for plugin rendering.',
				],
			];
		$this->add('active', 'checkbox', $opts ?? []);
	}
}

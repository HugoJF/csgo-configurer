<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ConstantForm extends Form
{
	public function buildForm()
	{
		$customFields = $this->getData('customFields', []);

		$this
			->add('key', 'text', [
				'attr' => [
					'id'           => 'giantpotato',
					'spellcheck'   => 'off',
					'autocomplete' => 'off',
				],
			]);

		if (count($customFields) == 0) {
			$this->addConstantFields();
		} else {
			$this->addCustomFields($customFields);
		}
	}

	private function addConstantFields()
	{
		$this->add('value', 'text');
		$this->add('list', 'text');
	}

	private function addCustomFields($customFields)
	{
		$this->add('list', 'text', [
			'attr'  => [
				'readonly' => 'readonly',
			],
			'value' => $this->getData('list'),
		]);

		foreach ($customFields as $field) {
			$this->add($field->key, 'text', [
				'label' => $field->name,
				'attr'  => [
					'placeholder' => $field->default,
				],
			]);
		}
	}
}

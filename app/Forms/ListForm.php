<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ListForm extends Form
{
	public function buildForm()
	{
		$this->key();
		$this->customFields();
		$this->overwrites();
		$this->active();
	}

	private function key()
	{

		$this->add('key', 'text', [
			'attr' => [
				'id'           => 'key',
				'spellcheck'   => 'off',
				'autocomplete' => 'off',
			],
		]);
	}

	private function overwrites()
	{
		$this->add('overwrites', 'checkbox');
	}

	private function active()
	{
		if (!$this->getModel()) {
			$opts = [
				'checked' => true,
			];
		}

		$this->add('active', 'checkbox', $opts ?? []);
	}

	private function customFields()
	{
		$customFields = $this->getData('customFields', []);

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

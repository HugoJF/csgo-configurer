<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class FieldForm extends Form
{
	public function buildForm()
	{
		$this->name();
		$this->required();
		$this->description();
		$this->key();
		$this->default();
	}

	private function name()
	{

		$this->add('name', 'text');
	}

	private function required()
	{
		$this->add('required', 'checkbox');
	}

	private function description()
	{

		$this->add('description', 'summernote');
	}

	private function key()
	{
		$this->add('key', 'text');
	}

	private function default()
	{
		$this->add('default', 'text');
	}
}

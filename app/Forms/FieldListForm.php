<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class FieldListForm extends Form
{
	public function buildForm()
	{
		$this
			->add('name', 'text')
			->add('key', 'text')
			->add('description', 'text');
	}
}

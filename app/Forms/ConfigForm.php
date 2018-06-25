<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ConfigForm extends Form
{
	public function buildForm()
	{
		$this
			->add('name', 'text')
			->add('slug', 'text')
			->add('priority', 'number')
		;
	}
}

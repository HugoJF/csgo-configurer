<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class PluginForm extends Form
{
	public function buildForm()
	{
		$this
			->add('slug', 'text')
			->add('name', 'text')
			->add('description', 'text')
			->add('folder', 'text')
		;
	}
}

<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class FileForm extends Form
{
	public function buildForm()
	{
		$this->type();
	}

	private function type()
	{
		$this->add('type', 'text');
	}
}

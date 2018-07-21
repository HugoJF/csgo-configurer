<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class InstallationForm extends Form
{
	public function buildForm()
	{
		$this->name();
		$this->description();
	}

	private function name()
	{
		$this->add('name', 'text');
	}

	private function description()
	{
		$this->add('description', 'text');
	}
}

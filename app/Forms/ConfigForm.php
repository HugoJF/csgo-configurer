<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ConfigForm extends Form
{
	public function buildForm()
	{
		$this->name();
		$this->slug();
		$this->priority();
	}

	private function name()
	{
		$this->add('name', 'text');
	}

	private function slug()
	{
		$this->add('slug', 'text');
	}

	private function priority()
	{
		$this->add('priority', 'number');
	}
}

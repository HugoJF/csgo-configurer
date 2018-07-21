<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class PluginForm extends Form
{
	public function buildForm()
	{
		$this->slug();
		$this->name();
		$this->description();
		$this->folder();
	}

	private function slug()
	{
		$this->add('slug', 'text');
	}

	private function name()
	{
		$this->add('name', 'text');
	}

	private function description()
	{
		$this->add('description', 'text');
	}

	private function folder()
	{
		$this->add('folder', 'text');
	}
}

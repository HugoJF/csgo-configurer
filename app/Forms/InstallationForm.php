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
		$this->add('name', 'text', [
			'label'      => 'Name',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'User friendly name to identify the installation',
			],
		]);
	}

	private function description()
	{
		$this->add('description', 'text', [
			'label'      => 'Description',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Description of what this installation is, allowed values, what it does, etc.',
			],
		]);
	}
}

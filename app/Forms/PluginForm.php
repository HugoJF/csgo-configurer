<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class PluginForm extends Form
{
	public function buildForm()
	{
		$this->name();
		$this->description();
		$this->folder();
	}

	private function name()
	{
		$this->add('name', 'text', [
			'label'      => 'Name',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'User friendly name to identify this plugin',
			],
		]);
	}

	private function description()
	{
		$this->add('description', 'text', [
			'label'      => 'Description',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Description of what this plugin is, allowed values, what it does, etc.',
			],
		]);
	}

	private function folder()
	{
		$this->add('folder', 'text', [
			'label'      => 'Folder',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Where this plugin is located at.',
			],
		]);
	}
}

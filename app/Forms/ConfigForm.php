<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ConfigForm extends Form
{
	public function buildForm()
	{
		$this->name();
		$this->priority();
	}

	private function name()
	{
		$this->add('name', 'text', [
			'label' => 'Config',
			'rules' => ['required'],
			'help_block' => [
				'text' => 'User friendly name to identify your config and what it does.',
			]
		]);
	}

	private function priority()
	{
		$this->add('priority', 'number', [
			'label' => 'Priority',
			'rules' => ['required'],
			'help_block' => [
				'text' => 'When computing your final config used for plugin rendering, configs might have conflicting keys, this priority number will be used to decide which key-value pair will be overwritten (higher values will always overwrite lower ones).'
			]
		]);
	}
}

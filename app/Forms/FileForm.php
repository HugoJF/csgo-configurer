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
		$this->add('type', 'text', [
			'label'      => 'File Type',
			'help_block' => [
				'text' => 'Used by the system to identify what kinda of file this is.',
			],
		]);
	}
}

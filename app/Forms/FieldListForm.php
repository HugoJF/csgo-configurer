<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class FieldListForm extends Form
{
	public function buildForm()
	{
		$this->name();
		$this->key();
		$this->description();
		$this->file_id();
	}

	private function name()
	{
		$this->add('name', 'text', [
			'label'      => 'Name',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'User friendly name to identify field list',
			],
		]);
	}

	private function key()
	{
		$this->add('key', 'text', [
			'label'      => 'Key',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Used to identify this field list inside the rendering code',
			],
		]);
	}

	private function description()
	{
		$this->add('description', 'text', [
			'label'      => 'Description',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'Description of what this field list is, allowed values, what it does, etc.',
			],
		]);
	}

	private function file_id()
	{
		$files = $this->getData('files');

		if ($files) {
			$files = $files->mapWithKeys(function ($value) {
				return [$value->id => $value->path];
			})->toArray();
		} else {
			$files = [];
		}

		if ($files) {
			$this->add('file_id', 'select', [
				'label'       => 'Owner file',
				'choices'     => $files,
				'attr'        => [
					'data-live-search' => 'true',
				],
				'help_block'  => [
					'text' => 'Which plugin file this field-list is stored. Used to group field-lists by file.',
				],
				'empty_value' => '=== Select File ===',
			]);
		}
	}
}

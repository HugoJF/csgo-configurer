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
		$this->add('name', 'text');
	}

	private function key()
	{
		$this->add('key', 'text');
	}

	private function description()
	{
		$this->add('description', 'text');
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
				'choices'     => $files,
				'attr'        => [
					'data-live-search' => 'true',
				],
				'empty_value' => '=== Select File ===',
			]);
		}
	}
}

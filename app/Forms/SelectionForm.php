<?php

namespace App\Forms;

use App\Bundle;
use App\Installation;
use App\Template;
use Kris\LaravelFormBuilder\Form;

class SelectionForm extends Form
{
	public function buildForm()
	{
		$installations = [];
		$bundles = [];
		$templates = [];

		foreach ($this->getData('installations') as $installation) {
			$installations[ $installation->id ] = $installation->name;
		}

		foreach ($this->getData('bundles') as $bundle) {
			$bundles[ $bundle->id ] = $bundle->name;
		}

		foreach ($this->getData('templates') as $template) {
			$templates[ $template->id ] = $template->name;
		}

		$this
			->add('installation_id', 'select', [
				'choices'     => $installations,
				'selected'    => $this->getData('installation_selected', 0),
				'empty_value' => '=== Installation ===',
			])->add('bundle_id', 'select', [
				'choices'     => $bundles,
				'selected'    => $this->getData('bundle_selected', 0),
				'empty_value' => '=== Bundle ===',
			])->add('template_id', 'select', [
				'choices'     => $templates,
				'selected'    => $this->getData('template_selected', 0),
				'empty_value' => '=== Template ===',
			]);
	}
}

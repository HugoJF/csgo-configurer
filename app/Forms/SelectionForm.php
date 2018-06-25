<?php

namespace App\Forms;

use App\Config;
use App\Installation;
use App\Plugin;
use Kris\LaravelFormBuilder\Form;

class SelectionForm extends Form
{
	public function buildForm()
	{
		$installations = [];
		$configs = [];
		$plugins = [];

		foreach ($this->getData('installations') as $installation) {
			$installations[ $installation->id ] = $installation->name;
		}

		foreach ($this->getData('configs') as $config) {
			$configs[ $config->id ] = $config->name;
		}

		foreach ($this->getData('plugins') as $plugin) {
			$plugins[ $plugin->id ] = $plugin->name;
		}

		$this
			->add('installation_id', 'select', [
				'choices'     => $installations,
				'empty_value' => '=== Installation ===',
			])->add('config_id', 'select', [
				'choices'     => $configs,
				'empty_value' => '=== Config ===',
			])->add('plugin_id', 'select', [
				'choices'     => $plugins,
				'empty_value' => '=== Plugin ===',
			])->add('priority', 'number')
		;
	}
}

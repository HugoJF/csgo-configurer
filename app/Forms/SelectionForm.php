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
		$this->installation_id();
		$this->config_id();
		$this->plugin_id();
		$this->priority();
	}

	private function installation_id()
	{
		$installations = [];

		foreach ($this->getData('installations') as $installation) {
			$installations[ $installation->id ] = $installation->name;
		}

		$this->add('installation_id', 'select', [
			'choices'     => $installations,
			'empty_value' => '=== Installation ===',
		]);
	}

	private function config_id()
	{
		$configs = [];

		foreach ($this->getData('configs') as $config) {
			$configs[ $config->id ] = $config->name;
		}

		$this->add('config_id', 'select', [
			'choices'     => $configs,
			'empty_value' => '=== Config ===',
		]);
	}

	private function plugin_id()
	{
		$plugins = [];

		foreach ($this->getData('plugins') as $plugin) {
			$plugins[ $plugin->id ] = $plugin->name;
		}

		$this->add('plugin_id', 'select', [
			'choices'     => $plugins,
			'empty_value' => '=== Plugin ===',
		]);
	}

	private function priority()
	{
		$this->add('priority', 'number');
	}
}

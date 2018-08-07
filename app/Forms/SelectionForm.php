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
			'label'       => 'Installation',
			'choices'     => $installations,
			'empty_value' => '=== Installation ===',
			'help_block'  => [
				'text' => 'The installation referenced in this selected.',
			],
		]);
	}

	private function config_id()
	{
		$configs = [];

		foreach ($this->getData('configs') as $config) {
			$configs[ $config->id ] = $config->name;
		}

		$this->add('config_id', 'select', [
			'label'       => 'Config',
			'choices'     => $configs,
			'empty_value' => '=== Config ===',
			'help_block'  => [
				'text' => 'The config selection for selected installation.',
			],
		]);
	}

	private function plugin_id()
	{
		$plugins = [];

		foreach ($this->getData('plugins') as $plugin) {
			$plugins[ $plugin->id ] = $plugin->name;
		}

		$this->add('plugin_id', 'select', [
			'label'       => 'Plugin',
			'choices'     => $plugins,
			'empty_value' => '=== Plugin ===',
			'help_block'  => [
				'text' => 'The plugin referenced in this selection.',
			],
		]);
	}

	private function priority()
	{
		$this->add('priority', 'number', [
			'label'      => 'Priority',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'The priority for the selected config.',
			],
		]);
	}
}

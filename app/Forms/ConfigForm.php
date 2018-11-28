<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ConfigForm extends Form
{
	public function buildForm()
	{
		$this->name();
		$this->priority();

		if ($this->getModel()) {
			$this->owner();
		}
	}

	private function owner()
	{
		$owners = [];

		foreach ($this->getData('users') ?? [] as $user) {
			$owners["App\User|{$user->id}"] = 'User: ' . $user->username;
		}

		foreach ($this->getData('plugins') ?? [] as $plugin) {
			$owners["App\Plugin|{$plugin->id}"] = 'Plugin: ' . $plugin->name;
		}

		foreach ($this->getData('servers') ?? [] as $server) {
			$owners["App\Server|{$server->id}"] = 'Server: ' .$server->name;
		}

		$this->add('new_owner', 'select', [
			'label'       => 'Owner',
			'choices'     => $owners,
			'empty_value' => '=== Config owner ===',
			'help_block'  => [
				'text' => 'Which object owns this config.',
			],
		]);
	}

	private function name()
	{
		$this->add('name', 'text', [
			'label'      => 'Config',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'User friendly name to identify your config and what it does.',
			],
		]);
	}

	private function priority()
	{
		$this->add('priority', 'number', [
			'label'      => 'Priority',
			'rules'      => ['required'],
			'help_block' => [
				'text' => 'When computing your final config used for plugin rendering, configs might have conflicting keys, this priority number will be used to decide which key-value pair will be overwritten (higher values will always overwrite lower ones).',
			],
		]);
	}
}

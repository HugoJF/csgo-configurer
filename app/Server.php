<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Server extends Model
{
	protected $fillable = [
		'name', 'ip', 'port', 'password', 'ftp_user', 'ftp_password', 'ftp_host', 'ftp_root',
	];

	protected $dates = [
		'render_requested_at', 'rendered_at', 'sync_requested_at', 'synced_at',
	];

	public function installation()
	{
		return $this->belongsTo('App\Installation');
	}

	public function configs()
	{
		return $this->morphMany('App\Config', 'owner');
	}

	public function files()
	{
		return $this->morphMany('App\File', 'owner');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function getPluginList()
	{
		$plugins = [];

		if (!$this->installation) {
			return [];
		}

		foreach ($this->installation->plugins as $plugin) {
			$plugins[] = $plugin;
		}

		return $plugins;
	}

	private function getConfigs()
	{
		$configs = [];

		if ($this->installation) {
			$this->installation->load(['plugins' => function ($q) {
				$q->orderBy('installation_plugin.priority', 'DESC');
			}]);
		}

		$user_configs = $this->user->configs()->orderBy('priority', 'DESC')->get();
		$server_configs = $this->configs()->orderBy('priority', 'DESC')->get();

		foreach ($user_configs as $config) {
			$configs[] = $config;
		}

		foreach ($server_configs as $config) {
			$configs[] = $config;
		}

		if ($this->installation) {
			foreach ($this->installation->plugins as $plugin) {
				if ($plugin->pivot->selection) {
					$configs[] = $plugin->pivot->selection;
				}
			}
		}

		return $configs;
	}

	public function getConstants()
	{
		$configs = $this->getConfigs();

		$finalConfig = [];
		$constants = [];

		foreach ($configs as $config) {
			foreach ($config->constants as $constant) {
				if ($constant->list) {
					if (!isset($finalConfig[ $constant->list ])) {
						$finalConfig[ $constant->list ] = [];
					}
					if (!isset($constants[ $constant->list ])) {
						$constants[ $constant->list ] = [];
					}
					$contains = false;
					foreach ($finalConfig[$constant->list] as $item) {
						if($item['key'] == $constant->key) {
							$contains = true;
						}
					}
					if (!$contains) {
						$finalConfig[ $constant->list ][] = $constant->toArray();
						$constants[ $constant->list ][] = $constant;
					}
					continue;
				}
				if (!array_key_exists($constant->key, $finalConfig)) {
					$finalConfig[ $constant->key ] = $constant->value;
					$constants[] = $constant;
				}
			}
		}

		return [
			'constants' => $constants,
			'config'    => $finalConfig,
		];
	}

	public function renderConfig()
	{
		return $this->getConstants()['config'];
	}

	public function renderConstants()
	{
		return $this->getConstants()['constants'];
	}
}

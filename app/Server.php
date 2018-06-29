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

	private function getConfigs()
	{
		$user = $this->user;

		$configs = [];

		if(!$this->installation) {
			return [];
		}

		$this->installation->load(['plugins' => function ($q) {
			$q->orderBy('installation_plugin.priority', 'DESC');
		}]);

		$user_configs = $user->configs()->orderBy('priority', 'DESC')->get();
		$server_configs = $this->configs()->orderBy('priority', 'DESC')->get();

		foreach ($user_configs as $b) {
			$configs[] = $b;
		}

		foreach ($server_configs as $b) {
			$configs[] = $b;
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
		$config = [];
		$constants = [];

		foreach ($configs as $c) {
			foreach ($c->constants as $constant) {
				if (!array_key_exists($constant->key, $config)) {
					$config[ $constant->key ] = $constant->value;
					$constants[] = $constant;
				}
			}
		}

		return [
			'constants' => $constants,
			'config'    => $config,
		];
	}

	public function renderConfig()
	{
		return $this->getConstants()['config'];
	}
}

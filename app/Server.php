<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Server extends Model
{
	protected $fillable = [
		'name', 'ip', 'port', 'password', 'ftp_user', 'ftp_password', 'ftp_host', 'ftp_root',
	];

	public function installation()
	{
		return $this->belongsTo('App\Installation');
	}

	public function bundles()
	{
		return $this->morphMany('App\Bundle', 'owner');
	}

	private function getBundles()
	{
		$bundles = [];

		$user_bundles = Auth::user()->bundles;
		$server_bundles = $this->bundles;

		foreach ($user_bundles as $b) {
			$bundles[] = $b;
		}

		foreach ($server_bundles as $b) {
			$bundles[] = $b;
		}

		foreach ($this->installation->templates as $template) {
			if ($template->pivot->selection) {
				$bundles[] = $template->pivot->selection;
			}
		}

		return $bundles;
	}

	public function getConstants()
	{
		$bundles = $this->getBundles();
		$bundle = [];
		$constants = [];

		foreach ($bundles as $bundle) {
			foreach ($bundle->constants as $constant) {
				if (!array_key_exists($constant->key, $bundle)) {
					$bundle[ $constant->key ] = $constant->value;
					$constants[] = $constant;
				}
			}
		}

		return [
			'constants' => $constants,
			'bundle'    => $bundle,
		];
	}

	public function renderBundle()
	{
		return $this->getConstants()['bundle'];
	}
}

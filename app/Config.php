<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

	protected $guarded = [
		'user_id',
	];

	protected $fillable = [
		'name',
		'slug',
		'priority',
	];

	public function getRouteKeyName()
	{
		return 'slug';
	}

	public function owner()
	{
		return $this->morphTo();
	}

	public function constants()
	{
		return $this->hasMany('App\Constant');
	}

	public function selections()
	{
		return $this->hasMany('App\InstallationConfig', 'config_id');
	}

	public function fieldLists()
	{
		if ($this->owner_type == 'App\User') {
			return $this->fieldListsFromUser($this->owner);
		} else if ($this->owner_type == 'App\Server') {
			return $this->fieldListsFromServer($this->owner);
		} else if ($this->owner_type == 'App\Plugin') {
			return $this->fieldListsFromPlugin($this->owner);
		} else {
			throw new \Exception('Invalid owner type');
		}
	}

	public function fieldListsFromUser($user)
	{
		$serverFieldLists = [];
		foreach ($user->servers as $server) {
			if ($server->installation) {
				foreach ($server->installation->plugins as $plugin) {
					foreach ($plugin->fieldLists as $fl) {
						$serverFieldLists[] = $fl;
					}
				}
			}
		}

		return $serverFieldLists;
	}

	public function fieldListsFromServer($server)
	{
		$serverFieldLists = [];
		if ($server->installation) {
			foreach ($server->installation->plugins as $plugin) {
				foreach ($plugin->fieldLists as $fl) {
					$serverFieldLists[] = $fl;
				}
			}
		}

		return $serverFieldLists;
	}

	public function fieldListsFromPlugin($plugin)
	{
		return $plugin->fieldLists;
	}
}

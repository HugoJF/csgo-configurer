<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
	protected $dates = [
		'created_at', 'updated_at', 'modified_at',
	];

	public function getRouteKeyName()
	{
		return 'slug';
	}

	protected $fillable = [
		'name', 'slug', 'description', 'folder', 'modified_at',
	];

	public function configs()
	{
		return $this->morphMany('App\Config', 'owner');
	}

	public function files()
	{
		return $this->morphMany('App\File', 'owner');
	}

	public function servers()
	{
		return $this->belongsToMany('App\Server');
	}

	public function selections()
	{
		return $this->hasMany('App\InstallationPlugin', 'config_id');
	}

	public function installations()
	{
		return $this->belongsToMany('App\Installation')->withPivot(['config_id', 'priority'])->using('App\InstallationPlugin');
	}
}

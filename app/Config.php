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
}

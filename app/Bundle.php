<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{

	protected $guarded = [
		'user_id',
	];

	protected $fillable = [
		'name',
		'slug',
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
		return $this->hasMany('App\InstallationBundle', 'bundle_id');
	}
}

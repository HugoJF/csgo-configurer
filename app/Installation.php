<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
	protected $fillable = [
		'name', 'description',
	];

	public function server()
	{
		return $this->hasOne('App\Server');
	}

	public function plugins()
	{
		return $this->belongsToMany('App\Plugin')->withPivot(['config_id', 'priority'])->using('App\InstallationPlugin');
	}

	public function selections()
	{
		return $this->hasMany('App\Selection');
	}

}

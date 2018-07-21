<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
	protected $fillable = [
		'name', 'description',
	];
	public static function indexBreadcrumb()
	{
		return homeBreadcrumb()->add([
			'text'  => 'Installations',
			'route' => 'installation.index',
		]);
	}

	public function showBreadcrumb()
	{
		return Installation::indexBreadcrumb()->add([
			'text'  => $this->name,
			'route' => ['installation.show', $this],
		]);
	}
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

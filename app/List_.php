<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class List_ extends Model
{
	use RoutesActions;

	protected $table = 'lists';

	protected $fillable = [
		'key', 'overwrites', 'active',
	];

	protected $routeNamePrefix = 'list.';


	public static function indexBreadcrumb($owner = null)
	{
		if ($owner === null) {
			$bc = homeBreadcrumb();
		} else {
			$bc = $owner->showBreadcrumb();
		}

		return $bc->add([
			'text'  => 'Lists',
			'route' => 'home',
		]);
	}


	public function showBreadcrumb()
	{
		return List_::indexBreadcrumb($this->owner)->add([
			'text'  => $this->key,
			'route' => ['list.show', $this],
		]);
	}

	public function owner()
	{
		return $this->morphTo();
	}

	public function lists()
	{
		return $this->morphMany('App\List_', 'owner');
	}

	public function fieldList()
	{
		return $this->belongsTo('App\FieldList');
	}

	public function constants()
	{
		return $this->morphMany('App\Constant', 'owner');
	}

	public function getConfig()
	{

		$owner = $this->owner;
		$owner_type = $this->owner_type;

		while ($owner_type == 'App\List_') {
			$owner_type = $owner->owner_type;
			$owner = $owner->owner;
		}

		return $owner;
	}
}

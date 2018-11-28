<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class List_ extends Model
{
	use RoutesActions;
	use NodeTrait;

	protected $table = 'lists';

	protected $fillable = [
		'key', 'overwrites', 'active', 'name',
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

	public function config()
	{
		return $this->hasOne('App\Config', 'list_id');
	}

	public function fieldList()
	{
		return $this->belongsTo('App\FieldList');
	}

	public function constants()
	{
		return $this->hasMany('App\Constant', 'list_id');
	}
}

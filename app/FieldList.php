<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldList extends Model
{
	use RoutesActions;

	protected $routeNamePrefix = 'field-list.';

	protected $fillable = [
		'name', 'key', 'description',
	];

	protected $guarded = [
		'plugin_id',
	];

	public static function indexBreadcrumb($owner) {

		return $owner->showBreadcrumb()->addUrl('Field lists', $owner->routeShow());
	}

	public function showBreadcrumb()
	{
		return FieldList::indexBreadcrumb($this->owner)->add([
			'text'  => $this->name,
			'route' => ['field-list.show', $this],
		]);
	}

	public function owner()
	{
		return $this->morphTo();
	}

	public function lists()
	{
		return $this->hasMany('App\List_');
	}

	public function fields()
	{
		return $this->morphMany('App\Field', 'owner');
	}

	public function file()
	{
		return $this->belongsTo('App\File');
	}

	public function fieldLists()
	{
		return $this->morphMany('App\FieldList', 'owner');
	}

	public function getPlugin()
	{
		$owner = $this->owner;
		$owner_type = $this->owner_type;

		while ($owner_type == 'App\FieldList') {
			$owner_type = $owner->owner_type;
			$owner = $owner->owner;
		}

		return $owner;
	}
}

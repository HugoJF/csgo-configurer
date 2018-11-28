<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class FieldList extends Model
{
	use RoutesActions;
	use NodeTrait;

	protected $routeNamePrefix = 'field-list.';

	protected $fillable = [
		'name', 'key', 'description',
	];

	protected $guarded = [
		'plugin_id',
	];

	public static function indexBreadcrumb($owner)
	{
		return $owner->showBreadcrumb()->addUrl('Field lists', $owner->routeShow());
	}

	public function showBreadcrumb()
	{
		if ($this->isRoot()) {
			return FieldList::indexBreadcrumb($this->plugin)->add([
				'text'  => $this->name,
				'route' => ['field-list.show', $this],
			]);
		} else {
			return $this->parent->showBreadcrumb()->add([
				'text'  => $this->name,
				'route' => ['field-list.show', $this],
			]);
		}
	}

	public function plugin()
	{
		return $this->hasOne('App\Plugin', 'field_list_id');
	}

	public function lists()
	{
		return $this->hasMany('App\List_');
	}

	public function fields()
	{
		return $this->hasMany('App\Field');
	}

	public function files()
	{
		return $this->belongsTo('App\File');
	}

	public function getPlugin()
	{
		// TODO: implement
	}
}

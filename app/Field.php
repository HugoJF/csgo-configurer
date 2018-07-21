<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
	protected $fillable = [
		'name', 'description', 'key', 'default', 'required',
	];

	protected $guarded = [
		'field_list_id',
	];

	public function showBreadcrumb()
	{
		return $this->owner->showBreadcrumb()->add([
			'text'  => $this->name,
			'url' => $this->owner->routeShow(),
		]);
	}

	public function owner()
	{
		return $this->morphTo('owner');
	}
}

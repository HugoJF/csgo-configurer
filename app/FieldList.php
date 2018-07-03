<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldList extends Model
{
	protected $fillable = [
		'name', 'key', 'description',
	];

	protected $guarded = [
		'plugin_id',
	];

	public function plugin()
	{
		return $this->belongsTo('App\Plugin');
	}

	public function fields()
	{
		return $this->hasMany('App\Field');
	}
}

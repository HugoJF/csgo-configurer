<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
	protected $fillable = [
		'name', 'description', 'key', 'default',
	];

	protected $guarded = [
		'field_list_id',
	];

	public function fieldList()
	{
		return $this->belongsTo('App\FieldList');
	}
}

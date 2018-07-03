<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constant extends Model
{
	protected $fillable = [
		'key', 'value', 'list',
	];

	protected $guarded = [
		'user_id',
	];

	public function config()
	{
		return $this->belongsTo('App\Config');
	}
}

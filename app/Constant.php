<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constant extends Model
{
	protected $fillable = [
		'key', 'value',
	];

	protected $guarded = [
		'user_id',
	];

	public function bundle()
	{
		return $this->belongsTo('App\Bundle');
	}
}

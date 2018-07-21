<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constant extends Model
{
	protected $fillable = [
		'key', 'value', 'list', 'active',
	];

	protected $guarded = [
		'user_id',
	];

	public static function indexBreadcrumb()
	{
		return homeBreadcrumb()->add([
			'text'  => 'Constants',
			'route' => 'constant.index',
		]);
	}

	public function showBreadcrumb()
	{
		return Constant::indexBreadcrumb()->add([
			'text'  => $this->key,
			'route' => ['constant.show', $this],
		]);
	}

	public function owner()
	{
		return $this->morphTo();
	}
}

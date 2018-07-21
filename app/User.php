<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable, RoutesActions;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'username', 'avatar', 'tradelink', 'lang',
	];

	protected $guarded = [
		'email', 'steamid',
	];

	protected $routeNamePrefix = 'user.';

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'remember_token',
	];
	public static function indexBreadcrumb()
	{
		return homeBreadcrumb()->add([
			'text'  => 'Users',
			'route' => 'home',
		]);
	}

	public function showBreadcrumb()
	{
		return User::indexBreadcrumb()->add([
			'text'  => $this->name,
			'route' => 'home',
		]);
	}
	public function configs()
	{
		return $this->morphMany('App\Config', 'owner');
	}

	public function servers()
	{
		return $this->hasMany('App\Server');
	}
}

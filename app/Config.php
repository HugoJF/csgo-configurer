<?php

namespace App;

use App\Classes\Breadcrumb;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
	use RoutesActions;

	protected $guarded = [
		'user_id',
	];

	protected $fillable = [
		'name',
		'slug',
		'priority',
	];

	protected $routeNamePrefix = 'config.';

	public function getRouteKeyName()
	{
		return 'slug';
	}

	public static function indexBreadcrumb($owner = null)
	{
		if ($owner === null) {
			$bc = homeBreadcrumb();
		} else {
			$bc = $owner->showBreadcrumb();
		}

		return $bc->add([
			'text'  => 'Configs',
			'route' => 'config.index',
		]);
	}

	public function showBreadcrumb()
	{
		return Config::indexBreadcrumb($this->owner)->add([
			'text'  => $this->name,
			'route' => ['config.show', $this],
		]);
	}

	public function owner()
	{
		return $this->morphTo();
	}

	public function data()
	{
		return $this->belongsTo('App\List_', 'list_id');
	}

	public function selections()
	{
		return $this->hasMany('App\InstallationConfig', 'config_id');
	}

	public function getFieldLists()
	{
		if ($this->owner_type == 'App\User') {
			return $this->fieldListsFromUser($this->owner);
		} else if ($this->owner_type == 'App\Server') {
			return $this->fieldListsFromServer($this->owner);
		} else if ($this->owner_type == 'App\Plugin') {
			return $this->fieldListsFromPlugin($this->owner);
		} else {
			throw new \Exception('Invalid owner type');
		}
	}

	public function fieldListsFromUser($user)
	{
		$serverFieldLists = [];

		foreach ($user->servers as $server) {
			if ($server->installation) {
				foreach ($server->installation->plugins as $plugin) {
					foreach ($plugin->data->children->toFlatTree() as $fl) {
						$serverFieldLists[] = $fl;
					}
				}
			}
		}

		return $serverFieldLists;
	}

	public function fieldListsFromServer($server)
	{
		$serverFieldLists = [];
		if ($server->installation) {
			foreach ($server->installation->plugins as $plugin) {
				foreach ($plugin->data->children->toFlatTree() as $fl) {
					$serverFieldLists[] = $fl;
				}
			}
		}

		return $serverFieldLists;
	}

	public function fieldListsFromPlugin($plugin)
	{
		return $plugin->data->children->toFlatTree();
	}
}

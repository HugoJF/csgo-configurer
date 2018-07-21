<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/10/2018
 * Time: 3:47 PM
 */

namespace App;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

trait RoutesActions
{

	public function route($action = 'index', $parameters = [])
	{
		$route = $this->routeNamePrefix . $action;
		if (Route::has($route)) {
			return route($route, $parameters);
		} else {
			Log::error('Trying to generate route that does not exists!', [
				'route'      => $route,
				'parameters' => $parameters,
				'this'       => $this,
			]);

			return $route;
		}
	}

	public function routeIndex($parameters = [])
	{
		return $this->route('index', $parameters);
	}

	public function routeShow($parameters = [])
	{
		return $this->route('show', array_merge([$this], $parameters));
	}

	public function routeCreate($parameters = [])
	{
		return $this->route('create', $parameters);
	}

	public function routeStore($parameters = [])
	{
		return $this->route('store', $parameters);
	}

	public function routeEdit($parameters = [])
	{
		return $this->route('edit', array_merge([$this], $parameters));
	}

	public function routeUpdate($parameters = [])
	{
		return $this->route('update', array_merge([$this], $parameters));
	}

	public function routeDelete($parameters = [])
	{
		return $this->route('delete', array_merge([$this], $parameters));
	}
}
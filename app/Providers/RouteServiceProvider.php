<?php

namespace App\Providers;

use App\Config;
use App\Constant;
use App\FieldList;
use App\File;
use App\List_;
use App\Plugin;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * This namespace is applied to your controller routes.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @return void
	 */
	public function boot()
	{
		//

		parent::boot();

		Route::bind('config', function ($value) {
			return Config::where('slug', $value)->first();
		});

		Route::bind('plugin', function ($value) {
			return Plugin::where('slug', $value)->first();
		});

		Route::bind('field_list', function ($value) {
			return FieldList::find($value);
		});

		Route::bind('list', function ($value) {
			return List_::find($value);
		});
	}

	/**
	 * Define the routes for the application.
	 *
	 * @return void
	 */
	public function map()
	{
		$this->mapApiRoutes();

		$this->mapWebRoutes();
		//
	}

	/**
	 * Define the "web" routes for the application.
	 *
	 * These routes all receive session state, CSRF protection, etc.
	 *
	 * @return void
	 */
	protected function mapWebRoutes()
	{
		Route::middleware('web')
			 ->namespace($this->namespace)
			 ->group(base_path('routes/web.php'));
	}

	/**
	 * Define the "api" routes for the application.
	 *
	 * These routes are typically stateless.
	 *
	 * @return void
	 */
	protected function mapApiRoutes()
	{
		Route::prefix('api')
			 ->middleware('api')
			 ->namespace($this->namespace)
			 ->group(base_path('routes/api.php'));
	}
}

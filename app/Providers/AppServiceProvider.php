<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Wpb\String_Blade_Compiler\Facades\StringBlade;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		StringBlade::extend(function ($value) {
			return preg_replace(
				'/(\s*)@variable\(\'([a-zA-Z0-9_]+):([a-zA-Z0-9_]+)\'\)(\s*)/',
				'$1<?php variable(\'$2\', \$$2 ?? null, \'$3\'); ?>$3',
				$value);
		});
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}

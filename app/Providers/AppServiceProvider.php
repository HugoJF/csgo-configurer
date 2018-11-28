<?php

namespace App\Providers;

use App\Config;
use App\FieldList;
use App\List_;
use App\Observers\ConfigObserver;
use App\Observers\FieldListObserver;
use App\Observers\ListObserver;
use App\Observers\PluginObserver;
use App\Observers\ServerObserver;
use App\Observers\UserObserver;
use App\Plugin;
use App\Server;
use App\User;
use Illuminate\Support\Facades\Schema;
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
		Schema::defaultStringLength(191);

		Config::observe(ConfigObserver::class);
		FieldList::observe(FieldListObserver::class);
		List_::observe(ListObserver::class);
		Plugin::observe(PluginObserver::class);
		Server::observe(ServerObserver::class);
		User::observe(UserObserver::class);

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

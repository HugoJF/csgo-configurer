<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


/**
 * Authentication.
 */
Route::get('login', 'AuthController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


Route::get('builder', function () {
	$cfg =  View::make('config_example')->render();
	preg_match_all('/\$\$([a-zA-Z0-9_-]*)\$\$/', $cfg, $matches);

	// list of keys
	// dd($matches[1]);

	$b1 = [
		'hostname' => 'first name',
		'players' => 15,
	];

	$b2 = [
		'hostname' => 'second name',
		'los' => 'true',
	];

	dd($b1 + $b2);


});

Route::get('storage', function () {

	app('App\Http\Controllers\FileController')->sync_folders();
	app('App\Http\Controllers\FileController')->sync_plugins_files();

	return \App\Plugin::with('files')->get()->toJson();

});

Route::get('installations', 'InstallationController@index')->name('installation.index');
Route::get('installations/create', 'InstallationController@create')->name('installation.create');
Route::get('installations/{installation}', 'InstallationController@show')->name('installation.show');
Route::post('installations', 'InstallationController@store')->name('installation.store');
Route::get('installations/{installation}/edit', 'InstallationController@edit')->name('installation.edit');
Route::patch('installations/{installation}', 'InstallationController@update')->name('installation.update');
Route::delete('installations/{installation}', 'InstallationController@delete')->name('installation.delete');

Route::get('installations/{installation}/add-plugin', 'InstallationController@add_plugin')->name('installation.add-plugin');
Route::delete('installations/{installation}/remove-plugin/{plugin}', 'InstallationController@remove_plugin')->name('installation.remove-plugin');
Route::patch('installations/{installation}/add-plugin/{plugin}', 'InstallationController@store_plugin')->name('installation.store-plugin');

Route::get('installations/{installation}/selection/{plugin}', 'InstallationController@create_selection')->name('installation.create-selection');
Route::post('installations/{installation}/selection/{plugin}', 'InstallationController@store_selection')->name('installation.store-selection');

Route::get('servers', 'ServerController@index')->name('server.index');
Route::get('servers/create', 'ServerController@create')->name('server.create');
Route::get('servers/{server}/edit', 'ServerController@edit')->name('server.edit');
Route::get('servers/{server}/render', 'ServerController@render')->name('server.render');
Route::patch('servers/{server}', 'ServerController@update')->name('server.update');
Route::get('servers/{server}', 'ServerController@show')->name('server.show');
Route::post('servers', 'ServerController@store')->name('server.store');

Route::get('configs/{config}/constant/create', 'ConstantController@create')->name('constant.create');
Route::post('configs/{config}/constant', 'ConstantController@store')->name('constant.store');

Route::get('configs/create/{type?}/{id?}', 'ConfigController@create')->name('config.create');
Route::get('configs/{config}', 'ConfigController@show')->name('config.show');
Route::get('configs', 'ConfigController@index')->name('config.index');
Route::get('configs/{config}/edit', 'ConfigController@edit')->name('config.edit');
Route::patch('configs/{config}', 'ConfigController@update')->name('config.update');
Route::delete('configs/{config}', 'ConfigController@delete')->name('config.delete');
Route::post('configs/{type?}/{id?}', 'ConfigController@store')->name('config.store');


Route::get('plugins/{plugin}/files', 'FileController@index')->name('file.index');
Route::get('plugins/{plugin}/files/{file}', 'FileController@show_plugin_file')->name('file.show');
Route::get('servers/{server}/files/{file}', 'FileController@show_server_file')->name('file.show');

Route::get('files/{file}/edit', 'FileController@edit')->name('file.edit');
Route::patch('/files/{file}', 'FileController@update')->name('file.update');

Route::get('plugins/create', 'PluginController@create')->name('plugin.create');
Route::get('plugins/{plugin}', 'PluginController@show')->name('plugin.show');
Route::get('plugins', 'PluginController@index')->name('plugin.index');
Route::post('plugins', 'PluginController@store')->name('plugin.store');


Route::get('/home', function() { return redirect('/'); })->name('home');
Route::get('/user/settings', function() { return 'TODO'; })->name('users.settings');

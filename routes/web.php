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

	return \App\Template::with('files')->get()->toJson();

});

Route::get('installations', 'InstallationController@index')->name('installation.index');
Route::get('installations/create', 'InstallationController@create')->name('installation.create');
Route::get('installations/{installation}', 'InstallationController@show')->name('installation.show');
Route::post('installations', 'InstallationController@store')->name('installation.store');
Route::get('installations/{installation}/edit', 'InstallationController@edit')->name('installation.edit');
Route::patch('installations/{installation}', 'InstallationController@update')->name('installation.update');
Route::delete('installations/{installation}', 'InstallationController@delete')->name('installation.delete');

Route::get('installations/{installation}/add-template', 'InstallationController@add_template')->name('installation.add-template');
Route::delete('installations/{installation}/remove-template/{template}', 'InstallationController@remove_template')->name('installation.remove-template');
Route::patch('installations/{installation}/add-template/{template}', 'InstallationController@store_template')->name('installation.store-template');

Route::get('installations/{installation}/selection/{template}', 'InstallationController@create_selection')->name('installation.create-selection');
Route::post('installations/{installation}/selection/{template}', 'InstallationController@store_selection')->name('installation.store-selection');

Route::get('servers', 'ServerController@index')->name('server.index');
Route::get('servers/create', 'ServerController@create')->name('server.create');
Route::get('servers/{server}/edit', 'ServerController@edit')->name('server.edit');
Route::get('servers/{server}/render', 'ServerController@render')->name('server.render');
Route::patch('servers/{server}', 'ServerController@update')->name('server.update');
Route::get('servers/{server}', 'ServerController@show')->name('server.show');
Route::post('servers', 'ServerController@store')->name('server.store');

Route::get('bundles/{bundle}/constant/create', 'ConstantController@create')->name('constant.create');
Route::post('bundles/{bundle}/constant', 'ConstantController@store')->name('constant.store');

Route::get('bundles/create/{type?}/{id?}', 'BundleController@create')->name('bundle.create');
Route::get('bundles/{bundle}', 'BundleController@show')->name('bundle.show');
Route::get('bundles', 'BundleController@index')->name('bundle.index');
Route::get('bundles/{bundle}/edit', 'BundleController@edit')->name('bundle.edit');
Route::patch('bundles/{bundle}', 'BundleController@update')->name('bundle.update');
Route::delete('bundles/{bundle}', 'BundleController@delete')->name('bundle.delete');
Route::post('bundles/{type?}/{id?}', 'BundleController@store')->name('bundle.store');


Route::get('templates/{template}/files', 'FileController@index')->name('file.index');
Route::get('templates/{template}/files/{file}', 'FileController@show')->name('file.show');

Route::get('files/{file}/edit', 'FileController@edit')->name('file.edit');
Route::patch('/files/{file}', 'FileController@update')->name('file.update');

Route::get('templates/create', 'TemplateController@create')->name('template.create');
Route::get('templates/{template}', 'TemplateController@show')->name('template.show');
Route::get('templates', 'TemplateController@index')->name('template.index');
Route::post('templates', 'TemplateController@store')->name('template.store');


Route::get('/home', function() { return 'TODO'; })->name('home');
Route::get('/user/settings', function() { return 'TODO'; })->name('users.settings');

<?php

/**
 * Authentication.
 */
Route::get('login', 'AuthController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', function () {
	return view('welcome');
});

Route::get('/home', function () {
	return redirect('/');
})->name('home');

Route::get('sync', function () {
	event(new \App\Events\PluginsSynchronizationRequest());

	return 'requested!';
});


Route::middleware(['logged'])->group(function () {

	Route::get('/user/settings', function () {
		return 'TODO';
	})->name('users.settings');

	Route::prefix('installations')->name('installation.')->group(function () {
		Route::get('/', 'InstallationController@index')->name('index');
		Route::get('create', 'InstallationController@create')->name('create');
		Route::get('{installation}', 'InstallationController@show')->name('show');
		Route::get('{installation}/selection/{plugin}', 'InstallationController@create_selection')->name('create-selection');
		Route::get('{installation}/edit', 'InstallationController@edit')->name('edit');
		Route::get('{installation}/add-plugin', 'InstallationController@add_plugin')->name('add-plugin');

		Route::post('{installation}/selection/{plugin}', 'InstallationController@store_selection')->name('store-selection');
		Route::post('/', 'InstallationController@store')->name('store');

		Route::patch('{installation}', 'InstallationController@update')->name('update');
		Route::patch('{installation}/add-plugin/{plugin}', 'InstallationController@store_plugin')->name('store-plugin');

		Route::delete('{installation}', 'InstallationController@delete')->name('delete');
		Route::delete('{installation}/remove-plugin/{plugin}', 'InstallationController@remove_plugin')->name('remove-plugin');
	});

	Route::prefix('servers')->name('server.')->group(function () {
		Route::get('/', 'ServerController@index')->name('index');
		Route::get('create', 'ServerController@create')->name('create');
		Route::get('{server}/edit', 'ServerController@edit')->name('edit');
		Route::get('{server}/render', 'ServerController@render')->name('render');
		Route::get('{server}/sync', 'ServerController@sync')->name('sync');
		Route::get('{server}', 'ServerController@show')->name('show');

		Route::post('/', 'ServerController@store')->name('store');

		Route::patch('{server}', 'ServerController@update')->name('update');

		Route::delete('{server}', 'ServerController@delete')->name('delete');
	});

	Route::prefix('constant')->name('constant.')->group(function () {
		Route::get('{config}/constant/create/{field_list?}', 'ConstantController@create')->name('create');

		Route::post('{config}/constant', 'ConstantController@store')->name('store');
	});

	Route::prefix('config')->name('config.')->group(function () {
		Route::get('/', 'ConfigController@index')->name('index');
		Route::get('create/{type?}/{id?}', 'ConfigController@create')->name('create');
		Route::get('{config}', 'ConfigController@show')->name('show');
		Route::get('{config}/edit', 'ConfigController@edit')->name('edit');

		Route::post('{type?}/{id?}', 'ConfigController@store')->name('store');

		Route::patch('{config}', 'ConfigController@update')->name('update');

		Route::delete('{config}', 'ConfigController@delete')->name('delete');
	});

	Route::prefix('field-list')->name('field-list.')->group(function () {
		Route::get('create/{plugin}', 'FieldListController@create')->name('create');
		Route::get('{field_list}/edit', 'FieldListController@edit')->name('edit');

		Route::post('{plugin}', 'FieldListController@store')->name('store');

		Route::patch('{field_list}', 'FieldListController@update')->name('update');

		Route::delete('{field_list}', 'FieldListController@delete')->name('delete');
	});

	Route::prefix('field')->name('field.')->group(function () {
		Route::get('create/{field_list}', 'FieldController@create')->name('create');
		Route::get('{field}/edit', 'FieldController@edit')->name('edit');

		Route::post('{field_list}', 'FieldController@store')->name('store');

		Route::patch('{field}', 'FieldController@update')->name('update');

		Route::delete('{field}', 'FieldController@delete')->name('delete');
	});

	Route::prefix('files')->name('file.')->group(function () {
		Route::get('{file}/edit', 'FileController@edit')->name('edit');

		Route::patch('{file}', 'FileController@update')->name('update');
	});

	Route::prefix('plugins')->name('plugin.')->group(function () {
		Route::get('create', 'PluginController@create')->name('create');
		Route::get('plugins', 'PluginController@sync')->name('sync');
		Route::get('{plugin}', 'PluginController@show')->name('show');
		Route::get('/', 'PluginController@index')->name('index');

		Route::post('/', 'PluginController@store')->name('store');
	});

	Route::name('file.')->group(function () {
		Route::get('plugins/{plugin}/files', 'FileController@index')->name('index');
		Route::get('plugins/{plugin}/files/{file}', 'FileController@show_plugin_file')->name('show');
		Route::get('servers/{server}/files/{file}', 'FileController@show_server_file')->name('server_show');
	});
});

Route::get('config/{config}/variables', function (\App\Config $config) {
	$fieldsLists = $config->fieldLists();
	$variables = [];


	foreach ($fieldsLists as $fieldsList) {
		foreach ($fieldsList->fields as $field) {
			$variables[] = $field->toArray();
		}
	}

	foreach ($variables as $key => $variable) {
		$variables[$key]['name'] = $variable['name'] . ' - ' . $variable['description'];
	}

	return $variables;
})->name('test');
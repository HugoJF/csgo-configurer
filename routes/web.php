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
	Route::get('{server}/render-config', 'ServerController@renderConfig')->name('render-config');

	Route::post('/', 'ServerController@store')->name('store');

	Route::patch('{server}', 'ServerController@update')->name('update');

	Route::delete('{server}', 'ServerController@delete')->name('delete');
});

Route::prefix('constant')->name('constant.')->group(function () {
	Route::name('config.')->group(function () {
		Route::get('config/{config}/constant/create/{field_list?}', 'ConstantController@configCreate')->name('create');

		Route::post('config/{config}/constant', 'ConstantController@configStore')->name('store');
	});

	Route::name('list.')->group(function () {
		Route::get('list/{list}/constant/create/{field_list?}', 'ConstantController@listCreate')->name('create');

		Route::post('list/{list}/constant', 'ConstantController@listStore')->name('store');
	});

	Route::get('{constant}/activate', 'ConstantController@activate')->name('activate');
	Route::get('{constant}/deactivate', 'ConstantController@deactivate')->name('deactivate');
	Route::get('{constant}/edit', 'ConstantController@edit')->name('edit');

	Route::patch('{constant}', 'ConstantController@update')->name('update');

	Route::delete('{constant}', 'ConstantController@delete')->name('delete');
});

Route::prefix('list')->name('list.')->group(function () {
	Route::name('config.')->group(function () {
		Route::get('config/{config}/create/{field_list}', 'ListController@configCreate')->name('create');

		Route::post('config/{config}/field-list/{field_list}', 'ListController@configStore')->name('store');
	});

	Route::name('self.')->group(function () {
		Route::get('{list}/list/create/{field_list}', 'ListController@listCreate')->name('create');

		Route::post('{list}/field-list/{field_list}', 'ListController@listStore')->name('store');
	});

	Route::get('{list}/activate', 'ListController@activate')->name('activate');
	Route::get('{list}/deactivate', 'ListController@deactivate')->name('deactivate');
	Route::get('{list}', 'ListController@show')->name('show');
	Route::get('{list}/edit', 'ListController@edit')->name('edit');

	Route::patch('{list}', 'ListController@update')->name('update');

	Route::delete('{list}', 'ListController@delete')->name('delete');
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
	Route::name('plugin.')->group(function () {
		Route::get('create/plugin/{plugin}', 'FieldListController@pluginCreate')->name('create');
		Route::post('plugin/{plugin}', 'FieldListController@pluginStore')->name('store');
	});

	Route::name('self.')->group(function () {
		Route::get('create/{field_list}', 'FieldListcontroller@fieldListCreate')->name('create');
		Route::post('{field_list}', 'FieldListController@fieldListStore')->name('store');
	});

	Route::get('{field_list}', 'FieldListController@show')->name('show');

	Route::get('{field_list}/edit', 'FieldListController@edit')->name('edit');

	Route::patch('{field_list}', 'FieldListController@update')->name('update');

	Route::delete('{field_list}', 'FieldListController@delete')->name('delete');
});

Route::prefix('field')->name('field.')->group(function () {
	Route::name('field-list.')->group(function () {
		Route::get('create/field-list/{field_list}', 'FieldController@createFieldList')->name('create');

		Route::post('field-list/{field_list}', 'FieldController@storeFieldList')->name('store');
	});

	Route::name('plugin.')->group(function () {
		Route::get('create/plugin/{plugin}', 'FieldController@createPlugin')->name('create');

		Route::post('plugin/{plugin}', 'FieldController@storePlugin')->name('store');
	});

	Route::get('{field}/require', 'FieldController@require')->name('require');
	Route::get('{field}/optional', 'FieldController@optional')->name('optional');
	Route::get('{field}/edit', 'FieldController@edit')->name('edit');

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
	Route::get('plugins/{plugin}/files/{file}', 'FileController@showPluginFile')->name('show');
	Route::get('servers/{server}/files/{file}', 'FileController@showServerFile')->name('server_show');

	Route::patch('{file}/make-renderable', 'FileController@makeRenderable')->name('make-renderable');
	Route::patch('{file}/make-static', 'FileController@makeStatic')->name('make-static');
});

Route::get('plugins/{plugin}/fields', function (\App\Plugin $plugin) {
	$parser = new \App\Classes\PluginFieldParser($plugin);

	return $parser->parse()->result();
})->name('plugin.fields');

Route::get('config/{config}/values', function (\App\Config $config) {
	$parser = new \App\Classes\ConfigValueParser($config);

	return $parser->parse()->result();
})->name('config.values');

Route::get('plugins/{plugin}/manifest', function (\App\Plugin $plugin) {
	$plugin->load('fields', 'fieldLists', 'fieldLists.fields');

	return $plugin;
})->name('plugin.manifest');

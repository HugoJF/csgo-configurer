<?php

if (!function_exists('variable')) {

	function variable($name, $value, $default)
	{
		return \App\Classes\VariableHandler::variable($name, $value, $default);
	}

	function manifest($data)
	{
		return \App\Classes\VariableHandler::manifest($data);
	}
}

if (!function_exists('homeBreadcrumb')) {
	function homeBreadcrumb()
	{
		return new \App\Classes\Breadcrumb('Home', 'home');
	}
}
<?php

if (!function_exists('variable')) {

	function variable($name, $value, $default)
	{
		return \App\Classes\VariableHandler::variable($name, $value, $default);
	}
}

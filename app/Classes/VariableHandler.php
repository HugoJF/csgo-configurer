<?php

namespace App\Classes;

class VariableHandler
{
	static $rendering = true;
	static $variables = [];

	public static function variable($name, $value, $default)
	{
		if (static::$rendering) {
			if ($value == null) {
				return $value;
			} else {
				return $default;
			}
		} else {
			static::$variables[] = $name;
		}

		return '';
	}

	public static function rendering()
	{
		static::$rendering = true;
	}

	public static function inspecting()
	{
		static::$rendering = false;
	}

	public static function getVariables()
	{
		return static::$variables;
	}

}
<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/21/2018
 * Time: 5:20 AM
 */

namespace App\Classes;


class CompoundVariableTranslator
{
	public const MODE_EXCEPTION = 1;
	public const MODE_REPLACE_EMPTY = 2;
	public const MODE_NO_REPLACE = 3;

	private $config;

	private $loopList;

	private $mode;

	public function __construct(array &$config)
	{
		$this->config = &$config;
	}

	public function translate()
	{
		$this->translatePass();

		return $this;
	}

	public function setMode($mode)
	{
		$this->mode = $mode;

		return $this;
	}

	public function setModeException()
	{
		return $this->setMode(static::MODE_EXCEPTION);
	}

	public function setModeReplaceEmpty()
	{
		return $this->setMode(static::MODE_REPLACE_EMPTY);
	}

	public function setModeNoReplace()
	{
		return $this->setMode(static::MODE_NO_REPLACE);
	}

	public function translatePass()
	{
		foreach ($this->config as $key => $item) {
			$this->resetLoopCheck();
			$this->translateItem($key, $item);
		}
	}

	public function resetLoopCheck()
	{
		$this->loopList = [];
	}

	public function loopCheck($item)
	{
		if (in_array($item, $this->loopList)) {
			throw new \Exception("Item {$item} is already in the list, loop detected");
		} else {
			$this->loopList[] = $item;
		}
	}

	public function translateItem($key, $item)
	{
		if (($templateCount = $this->needsTranslation($item, $matches))) {
			$this->config[ $key ] = $this->replaceTemplates($item, $templateCount, $matches);
		}
	}

	public function replaceTemplates($item, $templateCount, $matches)
	{
		$translation = $item;

		for ($i = 0; $i < $templateCount; $i++) {
			$translation = $this->replaceTemplate($translation, $matches[0][ $i ], $matches[1][ $i ]);
		}

		return $translation;
	}

	private function needsTranslation($value, &$match)
	{
		if (is_array($value))
			return false;

		$count = preg_match_all('/{%\s*([a-zA-Z0-9.-_]+)\s*%}/', $value, $match);

		return $count;
	}

	private function replaceTemplate($original, $match, $variable)
	{
		$variable = $this->getVariableByString($variable);

		if ($variable === false) {
			return $match;
		} else {
			return str_replace($match, $variable, $original);
		}
	}

	private function getVariableByString($path)
	{
		$this->loopCheck($path);

		$pieces = explode('.', $path);
		$value = $this->config;
		$k = null;
		foreach ($pieces as $key => $piece) {
			$k = $key;
			if (array_key_exists($piece, $value)) {
				$value = $value[ $piece ];
			} else {
				dd($this->config);
				switch ($this->mode) {
					default:
					case static::MODE_EXCEPTION:
						throw new \Exception("Bad path '{$path}' on part '{$piece}'");
						break;
					case static::MODE_REPLACE_EMPTY:
						return '';
						break;
					case static::MODE_NO_REPLACE:
						return false;
						break;
				}
			}
		}
		if ($this->needsTranslation($value, $matches)) {
			$this->translateItem($k, $value);
		}

		return $value;
	}
}
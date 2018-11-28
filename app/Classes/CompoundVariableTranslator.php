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
		// Save reference to edit array directly
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
			$this->translateItem($this->config, $key);
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

	/**
	 * @param $base - base list for item
	 * @param $key  - item key
	 *
	 * @throws \Exception
	 */
	public function translateItem(&$base, $key)
	{
		$this->resetLoopCheck();

		$item = &$base[$key];

		// Check if item is not a list and needs translation
		if (!is_array($item) && ($templateCount = $this->needsTranslation($item, $matches))) {
			// Replace item templates
			$item = $this->replaceTemplates($item, $templateCount, $matches);
		} else if (is_array($item)) {
			// Pass to list translator
			$this->translateList($item);
		}
	}

	/**
	 * Translate a list of items
	 *
	 * @param $list - list of items that may need translation
	 *
	 * @throws \Exception
	 */
	private function translateList(&$list)
	{
		foreach ($list as $k => $i) {
			$this->translateItem($list, $k);
		}
	}

	/**
	 * @param $item
	 * @param $templateCount
	 * @param $matches
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function replaceTemplates($item, $templateCount, $matches)
	{
		for ($i = 0; $i < $templateCount; $i++) {
			$item = $this->replaceTemplate($item, $matches[0][ $i ], $matches[1][ $i ]);
		}

		return $item;
	}

	/**
	 * @param $value
	 * @param $match
	 *
	 * @return false|int
	 */
	private function needsTranslation($value, &$match)
	{
		$count = preg_match_all('/{%\s*([a-zA-Z0-9.-_]+)\s*%}/', $value, $match);

		return $count;
	}

	/**
	 * @param $original
	 * @param $match
	 * @param $variable
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	private function replaceTemplate($original, $match, $variable)
	{
		$variable = $this->getVariableByString($variable);

		if ($variable === false) {
			return $match;
		} else {
			return str_replace($match, $variable, $original);
		}
	}

	/**
	 * @param $path - variable path as keys separated by dots.
	 *
	 * @return array|bool|mixed|string
	 * @throws \Exception
	 */
	private function getVariableByString($path)
	{
		$this->loopCheck($path);

		// Get every key in path
		$pieces = explode('.', $path);

		// Start at root
		$value = $this->config;

		// Expose scope of variable
		$k = null;

		// Traverse array
		foreach ($pieces as $key => $piece) {
			$k = $key;

			// Move root pointer if path is correct
			if (array_key_exists($piece, $value)) {
				$value = $value[ $piece ];
			} else {
				return $this->runMode($piece, $path);
			}
		}

		// If resulting variable needs translation, translate it
		if (( $templateCount = $this->needsTranslation($value, $matches))) {
			$value = $this->replaceTemplates($value, $templateCount, $matches);
		}

		return $value;
	}

	/**
	 * @param $piece
	 * @param $path
	 *
	 * @return bool|string
	 * @throws \Exception
	 */
	protected function runMode($piece, $path) {
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
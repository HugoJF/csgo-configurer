<?php

namespace App\Classes;


use App\Config;
use App\List_;
use Illuminate\Support\Collection;

class ConfigValueParser
{
	private $config;

	private $result;

	public function __construct(Config $config)
	{
		$this->result = [];
		$this->config = $config;
	}

	public function parse()
	{
		$this->parseLists($this->config->lists);
		$this->parseConstants($this->config->constants);

		return $this;
	}


	private function parseLists(Collection $lists, $prefix = '')
	{
		$lists->each(function (List_ $list) use ($prefix) {
			$this->parseList($list, $prefix);
		});
	}

	private function parseList(List_ $list, $prefix = '')
	{
		$prefix = $this->mergePrefix($prefix, $list->fieldList->key);
		$prefix = $this->mergePrefix($prefix, $list->key);

		$this->parseConstants($list->constants, $prefix);
		$this->parseLists($list->lists, $prefix);
	}

	private function parseConstants(Collection $constants, $prefix = '')
	{
		$parsedConstants = $constants->map(function ($item) use ($prefix) {
			$key = $this->mergePrefix($prefix, $item->key);

			$a['display'] = "<strong><u>{%{$key}%}</u></strong>: {$item->value}";
			$a['key'] = "{%{$key}%}";

			return $a;
		})->toArray();

		$this->result = array_merge($this->result, $parsedConstants);
	}

	private function mergePrefix($prefix, $info)
	{
		if ($prefix) {
			$merged = $prefix . '.' . $info;
		} else {
			$merged = $info;
		}

		return $merged;
	}

	public function result()
	{
		return $this->result;
	}
}
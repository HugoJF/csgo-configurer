<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/21/2018
 * Time: 4:15 AM
 */

namespace App\Classes;


use App\Plugin;

class PluginFieldParser
{
	private $plugin;

	private $result;

	public function __construct(Plugin $plugin)
	{
		$this->plugin = $plugin;
	}

	public function parse()
	{
		$this->result = $this->plugin->fields->transform(function ($item) {
			$item['display'] = $item['key'] . ' - ' . $item['description'];

			return $item;
		});

		return $this;
	}

	public function result()
	{
		return $this->result;
	}
}
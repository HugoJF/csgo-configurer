<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/21/2018
 * Time: 4:15 AM
 */

namespace App\Classes;


use App\Field;
use App\FieldList;
use App\Plugin;
use Illuminate\Support\Collection;

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
		$this->parsePlugin($this->plugin);

		return $this;
	}

	public function parsePlugin(Plugin $plugin)
	{
		$this->parseFields($plugin->fields);
		$this->parseFieldLists($plugin->fieldLists);
	}

	public function parseFields(Collection $fields)
	{
		$fields->each(function ($field) {
			$piece['display'] = $field['key'] . ' - ' . $field['description'];
			$piece['key'] = $field['key'];
			$this->result[] = $piece;
		});
	}

	public function parseFieldLists(Collection $fieldLists)
	{
		$fieldLists->each(function (FieldList $fieldList) {
			$this->parseFields($fieldList->fields);
		});
	}


	public function result()
	{
		return $this->result;
	}
}
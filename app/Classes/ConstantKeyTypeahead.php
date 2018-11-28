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
use App\Installation;
use App\Plugin;
use App\Server;
use App\User;
use Illuminate\Support\Collection;

class ConstantKeyTypeahead
{
	private $result;

	public function __construct()
	{
	}

	public function parse()
	{
		foreach (Plugin::all() as $plugin) {
			$this->parsePlugin($plugin);
		}

		return $this;
	}

	public function parsePlugin(Plugin $plugin)
	{
		if ($plugin->data && $plugin->data->fields) {
			$this->parseFields($plugin->data->fields);
		}
		if ($plugin->data && $plugin->data->fieldLists) {
			$this->parseFieldLists($plugin->data->fieldLists);
		}
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
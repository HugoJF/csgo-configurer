<?php

namespace App\Classes;


use App\Config;
use App\Installation;
use App\List_;
use App\Plugin;
use App\Server;
use App\User;
use Illuminate\Support\Collection;

class ConstantValueTypeahead
{
	private $config;

	private $result;

	public function __construct()
	{
		$this->result = [];
	}

	public function parse()
	{
		$user = \Auth::user();

		$this->parseUser($user);

		return $this;
	}

	public function parseUser(User $user): void
	{
		$this->parseConfigs($user->configs);

		$this->parseServers($user->servers);
	}

	public function parseServers(Collection $servers): void
	{
		foreach ($servers as $server) {
			$this->parseServer($server);
		}
	}

	public function parseServer(Server $server): void
	{
		$this->parseConfigs($server->configs);
		if($server->installation) {
			$this->parseInstallation($server->installation);
		}
	}

	public function parseInstallation(Installation $installation): void
	{
		$this->parsePlugins($installation->plugins);
	}

	public function parsePlugins(Collection $plugins): void
	{
		foreach ($plugins as $plugin) {
			$this->parsePlugin($plugin);
		}
	}

	public function parsePlugin(Plugin $plugin): void
	{
		$this->parseConfigs($plugin->configs);
	}

	public function parseConfigs(Collection $configs): void
	{
		foreach ($configs as $config) {
			$this->parseConfig($config);
		}
	}

	public function parseConfig(Config $config): void
	{
//		$this->parseLists($config->data->descendants->toFlatTree());
		$this->parseLists(List_::descendantsAndSelf($config->data)->load('descendants')->toFlatTree());
	}


	private function parseLists(Collection $lists, $prefix = '')
	{
		$lists->each(function (List_ $list) use ($prefix) {
			$this->parseList($list, $prefix);
		});
	}

	private function parseList(List_ $list, $prefix = '')
	{
		$key = $list->fieldList ? $list->fieldList->key : $list->key;
		$prefix = $this->mergePrefix($prefix, $key);
		if ($list->key) {
			$prefix = $this->mergePrefix($prefix, $list->key);
		}

		$this->parseConstants($list->constants, $prefix);
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
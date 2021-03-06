<?php

namespace App;

use App\Classes\CompoundVariableTranslator;
use App\Classes\SmartLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Server extends Model
{
	use RoutesActions;

	/*********************
	 * LARAVEL OVERRIDES *
	 *********************/
	protected $fillable = [
		'name', 'ip', 'port', 'password', 'ftp_user', 'ftp_password', 'ftp_host', 'ftp_root',
	];

	protected $dates = [
		'render_requested_at', 'rendered_at', 'sync_requested_at', 'synced_at',
	];

	protected $routeNamePrefix = 'server.';


	public static function indexBreadcrumb()
	{
		return homeBreadcrumb()->add([
			'text'  => 'Servers',
			'route' => 'server.index',
		]);
	}

	public function showBreadcrumb()
	{
		return Server::indexBreadcrumb()->add([
			'text'  => $this->name,
			'route' => ['server.show', $this],
		]);
	}

	public function installation()
	{
		return $this->belongsTo('App\Installation');
	}

	public function configs()
	{
		return $this->morphMany('App\Config', 'owner');
	}

	public function files()
	{
		return $this->morphMany('App\File', 'owner');
	}

	public function getRenderableInstallationFiles()
	{
		if(!$this->installation) return [];

		$plugins = $this->installation->plugins->pluck('id');

		$files = File::whereIn('id', $plugins)->renderable()->get();

		return $files;
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function renders()
	{
		return $this->hasMany('App\Render');
	}

	public function syncs()
	{
		return $this->hasMany('App\Synchronization');
	}

	public function getPluginList()
	{
		if (!$this->installation) {
			return [];
		} else {
			return $this->installation->plugins->toArray();
		}

	}

	public function getConfigs()
	{
		// Prepare array
		$configs = [];

		// Load relation
		if ($this->installation) {
			$this->installation->load(['plugins']);
		}

		// Load other configs
		$user_configs = $this->user->configs()->get();
		$server_configs = $this->configs()->get();

		// Merge user configs
		foreach ($user_configs as $config) {
			$configs[] = $config;
		}

		// Merge installation configs
		if ($this->installation) {
			foreach ($this->installation->plugins as $plugin) {
				if ($plugin->pivot->selection) {
					$configs[] = $plugin->pivot->selection;
				}
			}
		}

		// Merge server configs
		foreach ($server_configs as $config) {
			$configs[] = $config;
		}

		// Sort configs by priority
		usort($configs, function ($b, $a) {
			return $b->priority - $a->priority;
		});

		return $configs;
	}

	public function getConstants()
	{
		$configs = $this->getConfigs();

		$finalConfig = [];
		$constants = [];

		foreach ($configs as $config) {
			// Process constants
			foreach ($config->data->constants as $constant) {
				if ($constant->active) {
					$finalConfig[ $constant->key ] = $constant->value;
					$constants[] = $constant;
				}
			}

			// Process lists
			$finalConfig = $this->customMerge($finalConfig, $this->processLists($config->data->children));
		}

		$translator = new CompoundVariableTranslator($finalConfig);
		$translator->setModeNoReplace()->translate();

		return [
			'constants' => $constants,
			'config'    => $finalConfig,
		];
	}


	private function processLists(Collection $lists)
	{
		$processedList = [];

		foreach ($lists as $list) {
			if ($list->active) {
				$this->processList($processedList, $list);
			}
		}

		return $processedList;
	}

	private function processList(array &$a, List_ $list)
	{
		$parentKey = $list->fieldList->key;

		// This is used to skip using the List key
		if ($list->key) {
			$a[ $parentKey ][ $list->key ] = $this->remapConstants($list);
			$a[ $parentKey ][ $list->key ]['_replace'] = $list->overwrites ? 'true' : 'false';
			$a[ $parentKey ][ $list->key ] = $this->customMerge($a[ $list->fieldList->key ][ $list->key ], $this->processLists($list->lists));
		} else {
			$a[ $parentKey ] = $this->remapConstants($list);
			$a[ $parentKey ]['_replace'] = $list->overwrites ? 'true' : 'false';
			$a[ $parentKey ] = $this->customMerge($a[ $list->fieldList->key ], $this->processLists($list->lists));
		}
	}

	private function remapConstants(List_ $list)
	{
		$constants = $list->constants->filter(function ($value) {
			return $value->active;
		})->mapWithKeys(function ($value) {
			return [$value->key => $value->value];
		});

		// Adds default required fields
		foreach ($list->fieldList->fields as $field) {
			if ($field->required && !$constants->get($field->key)) {
				$constants[ $field->key ] = $field->default;
			}
		}

		return $constants->toArray();
	}

	private function shouldReplace($key, $b)
	{
		if (is_array($b) && is_array($b[ $key ]) && array_key_exists('_replace', $b[ $key ]) && $b[ $key ]['_replace'] === 'true') {
			return true;
		} else {
			return false;
		}
	}

	private function customMerge(array $a, array $b)
	{
		foreach ($b as $key => $item) {
			// Check if replacing is needed
			$replace = $this->shouldReplace($key, $b);

			// Check if item is array that needs merge
			if (array_key_exists($key, $a) && is_array($a[ $key ]) && is_array($b[ $key ]) && $replace === false) {
				// Merge array
				$a[ $key ] = $this->customMerge($a[ $key ], $b[ $key ]);
			} else {
				// If it's sequential key, append to the end of array
				if (is_numeric($key) && !$replace) {
					$a[] = $item;
					// If it's associative, replace
				} else {
					$a[ $key ] = $item;
				}
			}
		}

		return $a;
	}

	public function renderConfig()
	{
		return $this->getConstants()['config'];
	}

	public function renderConstants()
	{
		return $this->getConstants()['constants'];
	}
}

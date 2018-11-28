<?php

namespace App\Observers;

use App\Plugin;

class PluginObserver
{
	public function deleted(Plugin $plugin)
	{
		$plugin->configs()->delete();
		$plugin->files()->delete();
		$plugin->fieldLists()->delete();
		$plugin->fields()->delete();
	}
}

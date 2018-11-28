<?php

namespace App\Observers;

use App\Config;

class ConfigObserver
{
    /**
     * Handle the config "deleted" event.
     *
     * @param  \App\Config  $config
     * @return void
     */
    public function deleted(Config $config)
    {
    	$config->constants()->delete();
    	$config->lists()->delete();
    }
}

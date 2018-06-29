<?php

namespace App\Listeners;

use App\Events\PluginsSynchronizationRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PluginsFolderSynchronizer implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PluginsSynchronizationRequest  $event
     * @return void
     */
    public function handle(PluginsSynchronizationRequest $event)
    {
		app('App\Http\Controllers\FileController')->sync_folders();
		app('App\Http\Controllers\FileController')->sync_plugins_files();
    }
}

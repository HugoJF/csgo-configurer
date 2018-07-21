<?php

namespace App\Listeners;

use App\Events\GenericBroadcastEvent;
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
    	event(new GenericBroadcastEvent('Plugin folder synchronization started!', 'Plugin template folder synchronization started!'));
		app('App\Http\Controllers\FileController')->syncFolders();
		app('App\Http\Controllers\FileController')->syncPluginsFiles();
		event(new GenericBroadcastEvent('Plugin folder synchronization finished!', 'Plugin template folder synchronization finished successfully!'));
    }
}

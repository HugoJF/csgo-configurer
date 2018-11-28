<?php

namespace App\Observers;

use App\Server;

class ServerObserver
{
    /**
     * Handle the server "deleted" event.
     *
     * @param  \App\Server  $server
     * @return void
     */
    public function deleted(Server $server)
    {
    	$server->configs()->delete();
    	$server->files()->delete();
    }
}

<?php

namespace App\Console\Commands;

use App\Events\PluginsSynchronizationRequest;
use Illuminate\Console\Command;

class PluginsSynchronize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugins:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues plugins template folder synchronization';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	event(new PluginsSynchronizationRequest());
    	$this->info('Plugin synchronization requested!');
    }
}

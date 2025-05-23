<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test if the application can run basic commands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Laravel framework is working!');
        $this->info('PHP version: ' . phpversion());
        $this->info('Laravel version: ' . app()->version());
        $this->info('Environment: ' . app()->environment());
        
        // Test config access
        $this->info('Cache driver: ' . config('cache.default'));
        $this->info('Session driver: ' . config('session.driver'));
        
        return Command::SUCCESS;
    }
}

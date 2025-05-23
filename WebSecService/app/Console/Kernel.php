<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\File;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Register the core command paths for discovery.
     * 
     * @return void
     */
    public function bootstrap()
    {
        // Run parent bootstrap first
        parent::bootstrap();

        // Ensure command discovery paths include Laravel's core commands
        $this->app->make('config')->set('commands.paths', array_merge(
            $this->app->make('config')->get('commands.paths', []),
            [base_path('vendor/laravel/framework/src/Illuminate/*/Console/Commands')]
        ));
    }
}

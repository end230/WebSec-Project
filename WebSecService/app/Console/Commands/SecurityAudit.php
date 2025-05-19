<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class SecurityAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:audit {--fix : Attempt to fix some issues automatically}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit the application for common security vulnerabilities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting WebSecService Security Audit...');
        $issues = 0;

        // Check environment mode
        $this->info('Checking environment...');
        if (app()->environment('production') && config('app.debug')) {
            $this->error('❌ Debug mode is enabled in production environment');
            $issues++;
            if ($this->option('fix')) {
                // We don't automatically fix this as it requires .env modification
                $this->warn('To fix: Set APP_DEBUG=false in your .env file');
            }
        } else {
            $this->info('✅ Debug mode correctly configured for environment');
        }

        // Check important directories permissions
        $this->info('Checking directory permissions...');
        $directories = [
            storage_path(),
            base_path('bootstrap/cache'),
        ];
        
        foreach ($directories as $dir) {
            if (file_exists($dir)) {
                $perms = fileperms($dir);
                if (($perms & 0x0002) !== 0) { // World writable
                    $this->error("❌ Directory $dir is world-writable: " . decoct($perms & 0777));
                    $issues++;
                    if ($this->option('fix')) {
                        chmod($dir, 0755);
                        $this->info("🔧 Fixed permissions for $dir");
                    }
                } else {
                    $this->info("✅ $dir has secure permissions");
                }
            }
        }

        // Check .env file
        $this->info('Checking .env file...');
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $perms = fileperms($envPath);
            if (($perms & 0x0077) !== 0) { // Should be 0600 or similar
                $this->error('❌ .env file permissions too open: ' . decoct($perms & 0777));
                $issues++;
                if ($this->option('fix')) {
                    chmod($envPath, 0600);
                    $this->info('🔧 Fixed .env file permissions');
                }
            } else {
                $this->info('✅ .env file has secure permissions');
            }
        }

        // Check middleware configuration
        $this->info('Checking security middleware...');
        if (!app()->has('App\Http\Middleware\SecurityHeaders')) {
            $this->error('❌ SecurityHeaders middleware not found or not registered');
            $issues++;
        } else {
            $this->info('✅ SecurityHeaders middleware registered');
        }

        // Check for HTTPS-only cookies in production
        $this->info('Checking secure cookie settings...');
        if (app()->environment('production') && !config('session.secure')) {
            $this->error('❌ Session cookies not set to secure in production');
            $issues++;
        } else if (app()->environment('production')) {
            $this->info('✅ Session cookies properly secured');
        }

        // Report results
        $this->newLine();
        if ($issues === 0) {
            $this->info('✅ Security audit completed. No issues found!');
        } else {
            $this->error("❌ Security audit completed. Found {$issues} potential issues.");
            if (!$this->option('fix')) {
                $this->info('Run with --fix option to attempt automatic fixes for some issues.');
            }
        }
        
        return $issues === 0 ? 0 : 1;
    }
}

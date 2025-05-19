<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Exception;
use PDO;
use PDOException;

class ConfigFallbacks
{
    /**
     * Set up fallback configurations when critical services are unavailable
     *
     * @return void
     */
    public static function setup()
    {
        try {
            // For artisan commands, set a short timeout to avoid hanging
            if (self::isRunningArtisan()) {
                ini_set('default_socket_timeout', 3);
                set_time_limit(30); // 30 seconds max for artisan commands
                
                // Special handling for artisan commands
                if (isset($_SERVER['argv']) && count($_SERVER['argv']) > 1) {
                    // For certain commands, don't even attempt DB connection
                    $noDbCommands = ['config:clear', 'config:cache', 'view:clear', 'cache:clear'];
                    foreach ($noDbCommands as $command) {
                        if (strpos(implode(' ', $_SERVER['argv']), $command) !== false) {
                            self::setupFallbacks('Bypassing database check for ' . $command);
                            return;
                        }
                    }
                }
            }
            
            // Check if facades are initialized before using them
            if (!app()->resolved('db')) {
                self::setupFallbacks('Facades not initialized yet');
                return;
            }

            // Check if database is available using a lightweight approach
            try {
                // Set a shorter timeout for database connections to prevent hanging
                $connection = DB::connection();
                $connection->getPdo()->setAttribute(PDO::ATTR_TIMEOUT, 5);
                
                // Use a simple quick query to test connection
                $connection->select('SELECT 1');
                // Database is available, no need for fallbacks
                return;
            } catch (PDOException $dbException) {
                // Database connection failed, set up fallbacks
                self::setupFallbacks($dbException->getMessage());
            } catch (Exception $dbException) {
                // Other database issues, set up fallbacks
                self::setupFallbacks($dbException->getMessage());
            }
        } catch (Exception $e) {
            // If an unexpected error occurs, still try to set up fallbacks
            self::setupEmergencyFallbacks();
            
            // If running from console, output the error
            if (self::isRunningArtisan()) {
                echo "Error during bootstrap: " . $e->getMessage() . PHP_EOL;
            }
        }
    }

    /**
     * Set up fallback configurations
     * 
     * @param string $reason The reason for setting up fallbacks
     * @return void
     */
    private static function setupFallbacks($reason)
    {
        // Log the database connection error if Log facade is available
        if (app()->resolved('log')) {
            Log::error('Database connection failed. Setting up fallbacks: ' . $reason);
        }
        
        // Set session driver to file instead of database
        Config::set('session.driver', 'file');
        
        // Set cache driver to file instead of database
        Config::set('cache.default', 'file');
        Config::set('cache.stores.file.driver', 'file');
        
        // Ensure cache path exists and is writable
        try {
            $cachePath = storage_path('framework/cache/data');
            if (!is_dir($cachePath)) {
                @mkdir($cachePath, 0755, true);
            }
        } catch (Exception $e) {
            // If storage_path is not available, use a fallback approach
            $basePath = defined('base_path') ? base_path() : dirname(__DIR__, 2);
            $cachePath = $basePath . '/storage/framework/cache/data';
            if (!is_dir($cachePath)) {
                @mkdir($cachePath, 0755, true);
            }
        }
        
        // Set queue driver to sync instead of database
        Config::set('queue.default', 'sync');
        
        // Configure auth to not depend on database
        Config::set('auth.providers.users.driver', 'eloquent');
        Config::set('auth.providers.users.model', 'App\Models\User');
        Config::set('auth.guards.web.driver', 'session');
        
        // Disable database-dependent features
        Config::set('database.connections.mysql.enabled', false);
        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');
        
        // Set maintenance mode to use file driver instead of database
        Config::set('app.maintenance.driver', 'file');
        
        // For Artisan commands, apply specific settings
        if (self::isRunningArtisan()) {
            Config::set('app.debug', true);
            
            // Important! Set the database migrations table to in-memory SQLite
            Config::set('database.migrations', 'migrations');
            
            // Make sure we don't wait for Redis or other services
            Config::set('queue.connections.sync.driver', 'sync');
            Config::set('cache.stores.array.driver', 'array');
            Config::set('session.driver', 'array');
        }
    }

    /**
     * Emergency fallbacks when even Config facade is unavailable
     * 
     * @return void
     */
    private static function setupEmergencyFallbacks()
    {
        // If we can't even use Config facade, try to use the app container directly
        $app = app();
        if ($app && method_exists($app, 'make')) {
            try {
                $config = $app->make('config');
                if ($config) {
                    $config->set('session.driver', 'file');
                    $config->set('cache.default', 'file');
                    $config->set('queue.default', 'sync');
                    $config->set('database.default', 'sqlite');
                    $config->set('database.connections.sqlite.database', ':memory:');
                }
            } catch (Exception $e) {
                // If running in console mode, give a helpful message
                if (self::isRunningArtisan()) {
                    echo "Critical bootstrap failure. Try: php artisan config:clear" . PHP_EOL;
                }
            }
        }
    }
    
    /**
     * Check if running in console as an Artisan command
     *
     * @return bool
     */
    private static function isRunningArtisan()
    {
        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }
}

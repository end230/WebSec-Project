<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseStatusService
{
    /**
     * Check if the database is available
     *
     * @return bool
     */
    public static function isAvailable(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            Log::error('Database connection error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a user-friendly message about database status
     *
     * @return string
     */
    public static function getStatusMessage(): string
    {
        if (self::isAvailable()) {
            return 'Database is connected and operational.';
        } else {
            return 'Database is currently unavailable. Some features may be limited.';
        }
    }
}

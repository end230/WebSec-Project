<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Add management_level column if it doesn't exist
            if (!Schema::hasColumn('roles', 'management_level')) {
                $table->string('management_level')->nullable()->after('guard_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Drop the management_level column if it exists
            if (Schema::hasColumn('roles', 'management_level')) {
                $table->dropColumn('management_level');
            }
        });
    }
};

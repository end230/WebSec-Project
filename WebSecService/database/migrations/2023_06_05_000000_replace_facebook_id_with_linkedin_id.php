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
        Schema::table('users', function (Blueprint $table) {
            // Drop facebook_id if it exists
            if (Schema::hasColumn('users', 'facebook_id')) {
                $table->dropColumn('facebook_id');
            }
            
            // Add linkedin_id column
            if (!Schema::hasColumn('users', 'linkedin_id')) {
                $table->string('linkedin_id')->nullable()->after('google_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add facebook_id back
            if (!Schema::hasColumn('users', 'facebook_id')) {
                $table->string('facebook_id')->nullable()->after('google_id');
            }
            
            // Remove linkedin_id
            if (Schema::hasColumn('users', 'linkedin_id')) {
                $table->dropColumn('linkedin_id');
            }
        });
    }
};

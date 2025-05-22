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
            // Add management_level field with enum constraint
            $table->enum('management_level', ['low', 'middle', 'high'])
                  ->nullable()
                  ->after('remember_token')
                  ->comment('User management level: low (customer tasks), middle (+ low management), high (full access)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('management_level');
        });
    }
};

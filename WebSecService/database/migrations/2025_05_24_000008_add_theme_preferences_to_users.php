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
            $table->boolean('theme_dark_mode')->default(false)->after('last_certificate_login');
            $table->enum('theme_color', ['default', 'energy', 'calm', 'ocean'])->default('default')->after('theme_dark_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['theme_dark_mode', 'theme_color']);
        });
    }
}; 
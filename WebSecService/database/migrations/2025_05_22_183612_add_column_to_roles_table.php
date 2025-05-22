<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default roles
        DB::table('roles')->insertOrIgnore([
            ['name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Editor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Customer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Employee', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Moderator', 'created_at' => now(), 'updated_at' => now()], // New role suggestion
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->whereIn('name', [
            'Admin', 'Editor', 'Customer', 'Employee', 'Moderator'
        ])->delete();
    }
};
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EditorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Editor role if it doesn't exist
        $editorRole = Role::firstOrCreate(['name' => 'Editor'], [
            'management_level' => User::MANAGEMENT_LEVEL_HIGH
        ]);
        
        // Create editor user if not exists
        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password'),
                'management_level' => User::MANAGEMENT_LEVEL_HIGH,
            ]
        );
        
        // Assign Editor role
        $editor->assignRole('Editor');
        
        $this->command->info('Editor user created successfully!');
    }
}

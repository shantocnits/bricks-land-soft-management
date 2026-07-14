<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure Spatie roles exist
        Role::findOrCreate('admin');
        Role::findOrCreate('user');
        Role::findOrCreate('demo');

        // 1. admin@gmail.com (admin account)
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'name' => 'admin',
            'password' => \Hash::make('12345678'),
            'role' => 'admin',
        ]);
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // 2. shanto@gmail.com (ggg - demo account)
        $demo = User::firstOrCreate([
            'email' => 'shanto@gmail.com'
        ], [
            'name' => 'ggg',
            'password' => \Hash::make('12345678'),
            'role' => 'demo',
        ]);
        if (!$demo->hasRole('demo')) {
            $demo->assignRole('demo');
        }
    }
}

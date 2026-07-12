<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Demo',
            'email' => 'demo@example.com',
            'password' => \Hash::make('12345678'),
            'role' => 'admin',
            'permissions' => null,
        ]);

        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => \Hash::make('12345678'),
            'role' => 'admin',
            'permissions' => null,
        ]);
    }
}

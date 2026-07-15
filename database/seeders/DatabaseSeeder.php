<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Setting;
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

        // 3. Seed category types setting
        Setting::firstOrCreate(
            ['key' => 'category_types'],
            ['value' => json_encode(['ইট', 'আধলা', 'অন্যান্য'])]
        );

        // 4. Seed categories (শ্রেণি ও রেট) — চালানের modal dropdown এর জন্য
        if (Category::count() === 0) {
            $categories = [
                ['name' => '১ নং',       'type' => 'ইট',       'rate' => 8.10],
                ['name' => 'পিকটি',       'type' => 'ইট',       'rate' => 9.00],
                ['name' => '২ নং (ক)',    'type' => 'ইট',       'rate' => 8.50],
                ['name' => '২ নং (খ)',    'type' => 'ইট',       'rate' => 7.50],
                ['name' => '৩ নং ছালট',  'type' => 'ইট',       'rate' => 4.50],
                ['name' => '৩ নং গরিয়া', 'type' => 'ইট',       'rate' => 6.00],
                ['name' => 'এলোট',        'type' => 'ইট',       'rate' => 3.00],
                ['name' => '১ নং আদলা',  'type' => 'আধলা',     'rate' => 4.50],
                ['name' => '৩ নং আদলা',  'type' => 'আধলা',     'rate' => 1.50],
                ['name' => 'রাবিশ',       'type' => 'অন্যান্য', 'rate' => 500.00],
                ['name' => 'খোয়া',        'type' => 'অন্যান্য', 'rate' => 120.00],
            ];

            foreach ($categories as $cat) {
                Category::create($cat);
            }
        }
    }
}

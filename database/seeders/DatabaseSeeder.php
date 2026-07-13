<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // The list of all sidebar menus/permissions
        $menuPermissions = [
            'dashboard',
            'challan',
            'payment',
            'delivery',
            'due_ledger',
            'cash_ledger',
            'load_ledger',
            'unload',
            'brick_ledger',
            'ledger',
            'customer',
            'sales_report',
            'inventory',
            'documents',
            'raw_material',
            'staff',
            'vehicle_acc',
            'vehicle_rent',
            'debts',
            'accounts',
            'production',
            'phone',
        ];

        // Create Spatie Permissions
        foreach ($menuPermissions as $perm) {
            Permission::findOrCreate($perm);
        }

        // Create Spatie Roles
        $adminRole = Role::findOrCreate('admin');
        $userRole = Role::findOrCreate('user');

        // Assign all permissions to Admin role
        $adminRole->syncPermissions($menuPermissions);

        // Assign dashboard, challan, and delivery to User role as basic default
        $userRole->syncPermissions([]);

        // Create Default Admin Users and assign Spatie Roles
        $demoUser = User::create([
            'name' => 'Demo',
            'email' => 'demo@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);
        $demoUser->assignRole($adminRole);

        $mainAdmin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);
        $mainAdmin->assignRole($adminRole);
    }
}

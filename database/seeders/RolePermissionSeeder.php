<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // উদাহরণ পারমিশন — নিজের মতো বাড়াও
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage crm']);
        Permission::firstOrCreate(['name' => 'view reports']);

        // roles তৈরি এবং permission attach
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo(['manage crm', 'view reports']);

        $user = Role::firstOrCreate(['name' => 'user']);
        $user->givePermissionTo(['view reports']);

        // default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // change later
            ]
        );

        if(!$adminUser->hasRole('admin')){
            $adminUser->assignRole('admin');
        }
    }
}

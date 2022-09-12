<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['name' => 'create-users']);
        Permission::firstOrCreate(['name' => 'edit-users']);
        Permission::firstOrCreate(['name' => 'delete-users']);

        Permission::firstOrCreate(['name' => 'create-announce']);
        Permission::firstOrCreate(['name' => 'edit-announce']);
        Permission::firstOrCreate(['name' => 'delete-announce']);

        $userRole = Role::firstOrCreate(['name' => 'User']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $adminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',
            'delete-announce',
        ]);

        $userRole->givePermissionTo([
            'create-announce',
            'edit-announce',
            'delete-announce',
        ]);
    }
}

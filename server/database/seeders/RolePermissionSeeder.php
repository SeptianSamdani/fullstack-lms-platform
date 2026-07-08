<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['admin', 'instructor', 'student'];
        foreach ($roles as $role) Role::create(['name' => $role]);

        $permissions = ['create courses', 'update courses', 'delete courses', 'manage users'];
        foreach ($permissions as $perm) Permission::create(['name' => $perm]);

        Role::findByName('admin')->givePermissionTo(Permission::all());
        Role::findByName('instructor')->givePermissionTo(['create courses', 'update courses', 'delete courses']);
    }
}

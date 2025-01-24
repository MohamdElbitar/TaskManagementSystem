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
        //
        $managerRole = Role::create(['name' => 'manager']);
        $userRole = Role::create(['name' => 'user']);

        Permission::create(['name' => 'create tasks']);
        Permission::create(['name' => 'update tasks']);
        Permission::create(['name' => 'delete tasks']);
        Permission::create(['name' => 'assign tasks']);
        Permission::create(['name' => 'view tasks']);
        Permission::create(['name' => 'update task status']);

        $managerRole->givePermissionTo(['create tasks', 'update tasks', 'delete tasks', 'assign tasks', 'view tasks']);
        $userRole->givePermissionTo(['view tasks', 'update task status']);
    }
}

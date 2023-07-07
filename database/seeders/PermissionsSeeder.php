<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create new permissions and assign to admin role
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::query()->firstOrCreate(['name' => 'team_user.create']);
        Permission::query()->firstOrCreate(['name' => 'team_user.update']);
        Permission::query()->firstOrCreate(['name' => 'team_user.delete']);
        Permission::query()->firstOrCreate(['name' => 'team_user.view']);
        Permission::query()->firstOrCreate(['name' => 'team.create']);
        Permission::query()->firstOrCreate(['name' => 'team.update']);
        Permission::query()->firstOrCreate(['name' => 'team.delete']);
        Permission::query()->firstOrCreate(['name' => 'team.view']);
        Permission::query()->firstOrCreate(['name' => 'user.create']);
        Permission::query()->firstOrCreate(['name' => 'user.update']);
        Permission::query()->firstOrCreate(['name' => 'user.delete']);
        Permission::query()->firstOrCreate(['name' => 'user.view']);
        Permission::query()->firstOrCreate(['name' => 'role.create']);
        Permission::query()->firstOrCreate(['name' => 'role.update']);
        Permission::query()->firstOrCreate(['name' => 'role.delete']);
        Permission::query()->firstOrCreate(['name' => 'role.view']);
        Permission::query()->firstOrCreate(['name' => 'permission.create']);
        Permission::query()->firstOrCreate(['name' => 'permission.update']);
        Permission::query()->firstOrCreate(['name' => 'permission.delete']);
        Permission::query()->firstOrCreate(['name' => 'permission.view']);
        Permission::query()->firstOrCreate(['name' => 'profile.update']);
        Permission::query()->firstOrCreate(['name' => 'profile.view']);
        Permission::query()->firstOrCreate(['name' => 'profile.delete']);
        Permission::query()->firstOrCreate(['name' => 'profile.create']);
    }
}

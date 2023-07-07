<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create user belonging to a team
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        // set the causer to the super admin user
        $activityLog = Activity::query()->first();

        if($activityLog)
        {
            $activityLog->causer_id = $superAdminUser->id;
            $activityLog->causer_type = User::class;
            $activityLog->subject_type = User::class;
            $activityLog->save();
        }
        // create a team
        $team = Team::factory()->create([
            'name' => 'Default ' . config('team.team_label'),
            'description' => 'Default Team for the application',
            'user_id' => $superAdminUser->id,

        ]);

        $superAdminRole = Role::query()->firstOrCreate([
            'name' => User::ROLE_SUPER_ADMIN
        ]);

        $adminRole = Role::query()->firstOrCreate([
            'name' => User::ROLE_ADMIN
        ]);
        $editorRole = Role::query()->firstOrCreate([
            'name' => User::ROLE_EDITOR
        ]);
        $userRole = Role::query()->firstOrCreate([
            'name' => User::ROLE_USER
        ]);

        $superAdminRole->givePermissionTo(Permission::all());
        $adminRole->givePermissionTo([
            'user.create',
            'user.update',
            'user.delete',
            'user.view',
        ]);
        $editorRole->givePermissionTo(
            [
                'user.create',
                'user.update',
                'user.delete',
                'user.view',
            ]
        );
        $userRole->givePermissionTo(
            [
                'profile.view',
                'profile.update',
                'profile.delete',
            ]
        );

        if ($team) {
            setPermissionsTeamId($team->id);
            $superAdminUser->assignRole([User::ROLE_SUPER_ADMIN]);
            $superAdminUser->teamUsers()->attach($superAdminUser->id, ['team_id' => $team->id]);
        }

    }
}

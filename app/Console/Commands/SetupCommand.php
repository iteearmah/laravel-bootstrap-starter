<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the application for first use.';

    /**
     * Execute the console command.
     */
    public function handle()
    : void
    {

        // Ask for db migration refresh, default is no
        if ($this->confirm('Do you wish to refresh migration before seeding, it will clear all old data?'))
        {
            // Call the php artisan migrate:refresh
            $this->call('migrate:refresh');
            $this->line('Data cleared, starting from blank database.');
        }

        // Seed the default permissions
        $permissions = User::defaultPermissions();

        foreach ($permissions as  $permission)
        {
            $this->info('Creating permission: '.$permission);
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->info('Default Permissions added.');
        // Confirm team creation
        $team_name = $this->ask('Enter the team name', 'Default ' . config('team.team_label'));
        $user = User::factory([
            'email' => 'super-admin@admin.com',
        ])->create();
        $team = Team::create([
            'name' => $team_name,
            'description' => 'Default Team for the application',
            'user_id' => $user->id,
        ]);
        $user->teams()->attach($team);
        $this->info('Team '.$team_name.' added successfully');
        $teamId = $team->id;

        // Confirm roles needed

        if ($this->confirm('Create Roles for user, default is admin and user? [y|N]', true))
        {
            // Ask for roles from input
            $input_roles = $this->ask('Enter roles in comma separate format.', 'super-admin,admin,user,owner,member');

            // Explode roles
            $roles_array = explode(',', $input_roles);

            // add roles
            foreach ($roles_array as $role)
            {
                $role = Role::firstOrCreate(['name' => trim($role), 'team_id' => $teamId]);

                if ($role->name === 'super-admin')
                {
                    // assign all permissions
                    $role->syncPermissions(Permission::all());
                    $this->info('Admin granted all the permissions');
                } else
                {
                    // for others by default only read access
                    $role->syncPermissions(Permission::where('name', 'LIKE',
                        'view_%')->get());
                }

                // create one user for each role
                $user = $this->assignRoleUser($user, $role);
            }

            $this->info('Roles '.$input_roles.' added successfully');
        } else
        {
            Role::firstOrCreate(['name' => 'user']);
            $this->info('Added only default user role.');
        }
        $this->info('User '.$user->name.' added successfully');

        if( $user->hasRole('super-admin') ) {
            $this->info('Here are your admin details to login:');
            $this->warn($user->email);
            $this->warn('Password is "password"');
        }

        $this->warn('All done :)');

    }

    private function assignRoleUser($user, $role) {
        //set default team
        setPermissionsTeamId($user);
        $user->assignRole($role->name);

        return $user;
    }
}

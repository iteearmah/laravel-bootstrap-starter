<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Permission\Models\Role;

class Team extends Model
{
    use HasFactory;

    public const LOGOS_DIRECTORY = 'teams/logos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'logo'
    ];


    /**
     * Get the owner that owns the Team
     *
     * @return BelongsTo
     */

    public function owner()
    : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }

    public function members()
    : HasManyThrough
    {
        return $this->hasManyThrough(User::class, TeamUser::class, 'team_id', 'id', 'id', 'user_id');
    }

    public function roles()
    : HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function syncRoles(array $roles)
    : void {
        $this->roles()->sync($roles);
    }

    public function createTeamRoles($teamId, $roles)
    : void {
        $existingRoles = Role::query()->where('team_id', $teamId)->get();
        foreach ($existingRoles as $existingRole)
        {
            if ( ! in_array($existingRole->id, $roles, true))
            {
                $existingRole->delete();
            }
        }

        foreach ($roles as $role)
        {
            $fetchExistingRole = Role::query()->where('id', $role)->where('team_id', $teamId)->first();
            if ( ! $fetchExistingRole)
            {
                $roleName = Role::query()->where('id', $role)->first()->name ?? '';
                if ($roleName)
                {
                    Role::query()->firstOrCreate(['name' => $roleName, 'team_id' => $teamId]);
                }
            }
        }
    }
}

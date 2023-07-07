<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\TeamTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, TeamTrait, LogsActivity, CausesActivity;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';
    public const ROLE_SUPER_ADMIN = 'super-admin';
    public const ROLE_OWNER = 'owner';
    public const ROLE_MEMBER = 'member';
    public const ROLE_EDITOR = 'editor';

    public const DEFAULT_ROLE = self::ROLE_USER;

    const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_USER,
        self::ROLE_SUPER_ADMIN,
        self::ROLE_OWNER,
        self::ROLE_MEMBER,
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'last_login_at',
        'last_login_ip_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];



    /**
     * Get the teams that the user belongs to.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')->withTimestamps();

    }

    // current team members

    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')->withTimestamps();
    }

    /**
     * Get the team that the user belongs to.
     */

    public function team() : BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public static function defaultPermissions()
    {
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',
        ];
    }

    // getRoleNames

    public function getRoleNames()
    {
        if(!empty($this->user_id))
        {
            return self::find($this->user_id)->roles();
        }

        return $this->roles();
    }

    public function teamRoleNames($teamId)
    {
        return Role::where('team_id', $teamId)->pluck( 'name', 'id')->toArray();
    }

    public function attachTeam($team): void
    {
        $this->teams()->attach($team);
    }

    public function detachTeam($team): void
    {
        $this->teams()->detach($team);
    }

    public function getCurrentTeam()
    {
        return $this->teams()->where('teams.id', $this->currentTeamId())->first();
    }

    public function getActivitylogOptions()
    : LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('users')
            ->setDescriptionForEvent(fn(string $eventName) => "This {$eventName} event has been done by {$this->name}");
    }

    // set default causer id

    public function setCauser()
    {
        $this->causer_id = $this->id;

    }
}

<?php

namespace App\Traits;

use App\Models\Team;

trait TeamTrait
{
    // check is model has team relation boot
    public static function bootTeamTrait(): void
    {

        static::creating(static function ($model) {
            if (isset($model->attributesToArray()['team_id']) && auth()->check()) {
                $model->team_id = auth()->user()->currentTeamId();
            }
        });

        static::created(static function ($model) {
            if (auth()->check()) {
                $model->teamUsers()->attach(auth()->user()->id, ['team_id' => auth()->user()->currentTeamId()]);
                $model->teamUsers()->update(['team_id' => auth()->user()->currentTeamId()], ['created_at' => now(), 'updated_at' => now()]);
            }
        });


    }

    public function currentTeamId()
    {
        return session('team_id');
    }

    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    // get the users that belong to the team

    public function teamUsers()
    {
        return $this->belongsToMany(__CLASS__, 'team_user')->withTimestamps();
    }

    public function scopeCurrentTeam($query)
    {
        return $query->where('team_id', $this->currentTeamId());
    }

    public function scopeWhereCurrentTeamIn($query, $column, $values)
    {
        return $query->whereIn($column, $values)->where('team_id', $this->currentTeamId());
    }


}

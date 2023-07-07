<?php

namespace App\Traits;

trait OfTeamTrait
{
    // filter by team scope

    public function scopeOfTeam($query, $teamId = null)
    {
        $teamId = $teamId ?? auth()->user()?->currentTeamId();

        return $query->where('team_id', $teamId);
    }
}

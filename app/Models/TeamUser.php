<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUser extends Pivot
{
    use HasFactory;

    protected $table = 'team_user';


    public function team()
    {
        return $this->belongsTo(Team::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

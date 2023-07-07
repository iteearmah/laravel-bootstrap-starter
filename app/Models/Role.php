<?php

namespace App\Models;

use App\Traits\OfTeamTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory, OfTeamTrait;

    protected $fillable = [
        'name',
        'guard_name',
        'team_id'
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }


}

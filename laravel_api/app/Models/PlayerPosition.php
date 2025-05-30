<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerPosition extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerPositionFactory> */
    use HasFactory;

    protected $table    = 'player_position';
    public    $timestamps = true;

    protected $fillable = [
        'player_id','position_id',
        'skill','assigned_from',
    ];
}

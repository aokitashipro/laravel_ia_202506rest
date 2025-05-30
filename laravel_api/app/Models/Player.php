<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;

        protected $fillable = ['team_id','name','number'];

    public function team()         // N:1
    {
        return $this->belongsTo(Team::class);
    }

    public function positions()    // N:M
    {
        return $this->belongsToMany(Position::class)
                    ->withPivot('skill', 'assigned_from');
    }
}

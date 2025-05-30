<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /** @use HasFactory<\Database\Factories\PositionFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function players()      // M:N
    {
        return $this->belongsToMany(Player::class)
        ->withPivot('skill', 'assined_from');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
     protected $fillable = [
        'name', 'price', 'category'
    ];
}

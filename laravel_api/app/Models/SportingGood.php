<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportingGood extends Model
{
    protected $fillable = [
    'name', 'category', 'brand', 'price', 'weight',
    'is_available', 'stock', 'release_date',
    'color', 'sku',
];
}

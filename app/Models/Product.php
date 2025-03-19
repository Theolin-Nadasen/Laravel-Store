<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'qty',
        'price',
        'image',
        'images'
    ];

    protected $casts = ['images' => 'array'];
}

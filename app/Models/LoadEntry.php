<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadEntry extends Model
{
    protected $fillable = [
        'date',
        'round',
        'description',
        'category',
        'quantity'
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'integer'
    ];
}

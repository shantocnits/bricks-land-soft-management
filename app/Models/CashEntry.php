<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashEntry extends Model
{
    protected $fillable = [
        'description',
        'source',
        'cash_in',
        'cash_out',
        'date',
        'time',
        'season',
        'is_system',
    ];

    protected $casts = [
        'date' => 'date',
        'cash_in' => 'float',
        'cash_out' => 'float',
        'is_system' => 'boolean',
    ];
}

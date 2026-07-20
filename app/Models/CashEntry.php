<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashEntry extends Model
{
    protected $fillable = [
        'description',
        'cash_in',
        'cash_out',
        'date',
        'time'
    ];

    protected $casts = [
        'date' => 'date',
        'cash_in' => 'float',
        'cash_out' => 'float',
    ];
}

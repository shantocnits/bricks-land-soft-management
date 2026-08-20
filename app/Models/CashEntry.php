<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class CashEntry extends Model
{
    use LogsActivity;
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

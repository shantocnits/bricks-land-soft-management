<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class LoadEntry extends Model
{
    use LogsActivity;
    protected $fillable = [
        'date',
        'season',
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

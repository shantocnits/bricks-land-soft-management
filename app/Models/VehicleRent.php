<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleRent extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'area',
        'fare',
    ];
}

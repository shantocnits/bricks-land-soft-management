<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Vehicle extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'status'];

    public function transactions()
    {
        return $this->hasMany(VehicleTransaction::class);
    }
}

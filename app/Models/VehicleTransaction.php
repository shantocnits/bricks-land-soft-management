<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'type',
        'date',
        'description',
        'khotian_name',
        'quantity',
        'rent',
        'received',
        'due_amount',
        'amount',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

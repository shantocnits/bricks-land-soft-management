<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Delivery;

class Challan extends Model
{
    protected $fillable = [
        'customer_type',
        'customer_phone',
        'customer_name',
        'customer_address',
        'challan_no',
        'date',
        'challan_type',
        'notes',
        'value',
        'total_value',
        'rent',
        'transport_rent',
        'discount',
        'grand_total',
        'cash',
        'due',
        'send_sms',
        'due_payment_date',
        'season'
    ];

    protected $casts = [
        'send_sms' => 'boolean',
        'date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(ChallanItem::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}

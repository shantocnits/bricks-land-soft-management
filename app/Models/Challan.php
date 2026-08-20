<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Delivery;
use App\Traits\LogsActivity;

class Challan extends Model
{
    use LogsActivity;
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
        'delivery_date',
        'season'
    ];

    protected $casts = [
        'send_sms' => 'boolean',
        'date' => 'date',
        'delivery_date' => 'date',
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

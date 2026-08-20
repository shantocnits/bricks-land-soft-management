<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Delivery extends Model
{
    use LogsActivity;
    protected $fillable = [
        'delivery_no', 'challan_id', 'challan_item_id', 'category_name',
        'quantity', 'delivery_date', 'next_delivery_date', 'notes',
        'driver_name', 'driver_phone', 'vehicle_no', 'vehicle_rent', 'sms_sent'
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'next_delivery_date' => 'date',
        'sms_sent' => 'boolean',
    ];

    public function challan()
    {
        return $this->belongsTo(Challan::class);
    }

    public function challanItem()
    {
        return $this->belongsTo(ChallanItem::class);
    }
}

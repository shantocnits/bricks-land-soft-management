<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ChallanItem extends Model
{
    use LogsActivity;
    protected $fillable = [
        'challan_id', 'category_name', 'rate', 'quantity', 'delivered_quantity', 'amount'
    ];

    public function challan()
    {
        return $this->belongsTo(Challan::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}

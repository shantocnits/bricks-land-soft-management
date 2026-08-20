<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class SmsRecharge extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'payment_method',
        'sender_phone',
        'trx_id',
        'amount',
        'sms_count',
        'status',
    ];
}

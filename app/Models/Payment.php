<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Payment extends Model
{
    use LogsActivity;
    protected $fillable = [
        'date',
        'ledger',
        'desc',
        'payment_type',
        'qty',
        'rate',
        'total',
        'advance',
        'deduction',
        'payment',
        'purchase_receive',
        'doc_url',
        'has_doc',
        'season'
    ];

    protected $casts = [
        'qty' => 'float',
        'rate' => 'float',
        'total' => 'float',
        'advance' => 'float',
        'deduction' => 'float',
        'payment' => 'float',
        'purchase_receive' => 'float',
        'has_doc' => 'boolean'
    ];
}

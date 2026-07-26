<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeunaTransaction extends Model
{
    protected $fillable = [
        'ledger_name', 'transaction_type', 'address', 'phone',
        'amount', 'start_date', 'transaction_date', 'due_date',
        'row1', 'row2', 'description', 'paid_amount',
    ];

    protected $casts = [
        'amount'           => 'float',
        'paid_amount'      => 'float',
        'start_date'       => 'date',
        'transaction_date' => 'date',
        'due_date'         => 'date',
    ];
}

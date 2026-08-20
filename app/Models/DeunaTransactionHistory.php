<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class DeunaTransactionHistory extends Model
{
    use LogsActivity;
    protected $fillable = [
        'deuna_transaction_id',
        'type',
        'transaction_date',
        'description',
        'given_amount',
        'received_amount',
        'paid_amount',
        'balance',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'given_amount'     => 'float',
        'received_amount'  => 'float',
        'paid_amount'      => 'float',
        'balance'          => 'float',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(DeunaTransaction::class, 'deuna_transaction_id');
    }
}

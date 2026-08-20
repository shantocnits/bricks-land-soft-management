<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class InvestmentTransaction extends Model
{
    use LogsActivity;
    protected $fillable = [
        'investor_id',
        'transaction_type',
        'amount',
        'date',
        'payment_method',
        'notes',
    ];

    public function investor(): BelongsTo
    {
        return $this->belongsTo(Investor::class);
    }
}

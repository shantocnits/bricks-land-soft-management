<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'total_invested',
        'total_repaid',
        'profit_percentage',
        'status',
        'notes',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(InvestmentTransaction::class);
    }
}

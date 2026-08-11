<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Schema;

class Ledger extends Model
{
    protected $fillable = ['serial', 'name', 'group', 'group_type', 'rate', 'divisor', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to only return active (non-soft-deleted) ledgers.
     */
    public function scopeActive($query)
    {
        if (Schema::hasColumn('ledgers', 'is_active')) {
            return $query->where(function ($q) {
                $q->where('is_active', true)->orWhereNull('is_active');
            });
        }
        return $query;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetIssue extends Model
{
    protected $fillable = [
        'asset_id',
        'issued_to',
        'location',
        'quantity',
        'issue_date',
        'return_date',
        'status',
        'notes',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}

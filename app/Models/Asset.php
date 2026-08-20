<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class Asset extends Model
{
    use LogsActivity;
    protected $fillable = [
        'category_id',
        'name',
        'code',
        'image',
        'vendor',
        'unit_price',
        'total_qty',
        'current_qty',
        'issued_qty',
        'damaged_qty',
        'lost_qty',
        'has_warranty',
        'warranty_expiry',
        'notes',
    ];

    protected $casts = [
        'has_warranty' => 'boolean',
        'warranty_expiry' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function issues(): HasMany
    {
        return $this->hasMany(AssetIssue::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(AssetHistory::class);
    }
}

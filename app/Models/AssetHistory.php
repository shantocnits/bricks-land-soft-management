<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class AssetHistory extends Model
{
    use LogsActivity;
    protected $fillable = [
        'asset_id',
        'action_type',
        'quantity',
        'good_qty',
        'damaged_qty',
        'lost_qty',
        'proof_image',
        'notes',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}

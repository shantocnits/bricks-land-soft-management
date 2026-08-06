<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnloadEntry extends Model
{
    protected $fillable = [
        'date',
        'season',
        'round'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function items()
    {
        return $this->hasMany(UnloadItem::class, 'unload_entry_id');
    }
}

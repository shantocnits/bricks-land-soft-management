<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnloadItem extends Model
{
    protected $fillable = [
        'unload_entry_id',
        'category_name',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    public function entry()
    {
        return $this->belongsTo(UnloadEntry::class, 'unload_entry_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class UnloadItem extends Model
{
    use LogsActivity;
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

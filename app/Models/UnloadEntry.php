<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class UnloadEntry extends Model
{
    use LogsActivity;
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

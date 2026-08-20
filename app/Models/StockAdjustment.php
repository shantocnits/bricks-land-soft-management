<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class StockAdjustment extends Model
{
    use LogsActivity;
    protected $fillable = [
        'date',
        'description',
        'category_name',
        'stock_plus',
        'stock_minus',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
        'stock_plus' => 'integer',
        'stock_minus' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadRound extends Model
{
    protected $fillable = ['name', 'sort_order'];

    protected static function booted()
    {
        static::creating(function ($round) {
            if (is_null($round->sort_order)) {
                $round->sort_order = static::max('sort_order') + 1;
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLimit extends Model
{
    protected $fillable = ['user_id', 'limit_type', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

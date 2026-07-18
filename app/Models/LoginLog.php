<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $fillable = ['type', 'user_name', 'device', 'ip', 'time'];

    protected $casts = [
        'time' => 'datetime',
    ];
}

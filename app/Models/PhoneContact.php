<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneContact extends Model
{
    protected $fillable = ['name', 'address', 'profession', 'phone', 'notes'];
}

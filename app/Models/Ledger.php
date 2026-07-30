<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $fillable = ['serial', 'name', 'group', 'rate', 'divisor'];
}

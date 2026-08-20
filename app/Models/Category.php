<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;
    protected $fillable = ['name', 'type', 'rate'];
}

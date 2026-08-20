<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class UserLimit extends Model
{
    use LogsActivity;
    protected $fillable = ['user_id', 'limit_type', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

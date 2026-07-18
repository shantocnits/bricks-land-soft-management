<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = ['field', 'description', 'user_name', 'status'];

    /**
     * Log an activity.
     */
    public static function log($field, $description, $status = false)
    {
        return self::create([
            'field' => $field,
            'description' => $description,
            'user_name' => Auth::user()->name ?? 'Demo',
            'status' => $status,
        ]);
    }
}

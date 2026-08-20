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

    /**
     * Get user limit for specific type. Returns float amount or null if not set.
     * Admin users bypass all limits (returns null).
     */
    public static function getLimit($userId, $limitType)
    {
        if (!$userId) return null;

        $user = User::find($userId);
        if ($user && $user->isAdmin()) {
            return null; // Admins are unrestricted
        }

        if ($limitType === 'discount_limit') {
            $types = ['discount_limit', 'max_discount_limit'];
        } elseif ($limitType === 'due_limit') {
            $types = ['due_limit'];
        } elseif ($limitType === 'delivery_limit') {
            $types = ['delivery_limit'];
        } else {
            $types = [$limitType];
        }

        $record = static::where('user_id', $userId)
            ->whereIn('limit_type', $types)
            ->first();

        return $record ? (float) $record->amount : null;
    }
}

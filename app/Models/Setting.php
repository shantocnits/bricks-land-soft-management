<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Setting extends Model
{
    use LogsActivity;
    protected $fillable = ['key', 'value'];

    /**
     * Helper to get a setting value.
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            return $setting->value;
        }
        return $default;
    }

    /**
     * Helper to set a setting value.
     */
    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}

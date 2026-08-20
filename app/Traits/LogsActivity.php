<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait LogsActivity
{
    /**
     * Boot the trait to listen for Eloquent model events.
     */
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logModelEvent('created', $model);
        });

        static::updated(function ($model) {
            static::logModelEvent('updated', $model);
        });

        static::deleted(function ($model) {
            static::logModelEvent('deleted', $model);
        });
    }

    /**
     * Helper to insert record into ActivityLog safely.
     */
    protected static function logModelEvent(string $event, $model)
    {
        // Don't log ActivityLog model actions to prevent infinite loops
        if ($model instanceof ActivityLog) {
            return;
        }

        try {
            $friendlyName = static::getModelFriendlyName($model);
            $userName = (Auth::check() && !empty(Auth::user()->name)) ? Auth::user()->name : 'অ্যাডমিন';
            $id = $model->id ?? $model->getKey() ?? 'N/A';

            if ($event === 'created') {
                $field = "{$friendlyName} তৈরি";
                $description = "নতুন রেকর্ড যোগ করা হয়েছে (আইডি: {$id})";
            } elseif ($event === 'updated') {
                $field = "{$friendlyName} আপডেট";
                $changes = [];
                $dirty = $model->getDirty();
                $ignore = ['updated_at', 'created_at', 'remember_token', 'password'];

                foreach ($dirty as $key => $newValue) {
                    if (in_array($key, $ignore)) {
                        continue;
                    }
                    $oldValue = $model->getOriginal($key);
                    $oldStr = is_array($oldValue) || is_object($oldValue) ? json_encode($oldValue, JSON_UNESCAPED_UNICODE) : (string)$oldValue;
                    $newStr = is_array($newValue) || is_object($newValue) ? json_encode($newValue, JSON_UNESCAPED_UNICODE) : (string)$newValue;
                    $changes[] = "{$key}: {$oldStr} -> {$newStr}";
                }

                $descDetails = !empty($changes) ? implode(' • ', $changes) : 'তথ্য পরিবর্তন করা হয়েছে';
                $description = "রেকর্ড আপডেট করা হয়েছে (আইডি: {$id}) • {$descDetails}";
            } elseif ($event === 'deleted') {
                $field = "{$friendlyName} ডিলিট";
                $description = "রেকর্ড মুছে ফেলা হয়েছে (আইডি: {$id})";
            } else {
                return;
            }

            ActivityLog::create([
                'field' => $field,
                'description' => $description,
                'user_name' => $userName,
                'status' => false,
            ]);
        } catch (\Throwable $e) {
            Log::error("LogsActivity Trait Error: " . $e->getMessage());
        }
    }

    /**
     * Get a user-friendly Bengali name for model class.
     */
    protected static function getModelFriendlyName($model): string
    {
        $className = class_basename($model);
        $map = [
            'Challan' => 'চালান',
            'ChallanItem' => 'চালান আইটেম',
            'Delivery' => 'ডেলিভারি',
            'Payment' => 'পেমেন্ট খাতা',
            'CashEntry' => 'ক্যাশ খাতা',
            'LoadEntry' => 'লোড খাতা',
            'LoadRound' => 'লোড রাউন্ড',
            'UnloadEntry' => 'আনলোড খাতা',
            'UnloadItem' => 'আনলোড আইটেম',
            'Category' => 'শ্রেণি ও রেট',
            'DeunaTransaction' => 'দেনা-পাওনা হিসাব',
            'DeunaTransactionHistory' => 'দেনা-পাওনা হিস্ট্রি',
            'Investor' => 'ইনভেস্টর',
            'InvestmentTransaction' => 'ইনভেস্টমেন্ট হিসাব',
            'Asset' => 'মালামাল প্রোডাক্ট',
            'AssetCategory' => 'মালামাল ক্যাটাগরি',
            'AssetIssue' => 'মালামাল ইস্যু',
            'AssetHistory' => 'মালামাল ইতিহাস',
            'StockAdjustment' => 'স্টক অ্যাডজাস্টমেন্ট',
            'Task' => 'টাস্ক ম্যানেজার',
            'Vehicle' => 'গাড়ি হিসাব',
            'VehicleRent' => 'গাড়ির ভাড়া',
            'VehicleTransaction' => 'গাড়ি লেনদেন',
            'Ledger' => 'লেজার গ্রাহক/হিসাব',
            'User' => 'ইউজার একাউন্ট',
            'UserLimit' => 'ইউজার লিমিট',
            'DocumentFile' => 'ডকুমেন্ট ফাইল',
            'DocumentFolder' => 'ডকুমেন্ট ফোল্ডার',
            'PhoneContact' => 'ফোন কন্টাক্ট',
            'SmsLog' => 'এসএমএস লগ',
            'SmsRecharge' => 'এসএমএস রিচার্জ',
            'Setting' => 'সিস্টেম সেটিংস',
        ];

        return $map[$className] ?? $className;
    }
}

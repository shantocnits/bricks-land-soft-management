<?php

namespace App\Traits;

use App\Models\UserLimit;

trait ValidatesUserLimits
{
    /**
     * Validate user limits for Discount, Due, and Delivery Qty.
     * Returns false if any limit is exceeded (dispatches toast error & session flash), true if allowed.
     */
    protected function validateUserLimits($discountAmount = 0, $dueAmount = 0, $deliveryQty = 0)
    {
        $userId = auth()->id();
        if (!$userId) return true;

        $user = auth()->user();
        if ($user && $user->isAdmin()) {
            return true; // Unrestricted / Bypass All Limits for Admin
        }

        // 1. Discount Limit Validation
        if ((float)$discountAmount > 0) {
            $discLimit = UserLimit::getLimit($userId, 'discount_limit');
            if ($discLimit !== null && (float)$discountAmount > (float)$discLimit) {
                $formattedLimit = number_format($discLimit, (float)$discLimit == (int)$discLimit ? 0 : 2);
                $msg = "আপনার সর্বোচ্চ ডিসকাউন্ট লিমিট ৳ {$formattedLimit}। এর বেশি ডিসকাউন্ট দিতে অ্যাডমিনের অনুমতি প্রয়োজন।";
                $this->dispatch('show-toast', message: $msg, type: 'danger');
                session()->flash('error', $msg);
                return false;
            }
        }

        // 2. Due Limit Validation
        if ((float)$dueAmount > 0) {
            $dueLimit = UserLimit::getLimit($userId, 'due_limit');
            if ($dueLimit !== null && (float)$dueAmount > (float)$dueLimit) {
                $formattedLimit = number_format($dueLimit, (float)$dueLimit == (int)$dueLimit ? 0 : 2);
                $msg = "আপনার সর্বোচ্চ বাকি দেওয়ার লিমিট ৳ {$formattedLimit}। অনুমোদিত সীমার বেশি বাকি দেওয়া সম্ভব নয়।";
                $this->dispatch('show-toast', message: $msg, type: 'danger');
                session()->flash('error', $msg);
                return false;
            }
        }

        // 3. Delivery Limit Validation
        if ((float)$deliveryQty > 0) {
            $delivLimit = UserLimit::getLimit($userId, 'delivery_limit');
            if ($delivLimit !== null && (float)$deliveryQty > (float)$delivLimit) {
                $formattedLimit = number_format($delivLimit);
                $msg = "আপনার একবারে সর্বোচ্চ ডেলিভারি লিমিট {$formattedLimit} পিস। অতিরিক্ত ডেলিভারির জন্য অ্যাডমিনের অনুমতি প্রয়োজন।";
                $this->dispatch('show-toast', message: $msg, type: 'danger');
                session()->flash('error', $msg);
                return false;
            }
        }

        return true;
    }
}

<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserPermission extends Component
{
    public $selectedUserId = null;
    public $selectedPermissions = [];

    // The list of all sidebar menus/permissions with their Bengali names
    public $menuOptions = [
        'dashboard' => 'ড্যাশবোর্ড',
        'challan' => 'চালান',
        'payment' => 'পেমেন্ট খাতা',
        'delivery' => 'ডেলিভারি',
        'due_ledger' => 'বাকি খাতা',
        'cash_ledger' => 'ক্যাশ খাতা',
        'load_ledger' => 'লোড খাতা',
        'unload' => 'আনলোড',
        'brick_ledger' => 'স্টক খাতা',
        'ledger' => 'খতিয়ান',
        'customer' => 'কাস্টমার',
        'sales_report' => 'বিক্রি রিপোর্ট',
        'inventory' => 'ইনভেন্টরি',
        'documents' => 'ডকুমেন্টস',
        'raw_material' => 'কাচামাল স্টক',
        'staff' => 'স্টাফ ম্যানেজার',
        'vehicle_acc' => 'গাড়ির হিসাব',
        'vehicle_rent' => 'গাড়ি ভাড়া',
        'debts' => 'দেনা-পাওনা',
        'accounts' => 'অ্যাকাউন্টস',
        'production' => 'প্রোডাকশন',
        'phone' => 'ফোন নাম্বার',
    ];

    public function updatedSelectedUserId($value)
    {
        if ($value) {
            $user = User::find($value);
            if ($user) {
                // Get directly assigned permissions
                $this->selectedPermissions = $user->permissions->pluck('name')->toArray();
            } else {
                $this->selectedPermissions = [];
            }
        } else {
            $this->selectedPermissions = [];
        }
    }

    public function save()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
        ], [
            'selectedUserId.required' => 'প্রথমে একজন ইউজার নির্বাচন করুন।',
        ]);

        $user = User::find($this->selectedUserId);
        if ($user) {
            // Admin users always have all permissions, but sync anyway
            $user->syncPermissions($this->selectedPermissions);
            session()->flash('message', 'ইউজারের পারমিশন সফলভাবে সংরক্ষণ করা হয়েছে।');
        }
    }

    public function render()
    {
        return view('livewire.settings.user-permission', [
            'users' => User::orderBy('name', 'asc')->get()
        ]);
    }
}

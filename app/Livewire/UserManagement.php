<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagement extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
    public $selectedPermissions = [];

    // The list of all sidebar menus that can be configured
    public $menuOptions = [
        'dashboard' => 'ড্যাশবোর্ড',
        'challan' => 'চালান',
        'payment' => 'পেমেন্ট খাতা',
        'delivery' => 'ডেলিভারি',
        'due_ledger' => 'বাকি খাতা',
        'cash_ledger' => 'ক্যাশ খাতা',
        'load_ledger' => 'লোড খাতা',
        'unload' => 'আনলোড',
        'brick_ledger' => 'ইট খাতা',
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

    protected $rules = [
        'name' => 'required|string|min:3|unique:users,name',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|in:admin,user',
    ];

    protected $messages = [
        'name.required' => 'ইউজারনেম আবশ্যক।',
        'name.min' => 'ইউজারনেম কমপক্ষে ৩ অক্ষরের হতে হবে।',
        'name.unique' => 'এই ইউজারনেমটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
        'email.required' => 'ইমেইল আবশ্যক।',
        'email.email' => 'একটি সঠিক ইমেইল ঠিকানা দিন।',
        'email.unique' => 'এই ইমেইলটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
        'password.required' => 'পাসওয়ার্ড আবশ্যক।',
        'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
    ];

    /**
     * Create a new user.
     */
    public function createUser()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'permissions' => $this->role === 'admin' ? null : $this->selectedPermissions,
        ]);

        $this->reset(['name', 'email', 'password', 'role', 'selectedPermissions']);
        session()->flash('message', 'নতুন ব্যবহারকারী সফলভাবে তৈরি হয়েছে!');
    }

    /**
     * Instantly create a demo user with default access.
     */
    public function createDemoUser()
    {
        $randomSuffix = rand(100, 999);
        $demoUsername = 'user_' . $randomSuffix;
        $demoEmail = 'user_' . $randomSuffix . '@example.com';

        // Give default demo access to dashboard, challan, and delivery
        $defaultDemoPermissions = ['dashboard', 'challan', 'delivery'];

        User::create([
            'name' => $demoUsername,
            'email' => $demoEmail,
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'permissions' => $defaultDemoPermissions,
        ]);

        session()->flash('message', "ডেমো ইউজার তৈরি হয়েছে! ইউজারনেম: {$demoUsername}, পাসওয়ার্ড: 12345678");
    }

    /**
     * Toggle the Admin status of a user.
     */
    public function toggleAdmin($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            $user->role = $user->role === 'admin' ? 'user' : 'admin';
            // Clear permissions if upgraded to admin
            if ($user->role === 'admin') {
                $user->permissions = null;
            } else {
                $user->permissions = ['dashboard'];
            }
            $user->save();
            session()->flash('message', "{$user->name}-এর রোল সফলভাবে পরিবর্তন করা হয়েছে!");
        }
    }

    /**
     * Update access permission for a user.
     */
    public function togglePermission($userId, $menuKey)
    {
        $user = User::find($userId);
        if ($user && $user->role !== 'admin') {
            $permissions = is_array($user->permissions) ? $user->permissions : [];
            
            if (in_array($menuKey, $permissions)) {
                $permissions = array_values(array_diff($permissions, [$menuKey]));
            } else {
                $permissions[] = $menuKey;
            }
            
            $user->permissions = $permissions;
            $user->save();
        }
    }

    /**
     * Delete a user.
     */
    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            $user->delete();
            session()->flash('message', 'ব্যবহারকারী মুছে ফেলা হয়েছে!');
        }
    }

    public function render()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('livewire.user-management', [
            'users' => $users
        ])->layout('layouts.app');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UserManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
    public $selectedPermissions = [];
    public $profile_photo;
    public $current_profile_photo;

    public $editingUserId = null;
    public $editingUserRole = null;
    public $search = '';

    public $menuOptions = [
        'dashboard'    => 'ড্যাশবোর্ড',
        'challan'      => 'চালান',
        'payment'      => 'পেমেন্ট খাতা',
        'ledger'       => 'খতিয়ান',
        'delivery'     => 'ডেলিভারি',
        'due_ledger'   => 'বাকি খাতা',
        'cash_ledger'  => 'ক্যাশ খাতা',
        'load_ledger'  => 'লোড খাতা',
        'unload'       => 'আনলোড',
        'brick_ledger' => 'স্টক খাতা',
        'customer'     => 'কাস্টমার',
        'sales_report' => 'বিক্রি রিপোর্ট',
        'investment'   => 'ইনভেস্টমেন্ট',
        'documents'    => 'ডকুমেন্টস',
        'raw_material' => 'মালামাল স্টক',
        'task_manager' => 'টাস্ক ম্যানেজার',
        'vehicle_acc'  => 'গাড়ির হিসাব',
        'vehicle_rent' => 'গাড়ি ভাড়া',
        'debts'        => 'দেনা-পাওনা',
        'phone'        => 'ফোন নাম্বার',
        'sms'          => 'এসএমএস',
    ];

    public function mount()
    {
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('user', 'web');

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'admin' && !$currentUser->hasRole('admin')) {
            $currentUser->assignRole('admin');
        }

        if (!$currentUser || !$currentUser->isAdmin()) {
            return $this->redirectRoute('dashboard');
        }
    }

    public function createUser()
    {
        $this->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|max:255|unique:users,email',
            'password'      => 'required|string|min:6',
            'role'          => 'required|in:admin,user',
            'profile_photo' => 'nullable|image|max:2048',
        ], [
            'name.required'     => 'নাম আবশ্যক।',
            'email.required'    => 'ইমেইল/ইউজারনেম আবশ্যক।',
            'email.unique'      => 'এই ইমেইল/ইউজারনেমটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
            'password.required' => 'পাসওয়ার্ড আবশ্যক।',
            'password.min'      => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
        ]);

        $photoPath = null;
        if ($this->profile_photo) {
            $photoPath = $this->profile_photo->store('profile-photos', 'public');
        }

        $user = User::create([
            'name'          => $this->name,
            'email'         => $this->email,
            'password'      => Hash::make($this->password),
            'role'          => $this->role,
            'profile_photo' => $photoPath,
        ]);

        Role::findOrCreate($this->role, 'web');
        $user->assignRole($this->role);

        if ($this->role !== 'admin' && !empty($this->selectedPermissions)) {
            foreach ($this->selectedPermissions as $perm) {
                Permission::findOrCreate($perm, 'web');
            }
            $user->syncPermissions($this->selectedPermissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('message', 'নতুন ব্যবহারকারী সফলভাবে তৈরি করা হয়েছে!');
        $this->reset(['name', 'email', 'password', 'role', 'selectedPermissions', 'profile_photo']);
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.user-management', [
            'users' => $users,
        ])->layout('layouts.app');
    }
}

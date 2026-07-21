<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagement extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
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

    public $editingId = null;
    public $showModal = false;
    public $search = '';

    protected function rules()
    {
        $uniqueRule = $this->editingId
            ? 'required|email|unique:users,email,' . $this->editingId
            : 'required|email|unique:users,email';

        $passwordRule = $this->editingId
            ? 'nullable|string|min:8'
            : 'required|string|min:8';

        return [
            'name'     => 'required|string|max:255',
            'email'    => $uniqueRule,
            'password' => $passwordRule,
            'role'     => 'required|string',
        ];
    }

    protected $messages = [
        'name.required'     => 'নাম আবশ্যক।',
        'email.required'    => 'ইমেইল/ইউজারনেম আবশ্যক।',
        'email.email'       => 'একটি সঠিক ইমেইল ঠিকানা দিন।',
        'email.unique'      => 'এই ইমেইলটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
        'password.required' => 'পাসওয়ার্ড আবশ্যক।',
        'password.min'      => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।',
        'role.required'     => 'ইউজার টাইপ আবশ্যক।',
    ];

    public function openAddModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function save()
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            session()->flash('error', 'ডেমো মোডে ব্যবহারকারী পরিবর্তন করা সম্ভব নয়।');
            $this->showModal = false;
            return;
        }

        $this->validate();

        // Make sure permissions exist in DB before syncing
        foreach ($this->selectedPermissions as $perm) {
            Permission::findOrCreate($perm);
        }

        if ($this->editingId) {
            $user = User::find($this->editingId);
            if ($user) {
                $data = [
                    'name'  => $this->name,
                    'email' => $this->email,
                    'role'  => $this->role,
                ];
                if ($this->password) {
                    $data['password'] = Hash::make($this->password);
                }
                $user->update($data);

                // Sync Spatie role
                if (class_exists(Role::class)) {
                    Role::findOrCreate($this->role);
                    $user->syncRoles([$this->role]);
                }

                // Sync permissions
                if ($this->role !== 'admin') {
                    $user->syncPermissions($this->selectedPermissions);
                } else {
                    $user->syncPermissions([]);
                }

                session()->flash('message', 'ইউজার সফলভাবে আপডেট করা হয়েছে।');
            }
        } else {
            $user = User::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
                'role'     => $this->role,
            ]);

            // Assign Spatie Role
            if (class_exists(Role::class)) {
                Role::findOrCreate($this->role);
                $user->assignRole($this->role);
            }

            // Sync permissions
            if ($this->role !== 'admin') {
                $user->syncPermissions($this->selectedPermissions);
            }

            session()->flash('message', 'নতুন ইউজার সফলভাবে তৈরি করা হয়েছে।');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            session()->flash('error', 'ডেমো মোডে ব্যবহারকারী সংশোধন করা সম্ভব নয়।');
            return;
        }

        $user = User::find($id);
        if ($user) {
            $this->editingId = $user->id;
            $this->name      = $user->name;
            $this->email     = $user->email;
            $this->role      = $user->role ?: 'user';
            $this->password  = '';
            $this->selectedPermissions = $user->permissions->pluck('name')->toArray();
            $this->showModal = true;
        }
    }

    public function delete($id)
    {
        // Block action if logged in as Demo
        if (auth()->user()->hasRole('demo')) {
            session()->flash('error', 'ডেমো মোডে ব্যবহারকারী ডিলিট করা সম্ভব নয়।');
            return;
        }

        if ($id == auth()->id()) {
            session()->flash('error', 'আপনি বর্তমানে লগইন থাকা ইউজারটি ডিলিট করতে পারবেন না।');
            return;
        }

        User::destroy($id);
        session()->flash('message', 'ইউজার সফলভাবে মুছে ফেলা হয়েছে।');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'editingId', 'showModal', 'selectedPermissions']);
        $this->role = 'user';
    }

    public function render()
    {
        $query = User::orderBy('id', 'desc');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.settings.user-management', [
            'users' => $query->get(),
        ]);
    }
}

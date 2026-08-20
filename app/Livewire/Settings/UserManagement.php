<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UserManagement extends Component
{
    use WithFileUploads;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
    public $selectedPermissions = [];
    public $profile_photo;
    public $current_profile_photo;
    public $current_profile_photo_url;

    public $editingId = null;
    public $editingUserRole = null;

    // Re-render when any user updates their profile photo
    protected $listeners = ['profile-updated' => '$refresh'];
    public $showModal = false;
    public $search = '';

    // The list of all sidebar menus/permissions with their Bengali names
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

    protected function rules()
    {
        $uniqueRule = $this->editingId
            ? 'required|string|max:255|unique:users,email,' . $this->editingId
            : 'required|string|max:255|unique:users,email';

        $passwordRule = $this->editingId
            ? 'nullable|string|min:6'
            : 'required|string|min:6';

        return [
            'name'          => 'required|string|max:255',
            'email'         => $uniqueRule,
            'password'      => $passwordRule,
            'role'          => 'required|string|in:admin,user',
            'profile_photo' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'name.required'       => 'নাম আবশ্যক।',
        'email.required'      => 'ইমেইল/ইউজারনেম আবশ্যক।',
        'email.unique'        => 'এই ইমেইল/ইউজারনেমটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
        'password.required'   => 'পাসওয়ার্ড আবশ্যক।',
        'password.min'        => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
        'role.required'       => 'ইউজার টাইপ আবশ্যক।',
        'profile_photo.image' => 'ছবিটি সঠিক ইমেজ ফরম্যাটে হতে হবে।',
        'profile_photo.max'   => 'ছবিটির সাইজ সর্বোচ্চ ২ মেগাবাইট হতে পারবে।',
    ];

    public function openAddModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function save()
    {
        // If editing an existing admin user, enforce role remains admin to prevent demotion
        if ($this->editingId && ($this->editingUserRole === 'admin' || $this->role === 'admin')) {
            $this->role = 'admin';
            $this->selectedPermissions = [];
        }

        $this->validate();

        // Make sure Spatie permissions exist in DB before syncing
        if ($this->role !== 'admin' && !empty($this->selectedPermissions)) {
            foreach ($this->selectedPermissions as $perm) {
                Permission::findOrCreate($perm, 'web');
            }
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
                if ($this->profile_photo) {
                    if ($user->profile_photo) {
                        Storage::disk('public')->delete(ltrim($user->profile_photo, '/'));
                    }
                    $data['profile_photo'] = $this->profile_photo->store('profile-photos', 'public');
                }
                $user->update($data);

                // Sync Spatie role
                if (class_exists(Role::class)) {
                    Role::findOrCreate($this->role, 'web');
                    $user->syncRoles([$this->role]);
                }

                // Sync permissions
                if ($this->role !== 'admin') {
                    $user->syncPermissions($this->selectedPermissions);
                } else {
                    $user->syncPermissions([]);
                }

                $this->dispatch('show-toast', message: 'ইউজার সফলভাবে আপডেট করা হয়েছে।', type: 'success');
            }
        } else {
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

            // Assign Spatie Role
            if (class_exists(Role::class)) {
                Role::findOrCreate($this->role, 'web');
                $user->assignRole($this->role);
            }

            // Sync permissions
            if ($this->role !== 'admin' && !empty($this->selectedPermissions)) {
                $user->syncPermissions($this->selectedPermissions);
            }

            $this->dispatch('show-toast', message: 'নতুন ইউজার সফলভাবে তৈরি করা হয়েছে।', type: 'success');
        }

        // Reset permission cache so changes reflect instantly
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->showModal = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $user = User::find($id);
        if ($user) {
            $this->editingId = $user->id;
            $this->name      = $user->name;
            $this->email     = $user->email;
            $this->role      = $user->role ?: 'user';
            $this->editingUserRole = $user->role ?: 'user';
            $this->password  = '';
            $this->current_profile_photo = $user->profile_photo;
            $this->current_profile_photo_url = $user->profile_photo_url;
            $this->profile_photo = null;
            $this->selectedPermissions = $user->permissions->pluck('name')->toArray();
            $this->showModal = true;
        }
    }

    public $confirmDeleteId = null;

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        if ($this->confirmDeleteId) {
            $this->delete($this->confirmDeleteId);
            $this->confirmDeleteId = null;
        }
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            $this->dispatch('show-toast', message: 'আপনি বর্তমানে লগইন থাকা ইউজারটি ডিলিট করতে পারবেন না।', type: 'danger');
            return;
        }

        $user = User::find($id);
        if ($user) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete(ltrim($user->profile_photo, '/'));
            }
            $user->delete();
        }

        // Reset permission cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->dispatch('show-toast', message: 'ইউজার সফলভাবে মুছে ফেলা হয়েছে।', type: 'success');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'editingId', 'editingUserRole', 'showModal', 'selectedPermissions', 'profile_photo', 'current_profile_photo', 'current_profile_photo_url']);
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

<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';

    public $editingId = null;
    public $showCreateForm = false;

    protected function rules()
    {
        $uniqueRule = $this->editingId
            ? 'required|email|unique:users,email,' . $this->editingId
            : 'required|email|unique:users,email';

        $passwordRule = $this->editingId
            ? 'nullable|string|min:6'
            : 'required|string|min:6';

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
        'password.min'      => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
        'role.required'     => 'ইউজার টাইপ আবশ্যক।',
    ];

    public function toggleCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = !$this->showCreateForm;
    }

    public function save()
    {
        $this->validate();

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

            session()->flash('message', 'নতুন ইউজার সফলভাবে তৈরি করা হয়েছে।');
        }

        $this->resetForm();
    }

    /**
     * Edit opens a modal overlay (editingId is set; form panel is not shown).
     */
    public function edit($id)
    {
        $user = User::find($id);
        if ($user) {
            $this->editingId = $user->id;
            $this->name      = $user->name;
            $this->email     = $user->email;
            $this->role      = $user->role ?: 'user';
            $this->password  = '';
            // Do NOT set showCreateForm — edit uses the modal
            $this->showCreateForm = false;
        }
    }

    public function delete($id)
    {
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
        $this->reset(['name', 'email', 'password', 'role', 'editingId', 'showCreateForm']);
        $this->role = 'user';
    }

    public function render()
    {
        return view('livewire.settings.user-management', [
            'users' => User::orderBy('id', 'desc')->get(),
        ]);
    }
}

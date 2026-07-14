<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManagement extends Component
{
    use WithPagination;

    // Create user properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
    public $selectedPermissions = [];

    // Edit user properties
    public $editingUserId = null;
    public $editName = '';
    public $editEmail = '';
    public $editPassword = '';
    public $editRole = 'user';
    public $editSelectedPermissions = [];

    /**
     * Authorize user access.
     */
    public function mount()
    {
        // Ensure Spatie roles exist in database
        Role::findOrCreate('admin');
        Role::findOrCreate('user');
        Role::findOrCreate('demo');

        // Automatically assign Spatie admin role to database admins
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'admin' && !$currentUser->hasRole('admin')) {
            $currentUser->assignRole('admin');
        }
        if ($currentUser && $currentUser->role === 'demo' && !$currentUser->hasRole('demo')) {
            $currentUser->assignRole('demo');
        }

        if (!$currentUser || (!$currentUser->hasRole('admin') && !$currentUser->hasRole('demo'))) {
            return $this->redirectRoute('dashboard');
        }
    }

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
        'name' => 'required|string|max:255',
        'email' => 'required|string|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|in:admin,user,demo',
    ];

    protected $messages = [
        'name.required' => 'নাম আবশ্যক।',
        'email.required' => 'ইউজারনেম/ইমেইল আবশ্যক।',
        'email.unique' => 'এই ইউজারনেম/ইমেইলটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
        'password.required' => 'পাসওয়ার্ড আবশ্যক।',
        'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
    ];

    /**
     * Create a new user.
     */
    public function createUser()
    {
        // Block action if logged in as Demo
        if (Auth::user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে কোনো ব্যবহারকারী তৈরি করা সম্ভব নয়।');
            return;
        }

        $this->validate();

        // Ensure Spatie roles exist
        Role::findOrCreate('admin');
        Role::findOrCreate('user');
        Role::findOrCreate('demo');

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $user->assignRole($this->role);
        if ($this->role === 'user' || $this->role === 'demo') {
            foreach ($this->selectedPermissions as $perm) {
                Permission::findOrCreate($perm);
            }
            $user->syncPermissions($this->selectedPermissions);
        }

        $this->reset(['name', 'email', 'password', 'role', 'selectedPermissions']);
        session()->flash('message', 'নতুন ব্যবহারকারী সফলভাবে তৈরি হয়েছে!');
    }

    /**
     * Load user details into edit mode.
     */
    public function editUser($userId)
    {
        // Block action if logged in as Demo
        if (Auth::user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে তথ্য সংশোধন করা সম্ভব নয়।');
            return;
        }

        $user = User::find($userId);
        if ($user && $user->email !== 'admin@gmail.com') {
            // Ensure roles exist
            Role::findOrCreate('admin');
            Role::findOrCreate('user');
            Role::findOrCreate('demo');

            $this->editingUserId = $user->id;
            $this->editName = $user->name;
            $this->editEmail = $user->email;
            $this->editRole = $user->role;
            $this->editSelectedPermissions = $user->permissions()->pluck('name')->toArray();
            $this->editPassword = '';
        }
    }

    /**
     * Update an existing user's details.
     */
    public function updateUser()
    {
        // Block action if logged in as Demo
        if (Auth::user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে তথ্য সংশোধন করা সম্ভব নয়।');
            return;
        }

        $this->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => 'required|string|unique:users,email,' . $this->editingUserId,
            'editPassword' => 'nullable|string|min:6',
            'editRole' => 'required|in:admin,user,demo',
        ], [
            'editName.required' => 'নাম আবশ্যক।',
            'editEmail.required' => 'ইউজারনেম/ইমেইল আবশ্যক।',
            'editEmail.unique' => 'এই ইউজারনেম/ইমেইলটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
            'editPassword.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
        ]);

        $user = User::find($this->editingUserId);
        if ($user && $user->email !== 'admin@gmail.com') {
            // Ensure roles exist
            Role::findOrCreate('admin');
            Role::findOrCreate('user');
            Role::findOrCreate('demo');

            $user->name = $this->editName;
            $user->email = $this->editEmail;
            $user->role = $this->editRole;
            
            if (!empty($this->editPassword)) {
                $user->password = Hash::make($this->editPassword);
            }

            $user->save();

            $user->syncRoles([$this->editRole]);
            if ($this->editRole === 'admin') {
                $user->syncPermissions([]);
            } else {
                foreach ($this->editSelectedPermissions as $perm) {
                    Permission::findOrCreate($perm);
                }
                $user->syncPermissions($this->editSelectedPermissions);
            }

            $this->cancelEdit();
            session()->flash('message', 'ব্যবহারকারীর তথ্য সফলভাবে আপডেট হয়েছে!');
        }
    }

    /**
     * Cancel the editing state.
     */
    public function cancelEdit()
    {
        $this->reset(['editingUserId', 'editName', 'editEmail', 'editPassword', 'editRole', 'editSelectedPermissions']);
    }

    /**
     * Log in as another user (Impersonation).
     */
    public function loginAsUser($userId)
    {
        // Block action if logged in as Demo
        if (Auth::user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে অন্য ব্যবহারকারী হিসেবে লগইন করা সম্ভব নয়।');
            return;
        }

        $user = User::find($userId);
        if ($user) {
            Auth::login($user);
            return redirect()->route('dashboard');
        }
    }

    /**
     * Toggle the Admin status of a user.
     */
    public function toggleAdmin($userId)
    {
        // Block action if logged in as Demo
        if (Auth::user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে রোল পরিবর্তন করা সম্ভব নয়।');
            return;
        }

        $user = User::find($userId);
        if ($user && $user->id !== Auth::id() && $user->email !== 'admin@gmail.com') {
            // Ensure Spatie roles exist
            Role::findOrCreate('admin');
            Role::findOrCreate('user');
            Role::findOrCreate('demo');

            if ($user->hasRole('admin')) {
                $user->syncRoles(['user']);
                $user->syncPermissions([]);
                $user->role = 'user';
            } else {
                $user->syncRoles(['admin']);
                $user->syncPermissions([]);
                $user->role = 'admin';
            }
            $user->save();
            session()->flash('message', "{$user->email}-এর রোল সফলভাবে পরিবর্তন করা হয়েছে!");
        }
    }

    /**
     * Update access permission for a user.
     */
    public function togglePermission($userId, $menuKey)
    {
        // Block action if logged in as Demo
        if (Auth::user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে পারমিশন পরিবর্তন করা সম্ভব নয়।');
            return;
        }

        $user = User::find($userId);
        if ($user && $user->email !== 'admin@gmail.com') {
            // Ensure roles exist
            Role::findOrCreate('admin');
            Role::findOrCreate('user');
            Role::findOrCreate('demo');

            if ($user->hasRole('admin')) {
                return; // Admin has total menu access
            }

            Permission::findOrCreate($menuKey);
            if ($user->hasPermissionTo($menuKey)) {
                $user->revokePermissionTo($menuKey);
            } else {
                $user->givePermissionTo($menuKey);
            }
        }
    }

    /**
     * Delete a user.
     */
    public function deleteUser($userId)
    {
        // Block action if logged in as Demo
        if (Auth::user()->hasRole('demo')) {
            session()->flash('message', 'ডেমো মোডে ব্যবহারকারী ডিলিট করা সম্ভব নয়।');
            return;
        }

        $user = User::find($userId);
        if ($user && $user->id !== Auth::id() && $user->email !== 'admin@gmail.com') {
            $user->delete();
            session()->flash('message', 'ব্যবহারকারী মুছে ফেলা হয়েছে!');
        }
    }

    public function render()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(5);

        return view('livewire.user-management', [
            'users' => $users
        ])->layout('layouts.app');
    }
}

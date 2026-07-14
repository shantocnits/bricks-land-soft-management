<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordChange extends Component
{
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed|different:current_password',
    ];

    protected $messages = [
        'current_password.required' => 'পুরাতন পাসওয়ার্ড আবশ্যক।',
        'new_password.required' => 'নতুন পাসওয়ার্ড আবশ্যক।',
        'new_password.min' => 'নতুন পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।',
        'new_password.confirmed' => 'নতুন পাসওয়ার্ড এবং কনফার্ম পাসওয়ার্ড মেলেনি।',
        'new_password.different' => 'নতুন পাসওয়ার্ড পুরাতন পাসওয়ার্ডের চেয়ে আলাদা হতে হবে。',
    ];

    public function changePassword()
    {
        $this->validate();

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'পুরাতন পাসওয়ার্ডটি সঠিক নয়।');
            return;
        }

        // Update password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // Auto-logout the user
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        session()->flash('status', 'পাসওয়ার্ড সফলভাবে পরিবর্তিত হয়েছে। নতুন পাসওয়ার্ড দিয়ে পুনরায় লগইন করুন।');

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.settings.password-change');
    }
}

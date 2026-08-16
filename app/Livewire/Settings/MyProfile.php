<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyProfile extends Component
{
    use WithFileUploads;

    public $user_name;
    public $user_email;
    public $user_photo;
    public $current_photo_path;

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->user_name = $user->name;
            $this->user_email = $user->email;
            $this->current_photo_path = $user->profile_photo;
        }
    }

    public function saveUserProfile()
    {
        $user = Auth::user();
        if (!$user) return;

        $this->validate([
            'user_name'  => 'required|string|max:255',
            'user_email' => 'required|string|max:255|unique:users,email,' . $user->id,
            'user_photo' => 'nullable|image|max:2048',
        ], [
            'user_name.required'  => 'নাম আবশ্যক।',
            'user_email.required' => 'ইউজারনেম/ইমেইল আবশ্যক।',
            'user_email.unique'   => 'এই ইউজারনেম/ইমেইলটি ইতিমধ্যে ব্যবহৃত হয়েছে।',
            'user_photo.image'    => 'ফাইলটি অবশ্যই ছবি ফরম্যাটে হতে হবে।',
            'user_photo.max'      => 'ছবিটির সাইজ ২ মেগাবাইটের বেশি হতে পারবে না।',
        ]);

        $user->name = $this->user_name;
        $user->email = $this->user_email;

        if ($this->user_photo) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete(ltrim($user->profile_photo, '/'));
            }
            $path = $this->user_photo->store('profile-photos', 'public');
            $user->profile_photo = $path;
            $this->current_photo_path = $path;
        }

        $user->save();
        $this->user_photo = null;

        // Dispatch Livewire event -> UserManagement table $refresh
        $this->dispatch('profile-updated');

        // Dispatch browser JS event -> topbar avatar src update
        $newPhotoUrl = $user->profile_photo_url;
        $this->dispatch('profile-photo-changed', url: $newPhotoUrl ?? '');

        session()->flash('profile_saved', 'আপনার প্রোফাইল তথ্য সফলভাবে আপডেট করা হয়েছে।');

        $this->dispatch('show-toast', message: 'আপনার প্রোফাইল তথ্য সফলভাবে আপডেট করা হয়েছে।', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.my-profile');
    }
}

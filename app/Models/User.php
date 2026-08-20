<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (!$this->profile_photo) {
            return null;
        }
        $path = ltrim($this->profile_photo, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        return asset('storage/' . $path);
    }

    public function isAdmin(): bool
    {
        return ($this->role === 'admin' || strtolower($this->role ?? '') === 'admin' || strtolower($this->name ?? '') === 'admin' || $this->hasRole('admin'));
    }

    public function hasPermissionTo($permission, $guardName = null): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        return parent::hasPermissionTo($permission, $guardName);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

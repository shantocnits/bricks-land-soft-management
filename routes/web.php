<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\UserManagement;
use App\Livewire\Settings;

// Redirect / to /login
Route::redirect('/', '/login');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/user-management', UserManagement::class)->name('user-management');
    Route::get('/settings', Settings::class)->name('settings');

    // Challan Routes
    Route::get('/challan/today',   \App\Livewire\Challan\TodayChallan::class)->name('challan.today');
    Route::get('/challan/pending', \App\Livewire\Challan\PendingChallan::class)->name('challan.pending');
    Route::get('/challan/all',     \App\Livewire\Challan\AllChallan::class)->name('challan.all');
    
    // Profile Routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
});

require __DIR__.'/auth.php';

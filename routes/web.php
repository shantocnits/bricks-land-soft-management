<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\UserManagement;
use App\Livewire\Settings;
use App\Livewire\Tutorial;
use App\Livewire\FeePayment;
use App\Livewire\PaymentKhata;

// Redirect / to /login
Route::redirect('/', '/login');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/user-management', UserManagement::class)->name('user-management');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/update-history', \App\Livewire\UpdateHistory::class)->name('update-history');
    Route::get('/login-history', \App\Livewire\LoginHistory::class)->name('login-history');
    Route::get('/tutorial', Tutorial::class)->name('tutorial');
    Route::get('/fee-payment', FeePayment::class)->name('fee-payment');
    Route::get('/payment-khata', PaymentKhata::class)->name('payment-khata');

    // Challan Routes
    Route::get('/challan/today',   \App\Livewire\Challan\TodayChallan::class)->name('challan.today');
    Route::get('/challan/pending', \App\Livewire\Challan\PendingChallan::class)->name('challan.pending');
    Route::get('/challan/all',     \App\Livewire\Challan\AllChallan::class)->name('challan.all');
    Route::get('/challan/customer-profile/{phone}', \App\Livewire\Challan\CustomerProfile::class)->name('challan.customer-profile');
    
    // Delivery Routes
    Route::get('/delivery/today',   \App\Livewire\Delivery\TodayDelivery::class)->name('delivery.today');
    Route::get('/delivery/pending', \App\Livewire\Delivery\PendingDelivery::class)->name('delivery.pending');
    Route::get('/delivery/all',     \App\Livewire\Delivery\AllDelivery::class)->name('delivery.all');
    
    // Due Ledger (Baki Khata) Routes
    Route::get('/due-ledger/today',       \App\Livewire\DueLedger\TodayCollection::class)->name('due-ledger.today');
    Route::get('/due-ledger/due-today',   \App\Livewire\DueLedger\DueToday::class)->name('due-ledger.due-today');
    Route::get('/due-ledger/all-due',     \App\Livewire\DueLedger\AllDueList::class)->name('due-ledger.all-due');

    // General & Support Routes
    Route::get('/about-us',               \App\Livewire\AboutUs::class)->name('about-us');
    Route::get('/faq',                    \App\Livewire\Faq::class)->name('faq');
    
    // Profile Routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
});

require __DIR__.'/auth.php';

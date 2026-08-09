<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\UserManagement;
use App\Livewire\Settings;
use App\Livewire\Tutorial;
use App\Livewire\FeePayment;
use App\Livewire\PaymentKhata;
use App\Livewire\Khotian;
use App\Livewire\Customer;
use App\Livewire\SalesReport;

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
    Route::get('/khotian', Khotian::class)->name('khotian');
    Route::get('/customer', Customer::class)->name('customer');
    Route::get('/sales-report', SalesReport::class)->name('sales-report');

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
    Route::get('/investment',             \App\Livewire\Investment::class)->name('investment');
    Route::get('/documents',              \App\Livewire\DocumentManager::class)->name('documents');
    Route::get('/documents/stream/{file}', function ($fileId) {
        $doc = \App\Models\DocumentFile::findOrFail($fileId);
        $path = storage_path('app/public/' . $doc->file_path);
        if (!file_exists($path)) {
            abort(404, 'ফাইল পাওয়া যায়নি।');
        }
        $mime = mime_content_type($path) ?: 'application/octet-stream';
        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . rawurlencode($doc->file_name) . '"'
        ]);
    })->name('documents.stream');
    Route::get('/malamal-stock',          \App\Livewire\MalamalStock::class)->name('malamal-stock');
    Route::get('/task-manager',           \App\Livewire\TaskManager::class)->name('task-manager');
    Route::get('/vehicle-account',        \App\Livewire\VehicleAccount::class)->name('vehicle-account');
    Route::get('/vehicle-rent',           \App\Livewire\VehicleRent::class)->name('vehicle-rent');
    Route::get('/sms-khata',              \App\Livewire\SmsPage::class)->name('sms-khata');
    Route::get('/cash-khata',             \App\Livewire\CashKhata::class)->name('cash-khata');
    Route::get('/load-khata',             \App\Livewire\LoadKhata::class)->name('load-khata');
    Route::get('/unload-khata',           \App\Livewire\UnloadKhata::class)->name('unload-khata');
    Route::get('/stock-khata',            \App\Livewire\StockKhata::class)->name('stock-khata');
    Route::get('/about-us',               \App\Livewire\AboutUs::class)->name('about-us');
    Route::get('/faq',                    \App\Livewire\Faq::class)->name('faq');
    Route::get('/phone-number',           \App\Livewire\PhoneNumber::class)->name('phone-number');
    Route::get('/deuna-pauna',            \App\Livewire\DeunaLedger::class)->name('deuna-pauna');
    Route::get('/deuna-pauna/{id}',       \App\Livewire\DeunaProfile::class)->name('deuna-pauna.profile');

    // Profile Routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');

    Route::post('/update-season', function (\Illuminate\Http\Request $request) {
        $season = $request->input('season', '২৫-২৬');
        \App\Models\Setting::set('season', $season);
        return response()->json(['success' => true, 'season' => $season], 200, [], JSON_UNESCAPED_UNICODE)->setCharset('UTF-8');
    })->name('update-season');
});

require __DIR__.'/auth.php';

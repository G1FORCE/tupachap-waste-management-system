<?php

use App\Http\Controllers\MpesaController;
use App\Livewire\CollectorDashboard;
use App\Livewire\LiveTrackingMap;
use App\Livewire\RequestPickup;
use App\Models\WasteRequest;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Laravel Breeze/Fortify supplies auth routes (login/register) - see README setup step 4
require __DIR__.'/auth.php';
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'collector' => redirect()->route('collector.dashboard'),
        default => redirect()->route('requests.create'),
    };
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {

    // --- User side ---
    Route::get('/request-pickup', RequestPickup::class)->name('requests.create');
    Route::get('/requests/{request}', LiveTrackingMap::class)->name('requests.show');
    Route::get('/my-requests', function () {
        return view('requests.index', [
            'requests' => WasteRequest::where('user_id', auth()->id())->latest()->get(),
        ]);
    })->name('requests.index');

    // --- Collector side ---
    Route::get('/collector/dashboard', CollectorDashboard::class)
        ->middleware('role:collector')
        ->name('collector.dashboard');

    // --- Payment ---
    Route::post('/requests/{request}/pay', [MpesaController::class, 'initiate'])->name('payments.initiate');
});

// M-Pesa hits this URL directly (no auth - Safaricom's servers call it)
Route::post('/mpesa/callback', [MpesaController::class, 'callback'])->name('mpesa.callback');

// --- Admin ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
});

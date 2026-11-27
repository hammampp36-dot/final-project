<?php

use App\Http\Controllers\Admin\AdminCropController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLandController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TransactionController;
use App\Livewire\CropManager;
use App\Livewire\FarmCalculator;
use App\Livewire\FarmerSearch;
use App\Livewire\LandManager;
use App\Livewire\ScheduleManager;
use App\Livewire\TransactionManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Agrismart Integrated Agricultural Platform
|--------------------------------------------------------------------------
|
| - Semua modul fitur utama langsung diarahkan ke Komponen LIVEWIRE
| - Autentikasi (login, register, logout) dihandle MANUAL oleh Auth Controller
| - Layout dan grouping route tertata rapi, modular, dan bersih
|
*/

// ==========================================
// 1. PUBLIC ROUTES (UMUM)
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ==========================================
// 2. PETANI / USER ROUTES (LIVEWIRE POWERED)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    // 2.1 Dashboard Petani
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2.2 Modul Lahan & QR Code (Full Livewire)
    Route::get('/lands', LandManager::class)->name('lands.index');
    Route::get('/lands/scan', [LandController::class, 'scan'])->name('lands.scan');
    Route::get('/lands/{land}/qr-download', [LandController::class, 'downloadQr'])->name('lands.qr-download');
    Route::get('/lands/create', [LandController::class, 'create'])->name('lands.create');
    Route::post('/lands', [LandController::class, 'store'])->name('lands.store');
    Route::get('/lands/{land}', [LandController::class, 'show'])->name('lands.show');
    Route::delete('/lands/{land}', [LandController::class, 'destroy'])->name('lands.destroy');

    // 2.3 Modul Katalog Tanaman & Panduan Budidaya (Full Livewire)
    Route::get('/crops', CropManager::class)->name('crops.index');

    // 2.4 Modul Jadwal Tanam & Rekomendasi (Full Livewire)
    Route::get('/schedules', ScheduleManager::class)->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::patch('/schedules/{schedule}/toggle', [ScheduleController::class, 'toggleStatus'])->name('schedules.toggle');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // 2.5 Modul Kalkulator Kebutuhan Tani Presisi (Full Livewire)
    Route::get('/calculator', FarmCalculator::class)->name('calculator.index');

    // 2.6 Modul Keuangan Kas Petani & Ekspor CSV (Full Livewire)
    Route::get('/transactions', TransactionManager::class)->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/export', [TransactionController::class, 'exportCsv'])->name('transactions.export');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // 2.7 Pengaturan Akun & Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// 3. ADMINISTRATOR ROUTES (LIVEWIRE POWERED)
// ==========================================
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // 3.1 Executive Agro Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // 3.2 Data Petani (Full Livewire Live Search)
        Route::get('/users', FarmerSearch::class)->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // 3.3 Master Katalog Tanaman (Full Livewire CRUD)
        Route::get('/crops', CropManager::class)->name('crops.index');
        Route::resource('crops', AdminCropController::class)->except(['index']);

        // 3.4 Supervisi Lahan Seluruh Petani (Full Livewire)
        Route::get('/lands', LandManager::class)->name('lands.index');
        Route::resource('lands', AdminLandController::class)->except(['index']);

        // 3.5 Supervisi Jadwal & Agenda Global (Full Livewire)
        Route::get('/schedules', ScheduleManager::class)->name('schedules.index');
        Route::patch('/schedules/{schedule}/toggle', [AdminScheduleController::class, 'toggleStatus'])->name('schedules.toggle');
        Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');

        // 3.6 Master Kas Global & Ekspor Laporan CSV (Full Livewire)
        Route::get('/transactions', TransactionManager::class)->name('transactions.index');
        Route::get('/transactions/export', [AdminTransactionController::class, 'exportCsv'])->name('transactions.export');
        Route::delete('/transactions/{transaction}', [AdminTransactionController::class, 'destroy'])->name('transactions.destroy');

        // 3.7 Kalkulator Makro Skala Besar (Full Livewire)
        Route::get('/calculator', FarmCalculator::class)->name('calculator');
    });

// ==========================================
// 4. MANUAL AUTHENTICATION ROUTES
// ==========================================
require __DIR__.'/auth.php';

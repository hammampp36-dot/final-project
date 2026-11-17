<?php

use App\Http\Controllers\Admin\AdminCropController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLandController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Agrismart Integrated Agricultural Platform
|--------------------------------------------------------------------------
|
| 1. Public Routes: Halaman utama (Welcome)
| 2. Farmer Routes (Petani/User): Dashboard, Lahan & QR, Jadwal, Kas, Kalkulator
| 3. Administrator Routes (Admin): Executive Dashboard, Pengguna, Master Data
| 4. Authentication Routes: Disertakan dari routes/auth.php (Manual Controller)
|
*/

// ==========================================
// 1. PUBLIC ROUTES (UMUM)
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ==========================================
// 2. PETANI / USER ROUTES (AUTH PROTECTED)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    // 2.1 Dashboard Petani (Otomatis redirect Admin ke Admin Dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2.2 Modul Lahan & QR Code (Livewire Powered)
    Route::get('/lands/scan', [LandController::class, 'scan'])->name('lands.scan');
    Route::get('/lands/{land}/qr-download', [LandController::class, 'downloadQr'])->name('lands.qr-download');
    Route::resource('lands', LandController::class)->except(['edit', 'update']);

    // 2.3 Modul Katalog Tanaman & Panduan Budidaya (Livewire Powered)
    Route::get('/crops', [CropController::class, 'index'])->name('crops.index');

    // 2.4 Modul Jadwal Tanam & Checklist Aktivitas (Livewire Powered)
    Route::resource('schedules', ScheduleController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::patch('/schedules/{schedule}/toggle', [ScheduleController::class, 'toggleStatus'])->name('schedules.toggle');

    // 2.5 Modul Kalkulator Kebutuhan Tani (Livewire Powered)
    Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator.index');

    // 2.6 Modul Keuangan Kas Petani & Ekspor CSV (Livewire Powered, Audit Protected)
    Route::get('/transactions/export', [TransactionController::class, 'exportCsv'])->name('transactions.export');
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'destroy']);

    // 2.7 Pengaturan Akun & Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// 3. ADMINISTRATOR ROUTES (ROLE: ADMIN)
// ==========================================
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // 3.1 Executive Agro Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // 3.2 Manajemen Akun Petani (Livewire Live Search & Detail)
        Route::resource('users', AdminUserController::class)->only(['index', 'show', 'destroy']);

        // 3.3 Master Katalog Tanaman (Livewire Powered Full CRUD)
        Route::resource('crops', AdminCropController::class);

        // 3.4 Supervisi Lahan Seluruh Petani (Livewire Powered)
        Route::resource('lands', AdminLandController::class)->only(['index', 'show', 'destroy']);

        // 3.5 Supervisi Jadwal & Agenda Global (Livewire Powered)
        Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
        Route::patch('/schedules/{schedule}/toggle', [AdminScheduleController::class, 'toggleStatus'])->name('schedules.toggle');
        Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');

        // 3.6 Master Kas Keuangan Global & Ekspor Laporan CSV
        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/export', [AdminTransactionController::class, 'exportCsv'])->name('transactions.export');
        Route::delete('/transactions/{transaction}', [AdminTransactionController::class, 'destroy'])->name('transactions.destroy');

        // 3.7 Kalkulator Makro Skala Kelompok Tani
        Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator');
    });

// ==========================================
// 4. MANUAL AUTHENTICATION ROUTES
// ==========================================
require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController; // Pastikan nanti controller ini dibuat
use App\Http\Controllers\PengawasanController; // Pastikan nanti controller ini dibuat


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(); // Ini route bawaan login/register

// GROUP ROUTE YANG BUTUH LOGIN
Route::middleware(['auth'])->group(function () {
    // Tambahkan Route ini agar /dashboard tidak 404
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan/{id}', [PengawasanController::class, 'show'])->name('pengawasan.detail');
    Route::get('/pengawasan/{id}/cetak-notulen', [PengawasanController::class, 'cetakNotulen'])->name('pengawasan.cetak.notulen');
    Route::get('/pengawasan/{id}/cetak-dokumentasi', [PengawasanController::class, 'cetakDokumentasi'])->name('pengawasan.cetak.dokumentasi');

    // 1. Dashboard Admin (Hanya Admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard.admin');
        // Tambahkan route kelola user di sini
        Route::resource('admin/surat-tugas', App\Http\Controllers\Admin\SuratTugasController::class, ['as' => 'admin']);
        Route::resource('admin/users', App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);
    });

    // 2. Dashboard Ketua (Hanya Ketua)
    Route::middleware(['role:ketua'])->group(function () {
        // Arahkan ke indexAdmin, tapi nama routenya tetap dashboard.ketua agar tidak error di login
        Route::get('/ketua/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard.ketua');
        // Tambahkan route lihat laporan di sini
    });

    // 3. Dashboard Pengawas (Hanya Pengawas)
    Route::middleware(['role:pengawas'])->group(function () {
        Route::get('/pengawas/dashboard', [DashboardController::class, 'indexPengawas'])->name('dashboard.pengawas');

        // Tambahkan route upload notulen di sini
        // TAMBAHKAN BARIS INI:
        Route::resource('pengawasan', PengawasanController::class);
    });
});

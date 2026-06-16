<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CutiController;

// 1. Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. Protected Routes (Requires active session)
Route::middleware('khanza.auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kepegawaian/dashboard', [DashboardController::class, 'kepegawaian'])->name('pegawai.dashboard');

    // Kepegawaian Routes
    Route::get('/pegawai', [PegawaiController::class, 'index'])
        ->name('pegawai.index');
        
    Route::get('/pegawai/create', [PegawaiController::class, 'create'])
        ->name('pegawai.create')
        ->middleware('khanza.auth:pegawai_admin');
        
    Route::post('/pegawai', [PegawaiController::class, 'store'])
        ->name('pegawai.store')
        ->middleware('khanza.auth:pegawai_admin');
        
    Route::get('/pegawai/{nik}/edit', [PegawaiController::class, 'edit'])
        ->name('pegawai.edit')
        ->middleware('khanza.auth:pegawai_admin');
        
    Route::put('/pegawai/{nik}', [PegawaiController::class, 'update'])
        ->name('pegawai.update')
        ->middleware('khanza.auth:pegawai_admin');
        
    Route::delete('/pegawai/{nik}', [PegawaiController::class, 'destroy'])
        ->name('pegawai.destroy')
        ->middleware('khanza.auth:pegawai_admin');

    // Cuti Pegawai Routes
    Route::get('/cuti/new-no', [CutiController::class, 'getNewNoPengajuan'])
        ->name('cuti.new-no')
        ->middleware('khanza.auth:pengajuan_cuti');

    Route::get('/cuti/employees', [CutiController::class, 'getEmployees'])
        ->name('cuti.employees')
        ->middleware('khanza.auth:pengajuan_cuti');

    Route::get('/cuti', [CutiController::class, 'index'])
        ->name('cuti.index')
        ->middleware('khanza.auth:pengajuan_cuti');
        
    Route::get('/cuti/create', [CutiController::class, 'create'])
        ->name('cuti.create')
        ->middleware('khanza.auth:pengajuan_cuti');
        
    Route::post('/cuti', [CutiController::class, 'store'])
        ->name('cuti.store')
        ->middleware('khanza.auth:pengajuan_cuti');

    Route::put('/cuti/{no_pengajuan}', [CutiController::class, 'update'])
        ->name('cuti.update')
        ->middleware('khanza.auth:pengajuan_cuti');
        
    Route::post('/cuti/{no_pengajuan}/approve', [CutiController::class, 'approve'])
        ->name('cuti.approve')
        ->middleware('khanza.auth:pegawai_admin');
        
    Route::post('/cuti/{no_pengajuan}/reject', [CutiController::class, 'reject'])
        ->name('cuti.reject')
        ->middleware('khanza.auth:pegawai_admin');
        
    Route::delete('/cuti/{no_pengajuan}', [CutiController::class, 'destroy'])
        ->name('cuti.destroy')
        ->middleware('khanza.auth:pengajuan_cuti');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\SuratKeteranganSehatController;
use App\Http\Controllers\TukarJagaController;
use App\Http\Controllers\KelahiranBayiController;

// 1. Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

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

    Route::post('/cuti/{no_pengajuan}/approve-pj', [CutiController::class, 'approvePj'])
        ->name('cuti.approve-pj');
        
    Route::post('/cuti/{no_pengajuan}/reject-pj', [CutiController::class, 'rejectPj'])
        ->name('cuti.reject-pj');
        
    Route::delete('/cuti/{no_pengajuan}', [CutiController::class, 'destroy'])
        ->name('cuti.destroy')
        ->middleware('khanza.auth:pengajuan_cuti');

    Route::get('/cuti/{no_pengajuan}/cetak', [CutiController::class, 'cetak'])
        ->name('cuti.cetak')
        ->middleware('khanza.auth:pengajuan_cuti')
        ->where('no_pengajuan', '.*');

    // Tukar Jaga Routes
    Route::get('/tukar-jaga/new-no', [TukarJagaController::class, 'getNewNoPengajuan'])
        ->name('tukar-jaga.new-no')
        ->middleware('khanza.auth:pengajuan_cuti');

    Route::get('/tukar-jaga/employees', [TukarJagaController::class, 'getEmployees'])
        ->name('tukar-jaga.employees')
        ->middleware('khanza.auth:pengajuan_cuti');

    Route::get('/tukar-jaga', [TukarJagaController::class, 'index'])
        ->name('tukar-jaga.index')
        ->middleware('khanza.auth:pengajuan_cuti');
        
    Route::post('/tukar-jaga', [TukarJagaController::class, 'store'])
        ->name('tukar-jaga.store')
        ->middleware('khanza.auth:pengajuan_cuti');

    Route::put('/tukar-jaga/{no_pengajuan}', [TukarJagaController::class, 'update'])
        ->name('tukar-jaga.update')
        ->middleware('khanza.auth:pengajuan_cuti')
        ->where('no_pengajuan', '.*');

    Route::delete('/tukar-jaga/{no_pengajuan}', [TukarJagaController::class, 'destroy'])
        ->name('tukar-jaga.destroy')
        ->middleware('khanza.auth:pengajuan_cuti')
        ->where('no_pengajuan', '.*');

    Route::get('/tukar-jaga/{no_pengajuan}/cetak', [TukarJagaController::class, 'cetak'])
        ->name('tukar-jaga.cetak')
        ->middleware('khanza.auth:pengajuan_cuti')
        ->where('no_pengajuan', '.*');

    Route::post('/tukar-jaga/{no_pengajuan}/approve', [TukarJagaController::class, 'approve'])
        ->name('tukar-jaga.approve')
        ->middleware('khanza.auth:pegawai_admin')
        ->where('no_pengajuan', '.*');
        
    Route::post('/tukar-jaga/{no_pengajuan}/reject', [TukarJagaController::class, 'reject'])
        ->name('tukar-jaga.reject')
        ->middleware('khanza.auth:pegawai_admin')
        ->where('no_pengajuan', '.*');

    Route::post('/tukar-jaga/{no_pengajuan}/approve-pj', [TukarJagaController::class, 'approvePj'])
        ->name('tukar-jaga.approve-pj')
        ->where('no_pengajuan', '.*');
        
    Route::post('/tukar-jaga/{no_pengajuan}/reject-pj', [TukarJagaController::class, 'rejectPj'])
        ->name('tukar-jaga.reject-pj')
        ->where('no_pengajuan', '.*');

    // 2.3. Surat Subsystem Routes
    Route::get('/surat/dashboard', [DashboardController::class, 'surat'])->name('surat.dashboard');
    
    Route::get('/surat/sehat/new-no', [SuratKeteranganSehatController::class, 'getNewNoSurat'])
        ->name('surat.sehat.new-no')
        ->middleware('khanza.auth:surat_keterangan_sehat');

    Route::get('/surat/sehat/registrasi-lookup', [SuratKeteranganSehatController::class, 'getRegistrasiLookup'])
        ->name('surat.sehat.registrasi-lookup')
        ->middleware('khanza.auth:surat_keterangan_sehat');

    Route::get('/surat/sehat', [SuratKeteranganSehatController::class, 'index'])
        ->name('surat.sehat.index')
        ->middleware('khanza.auth:surat_keterangan_sehat');

    Route::post('/surat/sehat', [SuratKeteranganSehatController::class, 'store'])
        ->name('surat.sehat.store')
        ->middleware('khanza.auth:surat_keterangan_sehat');

    Route::put('/surat/sehat/{no_surat}', [SuratKeteranganSehatController::class, 'update'])
        ->name('surat.sehat.update')
        ->middleware('khanza.auth:surat_keterangan_sehat')
        ->where('no_surat', '.*');

    Route::delete('/surat/sehat/{no_surat}', [SuratKeteranganSehatController::class, 'destroy'])
        ->name('surat.sehat.destroy')
        ->middleware('khanza.auth:surat_keterangan_sehat')
        ->where('no_surat', '.*');

    Route::get('/surat/sehat/{no_surat}/cetak', [SuratKeteranganSehatController::class, 'cetak'])
        ->name('surat.sehat.cetak')
        ->middleware('khanza.auth:surat_keterangan_sehat')
        ->where('no_surat', '.*');

    // 2.4. Surat Keterangan Kelahiran Bayi Routes
    Route::get('/surat/kelahiran/new-no', [KelahiranBayiController::class, 'getNewNo'])
        ->name('surat.kelahiran.new-no')
        ->middleware('khanza.auth:kelahiran_bayi');

    Route::get('/surat/kelahiran', [KelahiranBayiController::class, 'index'])
        ->name('surat.kelahiran.index')
        ->middleware('khanza.auth:kelahiran_bayi');

    Route::post('/surat/kelahiran', [KelahiranBayiController::class, 'store'])
        ->name('surat.kelahiran.store')
        ->middleware('khanza.auth:kelahiran_bayi');

    Route::put('/surat/kelahiran/{no_rkm_medis}', [KelahiranBayiController::class, 'update'])
        ->name('surat.kelahiran.update')
        ->middleware('khanza.auth:kelahiran_bayi')
        ->where('no_rkm_medis', '.*');

    Route::delete('/surat/kelahiran/{no_rkm_medis}', [KelahiranBayiController::class, 'destroy'])
        ->name('surat.kelahiran.destroy')
        ->middleware('khanza.auth:kelahiran_bayi')
        ->where('no_rkm_medis', '.*');

    Route::get('/surat/kelahiran/{no_rkm_medis}/cetak', [KelahiranBayiController::class, 'cetak'])
        ->name('surat.kelahiran.cetak')
        ->middleware('khanza.auth:kelahiran_bayi')
        ->where('no_rkm_medis', '.*');
});

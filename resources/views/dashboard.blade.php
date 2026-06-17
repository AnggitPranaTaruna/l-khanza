@extends('layouts.app')

@section('title', $nama_instansi)
@section('header_title', $nama_instansi)

@section('content')
<div class="card" style="margin-bottom: 40px; background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(16, 185, 129, 0.05)); border-color: rgba(14, 165, 233, 0.2); padding: 32px;">
    <h2 style="font-weight: 700; font-size: 1.75rem; margin-bottom: 8px; letter-spacing: -0.025em;">Portal Aplikasi {{ $nama_instansi }}</h2>
    <p style="color: var(--text-secondary); max-width: 800px; font-size: 0.95rem;">
        Halo <strong>{{ session('khanza_user')['name'] }}</strong>, selamat datang kembali. 
        Silakan pilih salah satu modul aplikasi di bawah ini untuk masuk ke ruang kerja subsystem masing-masing.
    </p>
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
    <!-- Modul Kepegawaian (ACTIVE) -->
    <a href="{{ route('pegawai.dashboard') }}" class="card" style="display: flex; flex-direction: column; height: 100%; border-color: rgba(14, 165, 233, 0.2); background-color: rgba(14, 165, 233, 0.03); cursor: pointer; padding: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div class="stat-icon primary" style="margin-bottom: 0;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <span class="badge badge-success" style="font-size: 0.7rem; padding: 4px 12px;">Aktif</span>
        </div>
        
        <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 10px; color: var(--text-primary);">Modul Kepegawaian</h3>
        <p style="color: var(--text-secondary); font-size: 0.9rem; flex-grow: 1; margin-bottom: 24px; line-height: 1.6;">
            Kelola profil lengkap pegawai, jenjang jabatan, unit departemen, serta permohonan dan persetujuan cuti karyawan secara terintegrasi.
        </p>
        <span style="font-weight: 600; font-size: 0.9rem; color: var(--primary); display: inline-flex; align-items: center; gap: 6px;">
            Masuk Modul ➔
        </span>
    </a>

    <!-- Modul Surat Keterangan (ACTIVE) -->
    @php
        $user = session('khanza_user');
        $isAdmin = $user['role'] === 'admin';
        $hasSuratAccess = $isAdmin || (isset($user['permissions']['surat_keterangan_sehat']) && $user['permissions']['surat_keterangan_sehat'] === 'true');
    @endphp
    @if($hasSuratAccess)
    <a href="{{ route('surat.dashboard') }}" class="card" style="display: flex; flex-direction: column; height: 100%; border-color: rgba(16, 185, 129, 0.2); background-color: rgba(16, 185, 129, 0.03); cursor: pointer; padding: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div class="stat-icon success" style="margin-bottom: 0;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <span class="badge badge-success" style="font-size: 0.7rem; padding: 4px 12px;">Aktif</span>
        </div>
        
        <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 10px; color: var(--text-primary);">Modul Surat</h3>
        <p style="color: var(--text-secondary); font-size: 0.9rem; flex-grow: 1; margin-bottom: 24px; line-height: 1.6;">
            Buat dan kelola surat keterangan medis resmi, termasuk Surat Keterangan Sehat, dengan format nomor surat otomatis dan terhubung ke data registrasi pasien.
        </p>
        <span style="font-weight: 600; font-size: 0.9rem; color: var(--success); display: inline-flex; align-items: center; gap: 6px;">
            Masuk Modul ➔
        </span>
    </a>
    @else
    <div class="card" style="display: flex; flex-direction: column; height: 100%; opacity: 0.65; cursor: not-allowed; padding: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div class="stat-icon success" style="margin-bottom: 0;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
            <span class="badge badge-danger" style="font-size: 0.7rem; padding: 4px 12px; background-color: rgba(239, 68, 68, 0.1); color: var(--danger);">Terkunci</span>
        </div>
        
        <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 10px; color: var(--text-secondary);">Modul Surat</h3>
        <p style="color: var(--text-secondary); font-size: 0.9rem; flex-grow: 1; margin-bottom: 24px; line-height: 1.6;">
            Buat dan kelola surat keterangan medis resmi, termasuk Surat Keterangan Sehat, dengan format nomor surat otomatis dan terhubung ke data registrasi pasien.
        </p>
        <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 6px;">
            Tidak Ada Akses
        </span>
    </div>
    @endif

    <!-- Modul Farmasi (LOCKED) -->
    <div class="card" style="display: flex; flex-direction: column; height: 100%; opacity: 0.65; cursor: not-allowed; padding: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div class="stat-icon warning" style="margin-bottom: 0;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4.5 16.5c-1.5 1.26-2 3-2 3s1.5-.5 3-2"></path>
                    <path d="M10 11l-5.5 5.5"></path>
                    <path d="M19 8.5c1.5-1.26 2-3 2-3s-1.5.5-3 2"></path>
                    <path d="M14 13l5.5-5.5"></path>
                    <rect x="9" y="9" width="6" height="6" rx="1"></rect>
                </svg>
            </div>
            <span class="badge badge-danger" style="font-size: 0.7rem; padding: 4px 12px; background-color: rgba(239, 68, 68, 0.1); color: var(--danger);">Terkunci</span>
        </div>
        
        <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 10px; color: var(--text-secondary);">Modul Farmasi</h3>
        <p style="color: var(--text-secondary); font-size: 0.9rem; flex-grow: 1; margin-bottom: 24px; line-height: 1.6;">
            Kelola data obat-obatan, BHP, transaksi resep apotek rawat jalan/rawat inap, pengadaan suplier, serta stok opname inventaris obat.
        </p>
        <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 6px;">
            Segera Hadir
        </span>
    </div>

    <!-- Modul Rekam Medis (LOCKED) -->
    <div class="card" style="display: flex; flex-direction: column; height: 100%; opacity: 0.65; cursor: not-allowed; padding: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div class="stat-icon success" style="margin-bottom: 0;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <span class="badge badge-danger" style="font-size: 0.7rem; padding: 4px 12px; background-color: rgba(239, 68, 68, 0.1); color: var(--danger);">Terkunci</span>
        </div>
        
        <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 10px; color: var(--text-secondary);">Modul Rekam Medis</h3>
        <p style="color: var(--text-secondary); font-size: 0.9rem; flex-grow: 1; margin-bottom: 24px; line-height: 1.6;">
            Pencatatan klinis pasien, diagnosa penyakit (ICD-10 & ICD-9-CM), berkas rekam medis digital (ERM), serta pelaporan sensus harian rumah sakit.
        </p>
        <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 6px;">
            Segera Hadir
        </span>
    </div>
</div>
@endsection

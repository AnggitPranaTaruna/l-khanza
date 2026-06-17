@extends('layouts.app')

@section('title', 'Dashboard Surat Keterangan')
@section('header_title', 'Dashboard Surat Keterangan')

@section('content')
@php
    $user = session('khanza_user');
    $isAdmin = $user['role'] === 'admin';
@endphp
<div class="card" style="margin-bottom: 32px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(14, 165, 233, 0.05)); border-color: rgba(16, 185, 129, 0.2); position: relative;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-weight: 700; font-size: 1.5rem; margin-bottom: 8px;">Workspace Surat Keterangan Medis</h2>
            <p style="color: var(--text-secondary); max-width: 650px; font-size: 0.9rem;">
                Anda berada di dalam subsystem **Modul Surat**. Di sini Anda dapat memantau, membuat, dan mencetak surat keterangan medis resmi pasien.
            </p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="font-size: 0.85rem; padding: 8px 16px; border-radius: 6px;">
                ⬅️ Kembali ke Menu Utama
            </a>
        </div>
    </div>
</div>

<div class="grid">
    <!-- Stat 1: Surat Ket Sehat -->
    <div class="card">
        <div class="stat-icon success">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <div class="stat-number">{{ $stats['total_sehat'] }}</div>
        <div class="stat-label">Surat Ket. Sehat</div>
    </div>

    <!-- Stat 2: Surat Ket Kelahiran -->
    @php
        $hasKelahiranBayiAccess = $isAdmin || (isset($user['permissions']['kelahiran_bayi']) && $user['permissions']['kelahiran_bayi'] === 'true');
    @endphp
    <div class="card" style="{{ !$hasKelahiranBayiAccess ? 'opacity: 0.5; cursor: not-allowed;' : '' }}">
        <div class="stat-icon warning">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                <line x1="15" y1="9" x2="15.01" y2="9"></line>
            </svg>
        </div>
        <div class="stat-number">{{ $stats['total_kelahiran'] }}</div>
        <div class="stat-label">Surat Ket. Kelahiran Bayi</div>
    </div>

    <!-- Stat 3: Bebas Narkoba -->
    <div class="card" style="opacity: 0.65;">
        <div class="stat-icon primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
        </div>
        <div class="stat-number">{{ $stats['total_bebas_narkoba'] }}</div>
        <div class="stat-label">Bebas Narkoba (Segera Hadir)</div>
    </div>
</div>

<div class="card" style="padding: 32px 0 0 0; overflow: hidden;">
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0 32px 24px 32px; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-weight: 600; font-size: 1.15rem;">Kelola Surat Keterangan Medis</h3>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            @if($isAdmin || (isset($user['permissions']['surat_keterangan_sehat']) && $user['permissions']['surat_keterangan_sehat'] === 'true'))
                <a href="{{ route('surat.sehat.index') }}" class="btn btn-secondary btn-sm">Buka Surat Ket. Sehat</a>
            @endif
            @if($hasKelahiranBayiAccess)
                <a href="{{ route('surat.kelahiran.index') }}" class="btn btn-primary btn-sm">Buka Surat Ket. Kelahiran Bayi</a>
            @endif
        </div>
    </div>

    <div class="table-container">
        @if($recentSurat->isEmpty())
            <div style="padding: 40px; text-align: center; color: var(--text-secondary);">
                Belum ada data surat keterangan sehat yang tercatat.
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>No. Rawat</th>
                        <th>No. R.M.</th>
                        <th>Nama Pasien</th>
                        <th>Tanggal Surat</th>
                        <th>Keperluan</th>
                        <th>Kesimpulan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSurat as $s)
                        <tr>
                            <td style="font-weight: 600; color: var(--primary);">{{ $s->no_surat }}</td>
                            <td>{{ $s->no_rawat }}</td>
                            <td>{{ $s->no_rkm_medis }}</td>
                            <td style="font-weight: 500;">{{ $s->nm_pasien }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggalsurat)->translatedFormat('d M Y') }}</td>
                            <td>{{ $s->keperluan }}</td>
                            <td>
                                @if($s->kesimpulan === 'Sehat')
                                    <span class="badge badge-success">Sehat</span>
                                @else
                                    <span class="badge badge-danger">Tidak Sehat</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

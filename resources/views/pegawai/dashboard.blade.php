@extends('layouts.app')

@section('title', 'Dashboard Kepegawaian')
@section('header_title', 'Dashboard Kepegawaian')

@section('content')
<div class="card" style="margin-bottom: 32px; background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(16, 185, 129, 0.05)); border-color: rgba(14, 165, 233, 0.2); position: relative;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-weight: 700; font-size: 1.5rem; margin-bottom: 8px;">Workspace Kepegawaian & Cuti</h2>
            <p style="color: var(--text-secondary); max-width: 650px; font-size: 0.9rem;">
                Anda berada di dalam subsystem **Modul Kepegawaian**. Di sini Anda dapat memantau kuota cuti pegawai, mereset data pengajuan, dan mengelola kepegawaian.
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
    <!-- Stat 1: Total Pegawai -->
    <div class="card">
        <div class="stat-icon primary">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="stat-number">{{ $stats['total_pegawai'] }}</div>
        <div class="stat-label">Total Pegawai</div>
    </div>

    <!-- Stat 2: Pending Cuti -->
    <div class="card">
        <div class="stat-icon warning">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-number">{{ $stats['pending_cuti'] }}</div>
        <div class="stat-label">Pengajuan Cuti Pending</div>
    </div>

    <!-- Stat 3: Cuti Disetujui -->
    <div class="card">
        <div class="stat-icon success">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-number">{{ $stats['approved_cuti'] }}</div>
        <div class="stat-label">Cuti Telah Disetujui</div>
    </div>
</div>

<div class="card" style="padding: 32px 0 0 0; overflow: hidden;">
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0 32px 24px 32px; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-weight: 600; font-size: 1.15rem;">Aktivitas Pengajuan Cuti Terbaru</h3>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('cuti.index') }}" class="btn btn-primary btn-sm">Buka Menu Pengajuan Cuti</a>
        </div>
    </div>

    <div class="table-container">
        @if($recentLeaves->isEmpty())
            <div style="padding: 40px; text-align: center; color: var(--text-secondary);">
                Belum ada pengajuan cuti yang tercatat.
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Pengajuan</th>
                        <th>Pemohon</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Durasi Cuti</th>
                        <th>Urgensi</th>
                        <th>Status</th>
                        @if(session('khanza_user')['role'] === 'admin')
                            <th style="text-align: right;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLeaves as $l)
                        <tr>
                            <td style="font-weight: 600; color: var(--primary);">{{ $l->no_pengajuan }}</td>
                            <td>
                                <div style="font-weight: 500;">{{ $l->nama_pemohon }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">NIK: {{ $l->nik }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($l->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>
                                <div style="font-weight: 500;">{{ $l->jumlah }} Hari</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                    {{ \Carbon\Carbon::parse($l->tanggal_awal)->translatedFormat('d M') }} s/d {{ \Carbon\Carbon::parse($l->tanggal_akhir)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $l->urgensi }}</span>
                            </td>
                            <td>
                                @if($l->status === 'Proses Pengajuan')
                                    <span class="badge badge-warning">Proses</span>
                                @elseif($l->status === 'Disetujui')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            @if(session('khanza_user')['role'] === 'admin')
                                <td style="text-align: right;">
                                    @if($l->status === 'Proses Pengajuan')
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <form action="{{ route('cuti.approve', $l->no_pengajuan) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--success); padding: 4px 10px; font-size: 0.75rem;">Setujui</button>
                                            </form>
                                            <form action="{{ route('cuti.reject', $l->no_pengajuan) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 10px; font-size: 0.75rem;">Tolak</button>
                                            </form>
                                        </div>
                                    @else
                                        <span style="font-size: 0.85rem; color: var(--text-secondary);">Selesai</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

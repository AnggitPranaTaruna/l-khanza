@extends('layouts.app')

@section('title', 'Dashboard Kepegawaian')
@section('header_title', 'Dashboard Kepegawaian')

@section('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px;
        margin-bottom: 32px;
    }
    .dashboard-grid .card {
        padding: 16px; 
        display: flex; 
        align-items: center; 
        gap: 14px;
    }
    .dashboard-grid .stat-icon {
        padding: 8px; 
        margin-bottom: 0; 
        border-radius: 8px;
        flex-shrink: 0;
        display: inline-flex;
    }
    .dashboard-grid .stat-icon svg {
        width: 18px;
        height: 18px;
    }
    .dashboard-grid .stat-number {
        font-size: 1.4rem; 
        margin-bottom: 2px; 
        line-height: 1;
        font-weight: 700;
        letter-spacing: -0.02em;
    }
    .dashboard-grid .stat-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    @media (max-width: 1200px) {
        .dashboard-grid {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }
</style>
@endsection

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

<div class="dashboard-grid">
    <!-- Stat 1: Total Surat Masuk -->
    <div class="card">
        <div class="stat-icon primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $stats['total_surat'] }}</div>
            <div class="stat-label">Total Surat Masuk</div>
        </div>
    </div>

    <!-- Stat 2: Pending Cuti -->
    <div class="card">
        <div class="stat-icon warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $stats['pending_cuti'] }}</div>
            <div class="stat-label">Cuti Pending</div>
        </div>
    </div>

    <!-- Stat 3: Cuti Disetujui -->
    <div class="card">
        <div class="stat-icon success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $stats['approved_cuti'] }}</div>
            <div class="stat-label">Cuti Disetujui</div>
        </div>
    </div>

    <!-- Stat 4: Cuti Ditolak -->
    <div class="card">
        <div class="stat-icon danger" style="color: var(--danger); background-color: var(--danger-bg);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $stats['rejected_cuti'] }}</div>
            <div class="stat-label">Cuti Ditolak</div>
        </div>
    </div>

    <!-- Stat 5: Pending Tukar Jaga -->
    <div class="card">
        <div class="stat-icon warning" style="color: var(--warning); background-color: rgba(217, 119, 6, 0.1);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 2.1l4 2-4 2"></path>
                <path d="M3 12h18"></path>
                <path d="M21 12l-4-4m4 4l-4 4"></path>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $stats['pending_tukar'] }}</div>
            <div class="stat-label">Tukar Pending</div>
        </div>
    </div>

    <!-- Stat 6: Tukar Jaga Disetujui -->
    <div class="card">
        <div class="stat-icon success" style="color: var(--success); background-color: var(--success-bg);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $stats['approved_tukar'] }}</div>
            <div class="stat-label">Tukar Disetujui</div>
        </div>
    </div>

    <!-- Stat 7: Tukar Jaga Ditolak -->
    <div class="card">
        <div class="stat-icon danger" style="color: var(--danger); background-color: var(--danger-bg);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div>
            <div class="stat-number">{{ $stats['rejected_tukar'] }}</div>
            <div class="stat-label">Tukar Ditolak</div>
        </div>
    </div>
</div>

<div class="card" style="padding: 32px 0 0 0; overflow: hidden;">
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0 32px 24px 32px; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-weight: 600; font-size: 1.15rem;">Daftar Pengajuan Cuti</h3>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            @if(session('khanza_user')['role'] === 'admin' || (isset(session('khanza_user')['permissions']['pengajuan_cuti']) && session('khanza_user')['permissions']['pengajuan_cuti'] === 'true'))
                <a href="{{ route('cuti.index') }}" class="btn btn-primary btn-sm">Buka Menu Pengajuan Cuti</a>
            @endif
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
                        <th style="text-align: right;">Aksi</th>
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
                                    <span class="badge badge-warning">Proses PJ</span>
                                @elseif($l->status === 'Disetujui PJ')
                                    <span class="badge badge-info">Disetujui PJ</span>
                                @elseif($l->status === 'Disetujui')
                                    <span class="badge badge-success">Disetujui HRD</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                 @php
                                     $loggedInNik = session('khanza_user')['username'];
                                     $isAdmin = session('khanza_user')['role'] === 'admin';
                                 @endphp
                                 <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                     <button type="button" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 0.75rem; background-color: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer;" 
                                             onclick="openCutiDetail('{{ $l->no_pengajuan }}', '{{ \Carbon\Carbon::parse($l->tanggal)->translatedFormat('d F Y') }}', '{{ $l->nama_pemohon }} ({{ $l->nik }})', '{{ $l->urgensi }}', '{{ \Carbon\Carbon::parse($l->tanggal_awal)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($l->tanggal_akhir)->translatedFormat('d M Y') }}', '{{ $l->jumlah }} Hari', '{{ addslashes($l->alamat) }}', '{{ addslashes($l->kepentingan) }}', '{{ $l->nama_pj }} ({{ $l->nik_pj }})', '{{ $l->status }}')">
                                         👁️ Detail
                                     </button>
                                     @if($isAdmin && $l->status === 'Disetujui PJ')
                                         <form action="{{ route('cuti.approve', $l->no_pengajuan) }}" method="POST" style="display:inline;">
                                             @csrf
                                             <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--success); padding: 4px 10px; font-size: 0.75rem;">Setujui (HRD)</button>
                                         </form>
                                         <form action="{{ route('cuti.reject', $l->no_pengajuan) }}" method="POST" style="display:inline;">
                                             @csrf
                                             <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 10px; font-size: 0.75rem;">Tolak (HRD)</button>
                                         </form>
                                     @elseif(!$isAdmin && $l->nik_pj === $loggedInNik && $l->status === 'Proses Pengajuan')
                                         <form action="{{ route('cuti.approve-pj', $l->no_pengajuan) }}" method="POST" style="display:inline;">
                                             @csrf
                                             <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--primary); padding: 4px 10px; font-size: 0.75rem;">Setujui (PJ)</button>
                                         </form>
                                         <form action="{{ route('cuti.reject-pj', $l->no_pengajuan) }}" method="POST" style="display:inline;">
                                             @csrf
                                             <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 10px; font-size: 0.75rem;">Tolak (PJ)</button>
                                         </form>
                                     @else
                                         <span style="font-size: 0.85rem; color: var(--text-secondary);">
                                             @if($l->status === 'Proses Pengajuan')
                                                 Menunggu PJ
                                             @elseif($l->status === 'Disetujui PJ')
                                                 Menunggu HRD
                                             @else
                                                 Selesai
                                             @endif
                                         </span>
                                     @endif
                                 </div>
                             </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<!-- Aktivitas Tukar Jaga Terbaru -->
<div class="card" style="padding: 32px 0 0 0; overflow: hidden; margin-top: 30px;">
    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0 32px 24px 32px; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-weight: 600; font-size: 1.15rem;">Daftar Pengajuan Tukar Jaga</h3>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('tukar-jaga.index') }}" class="btn btn-primary btn-sm">Buka Menu Tukar Jaga</a>
        </div>
    </div>

    <div class="table-container">
        @if($recentTukar->isEmpty())
            <div style="padding: 40px; text-align: center; color: var(--text-secondary);">
                Belum ada pengajuan tukar jaga yang tercatat.
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Pengajuan</th>
                        <th>Pemohon (Pihak I)</th>
                        <th>Pengganti (Pihak II)</th>
                        <th>Tgl Pengajuan</th>
                        <th>Tgl Swap (Tukar)</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTukar as $t)
                        <tr>
                            <td style="font-weight: 600; color: var(--primary);">{{ $t->no_pengajuan }}</td>
                            <td>
                                <div style="font-weight: 500;">{{ $t->nama_pemohon }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">NIK: {{ $t->nik_pemohon }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 500;">{{ $t->nama_tukar }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">NIK: {{ $t->nik_tukar }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}</td>
                             <td style="font-weight: 500; color: var(--warning);">
                                @php
                                    $tgl_mulai = \Carbon\Carbon::parse($t->tanggal_tukar_mulai);
                                    $tgl_akhir = \Carbon\Carbon::parse($t->tanggal_tukar_akhir);
                                @endphp
                                @if($tgl_mulai->equalTo($tgl_akhir))
                                    {{ $tgl_mulai->translatedFormat('d M Y') }}
                                @else
                                    @if($tgl_mulai->format('Y') === $tgl_akhir->format('Y'))
                                        @if($tgl_mulai->format('m') === $tgl_akhir->format('m'))
                                            {{ $tgl_mulai->translatedFormat('d') }} s.d. {{ $tgl_akhir->translatedFormat('d M Y') }}
                                        @else
                                            {{ $tgl_mulai->translatedFormat('d M') }} s.d. {{ $tgl_akhir->translatedFormat('d M Y') }}
                                        @endif
                                    @else
                                        {{ $tgl_mulai->translatedFormat('d M Y') }} s.d. {{ $tgl_akhir->translatedFormat('d M Y') }}
                                    @endif
                                @endif
                             </td>
                            <td>{{ Str::limit($t->alasan, 40) }}</td>
                            <td>
                                @if($t->status === 'Proses Pengajuan')
                                    <span class="badge badge-warning">Proses PJ</span>
                                @elseif($t->status === 'Disetujui PJ')
                                    <span class="badge badge-info">Disetujui PJ</span>
                                @elseif($t->status === 'Disetujui')
                                    <span class="badge badge-success">Disetujui HRD</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @php
                                    $loggedInNik = session('khanza_user')['username'];
                                    $isAdmin = session('khanza_user')['role'] === 'admin';
                                @endphp
                                <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                    <button type="button" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 0.75rem; background-color: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer;" 
                                            onclick="openTukarDetail('{{ $t->no_pengajuan }}', '{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d F Y') }}', '{{ $t->nama_pemohon }} ({{ $t->nik_pemohon }})', '{{ $t->nama_tukar }} ({{ $t->nik_tukar }})', '{{ \Carbon\Carbon::parse($t->tanggal_tukar_mulai)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($t->tanggal_tukar_akhir)->translatedFormat('d M Y') }}', '{{ addslashes($t->alasan) }}', '{{ $t->nama_pj }} ({{ $t->nik_pj }})', '{{ $t->status }}')">
                                        👁️ Detail
                                    </button>
                                    @if($isAdmin && $t->status === 'Disetujui PJ')
                                        <form action="{{ route('tukar-jaga.approve', $t->no_pengajuan) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--success); padding: 4px 10px; font-size: 0.75rem;">Setujui (HRD)</button>
                                        </form>
                                        <form action="{{ route('tukar-jaga.reject', $t->no_pengajuan) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 10px; font-size: 0.75rem;">Tolak (HRD)</button>
                                        </form>
                                    @elseif(!$isAdmin && $t->nik_pj === $loggedInNik && $t->status === 'Proses Pengajuan')
                                        <form action="{{ route('tukar-jaga.approve-pj', $t->no_pengajuan) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--primary); padding: 4px 10px; font-size: 0.75rem;">Setujui (PJ)</button>
                                        </form>
                                        <form action="{{ route('tukar-jaga.reject-pj', $t->no_pengajuan) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 10px; font-size: 0.75rem;">Tolak (PJ)</button>
                                        </form>
                                    @else
                                        <span style="font-size: 0.85rem; color: var(--text-secondary);">
                                            @if($t->status === 'Proses Pengajuan')
                                                Menunggu PJ
                                            @elseif($t->status === 'Disetujui PJ')
                                                Menunggu HRD
                                            @else
                                                Selesai
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<!-- Cuti Detail Modal -->
<div class="modal-overlay" id="cuti-detail-modal">
    <div class="modal-box" style="max-width: 600px;">
        <div class="modal-header">
            <span>Detail Pengajuan Cuti</span>
            <button class="modal-close" onclick="closeCutiModal()">&times;</button>
        </div>
        <div class="modal-body" style="padding: 24px;">
            <div style="border: 1px dashed var(--border-color); border-radius: 8px; padding: 20px; background-color: rgba(255, 255, 255, 0.01);">
                <div style="text-align: center; margin-bottom: 20px; border-bottom: 1.5px double var(--border-color); padding-bottom: 15px;">
                    <h4 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--primary);">SURAT PENGAJUAN CUTI PEGAWAI</h4>
                    <p style="margin: 5px 0 0 0; font-size: 0.8rem; color: var(--text-secondary);" id="detail-cuti-no"></p>
                </div>
                
                <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary); width: 180px;">Tgl. Pengajuan</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary); width: 15px;">:</td>
                        <td style="padding: 8px 0; font-weight: 500;" id="detail-cuti-tanggal"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Pemohon</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0; font-weight: 600;" id="detail-cuti-pemohon"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Jenis Cuti / Urgensi</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0;" id="detail-cuti-urgensi"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Waktu Pelaksanaan</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0; font-weight: 500;" id="detail-cuti-waktu"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Durasi</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0; font-weight: 600; color: var(--warning);" id="detail-cuti-durasi"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Alamat selama Cuti</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0;" id="detail-cuti-alamat"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Alasan/Kepentingan</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0;" id="detail-cuti-kepentingan"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Penanggung Jawab (PJ)</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0; font-weight: 500;" id="detail-cuti-pj"></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: var(--text-secondary);">Status Pengajuan</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0;" id="detail-cuti-status"></td>
                    </tr>
                </table>
            </div>
            
            <!-- Workflow Status Step Progress -->
            <div style="margin-top: 24px; padding: 15px; background: rgba(0,0,0,0.15); border: 1px solid var(--border-color); border-radius: 8px;">
                <h5 style="margin: 0 0 15px 0; font-size: 0.85rem; font-weight: 600; color: var(--text-primary);">Alur Persetujuan</h5>
                <div style="display: flex; justify-content: space-between; align-items: center; position: relative;">
                    <!-- Line in background -->
                    <div style="position: absolute; top: 15px; left: 10%; right: 10%; height: 2px; background-color: var(--border-color); z-index: 1;" id="workflow-line"></div>
                    
                    <!-- Step 1: Diajukan -->
                    <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 30%;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background-color: var(--primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;" id="step-diajukan-circle">✓</div>
                        <div style="font-size: 0.75rem; margin-top: 6px; font-weight: 600; color: var(--text-primary);">Diajukan</div>
                        <div style="font-size: 0.65rem; color: var(--text-secondary);">Oleh Pemohon</div>
                    </div>
                    
                    <!-- Step 2: Penanggung Jawab -->
                    <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 30%;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background-color: var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); font-weight: bold; font-size: 0.8rem;" id="step-pj-circle">2</div>
                        <div style="font-size: 0.75rem; margin-top: 6px; font-weight: 600; color: var(--text-secondary);" id="step-pj-label">Persetujuan PJ</div>
                        <div style="font-size: 0.65rem; color: var(--text-secondary);" id="step-pj-sub">Belum Diperiksa</div>
                    </div>
                    
                    <!-- Step 3: HRD -->
                    <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 30%;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background-color: var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); font-weight: bold; font-size: 0.8rem;" id="step-hrd-circle">3</div>
                        <div style="font-size: 0.75rem; margin-top: 6px; font-weight: 600; color: var(--text-secondary);" id="step-hrd-label">Persetujuan HRD</div>
                        <div style="font-size: 0.65rem; color: var(--text-secondary);" id="step-hrd-sub">Belum Diperiksa</div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeCutiModal()" style="font-size: 0.85rem; padding: 8px 16px;">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-cetak-cuti-modal" onclick="printCutiFromModal()" style="font-size: 0.85rem; padding: 8px 16px;">🖨️ Cetak Surat</button>
            </div>
        </div>
    </div>
</div>

<!-- Tukar Jaga Detail Modal -->
<div class="modal-overlay" id="tukar-detail-modal">
    <div class="modal-box" style="max-width: 600px;">
        <div class="modal-header">
            <span>Detail Pengajuan Tukar Jaga</span>
            <button class="modal-close" onclick="closeTukarModal()">&times;</button>
        </div>
        <div class="modal-body" style="padding: 24px;">
            <div style="border: 1px dashed var(--border-color); border-radius: 8px; padding: 20px; background-color: rgba(255, 255, 255, 0.01);">
                <div style="text-align: center; margin-bottom: 20px; border-bottom: 1.5px double var(--border-color); padding-bottom: 15px;">
                    <h4 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--primary);">FORMULIR PENGAJUAN TUKAR JAGA</h4>
                    <p style="margin: 5px 0 0 0; font-size: 0.8rem; color: var(--text-secondary);" id="detail-tukar-no"></p>
                </div>
                
                <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary); width: 180px;">Tgl. Pengajuan</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary); width: 15px;">:</td>
                        <td style="padding: 8px 0; font-weight: 500;" id="detail-tukar-tanggal"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Pihak I (Pemohon)</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0; font-weight: 600;" id="detail-tukar-pihak1"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Pihak II (Rekan Pengganti)</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0; font-weight: 600;" id="detail-tukar-pihak2"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Tanggal Swap (Tukar)</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0; font-weight: 600; color: var(--warning);" id="detail-tukar-waktu"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Alasan Tukar Jaga</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0;" id="detail-tukar-alasan"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 8px 0; color: var(--text-secondary);">Penanggung Jawab (PJ)</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0; font-weight: 500;" id="detail-tukar-pj"></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: var(--text-secondary);">Status Pengajuan</td>
                        <td style="padding: 8px 4px; color: var(--text-secondary);">:</td>
                        <td style="padding: 8px 0;" id="detail-tukar-status"></td>
                    </tr>
                </table>
            </div>
            
            <!-- Workflow Status Step Progress -->
            <div style="margin-top: 24px; padding: 15px; background: rgba(0,0,0,0.15); border: 1px solid var(--border-color); border-radius: 8px;">
                <h5 style="margin: 0 0 15px 0; font-size: 0.85rem; font-weight: 600; color: var(--text-primary);">Alur Persetujuan</h5>
                <div style="display: flex; justify-content: space-between; align-items: center; position: relative;">
                    <!-- Line in background -->
                    <div style="position: absolute; top: 15px; left: 10%; right: 10%; height: 2px; background-color: var(--border-color); z-index: 1;" id="tukar-workflow-line"></div>
                    
                    <!-- Step 1: Diajukan -->
                    <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 30%;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background-color: var(--primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;" id="tukar-step-diajukan-circle">✓</div>
                        <div style="font-size: 0.75rem; margin-top: 6px; font-weight: 600; color: var(--text-primary);">Diajukan</div>
                        <div style="font-size: 0.65rem; color: var(--text-secondary);">Oleh Pemohon</div>
                    </div>
                    
                    <!-- Step 2: Penanggung Jawab -->
                    <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 30%;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background-color: var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); font-weight: bold; font-size: 0.8rem;" id="tukar-step-pj-circle">2</div>
                        <div style="font-size: 0.75rem; margin-top: 6px; font-weight: 600; color: var(--text-secondary);" id="tukar-step-pj-label">Persetujuan PJ</div>
                        <div style="font-size: 0.65rem; color: var(--text-secondary);" id="tukar-step-pj-sub">Belum Diperiksa</div>
                    </div>
                    
                    <!-- Step 3: HRD -->
                    <div style="display: flex; flex-direction: column; align-items: center; z-index: 2; width: 30%;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background-color: var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-secondary); font-weight: bold; font-size: 0.8rem;" id="tukar-step-hrd-circle">3</div>
                        <div style="font-size: 0.75rem; margin-top: 6px; font-weight: 600; color: var(--text-secondary);" id="tukar-step-hrd-label">Persetujuan HRD</div>
                        <div style="font-size: 0.65rem; color: var(--text-secondary);" id="tukar-step-hrd-sub">Belum Diperiksa</div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeTukarModal()" style="font-size: 0.85rem; padding: 8px 16px;">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-cetak-tukar-modal" onclick="printTukarFromModal()" style="font-size: 0.85rem; padding: 8px 16px;">🖨️ Cetak Surat</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentCutiNo = null;
    let currentTukarNo = null;

    function openCutiDetail(no, tanggal, pemohon, urgensi, waktu, durasi, alamat, kepentingan, pj, status) {
        currentCutiNo = no;
        document.getElementById('detail-cuti-no').innerText = 'Nomor Dokumen: ' + no;
        document.getElementById('detail-cuti-tanggal').innerText = tanggal;
        document.getElementById('detail-cuti-pemohon').innerText = pemohon;
        document.getElementById('detail-cuti-urgensi').innerHTML = `<span class="badge badge-info">${urgensi}</span>`;
        document.getElementById('detail-cuti-waktu').innerText = waktu;
        document.getElementById('detail-cuti-durasi').innerText = durasi;
        document.getElementById('detail-cuti-alamat').innerText = alamat || '-';
        document.getElementById('detail-cuti-kepentingan').innerText = kepentingan || '-';
        document.getElementById('detail-cuti-pj').innerText = pj;
        
        let statusBadge = '';
        if (status === 'Proses Pengajuan') {
            statusBadge = '<span class="badge badge-warning">Proses PJ</span>';
        } else if (status === 'Disetujui PJ') {
            statusBadge = '<span class="badge badge-info">Disetujui PJ</span>';
        } else if (status === 'Disetujui') {
            statusBadge = '<span class="badge badge-success">Disetujui HRD</span>';
        } else {
            statusBadge = '<span class="badge badge-danger">Ditolak</span>';
        }
        document.getElementById('detail-cuti-status').innerHTML = statusBadge;

        // Reset workflow colors
        const line = document.getElementById('workflow-line');
        const cPj = document.getElementById('step-pj-circle');
        const lPj = document.getElementById('step-pj-label');
        const sPj = document.getElementById('step-pj-sub');
        const cHrd = document.getElementById('step-hrd-circle');
        const lHrd = document.getElementById('step-hrd-label');
        const sHrd = document.getElementById('step-hrd-sub');

        line.style.background = 'var(--border-color)';
        
        // Default state
        cPj.style.backgroundColor = 'var(--border-color)';
        cPj.style.color = 'var(--text-secondary)';
        cPj.innerText = '2';
        lPj.style.color = 'var(--text-secondary)';
        lPj.innerText = 'Persetujuan PJ';
        sPj.innerText = 'Belum Diperiksa';
        sPj.style.color = 'var(--text-secondary)';

        cHrd.style.backgroundColor = 'var(--border-color)';
        cHrd.style.color = 'var(--text-secondary)';
        cHrd.innerText = '3';
        lHrd.style.color = 'var(--text-secondary)';
        lHrd.innerText = 'Persetujuan HRD';
        sHrd.innerText = 'Belum Diperiksa';
        sHrd.style.color = 'var(--text-secondary)';

        if (status === 'Proses Pengajuan') {
            cPj.style.backgroundColor = 'var(--warning)';
            cPj.style.color = 'white';
            cPj.innerText = '●';
            lPj.style.color = 'var(--warning)';
            sPj.innerText = 'Menunggu Persetujuan';
            sPj.style.color = 'var(--warning)';
        } else if (status === 'Disetujui PJ') {
            line.style.background = 'linear-gradient(to right, var(--success) 50%, var(--border-color) 50%)';
            
            cPj.style.backgroundColor = 'var(--success)';
            cPj.style.color = 'white';
            cPj.innerText = '✓';
            lPj.style.color = 'var(--success)';
            sPj.innerText = 'Disetujui';
            sPj.style.color = 'var(--success)';

            cHrd.style.backgroundColor = 'var(--warning)';
            cHrd.style.color = 'white';
            cHrd.innerText = '●';
            lHrd.style.color = 'var(--warning)';
            sHrd.innerText = 'Menunggu Persetujuan';
            sHrd.style.color = 'var(--warning)';
        } else if (status === 'Disetujui') {
            line.style.background = 'var(--success)';
            
            cPj.style.backgroundColor = 'var(--success)';
            cPj.style.color = 'white';
            cPj.innerText = '✓';
            lPj.style.color = 'var(--success)';
            sPj.innerText = 'Disetujui';
            sPj.style.color = 'var(--success)';

            cHrd.style.backgroundColor = 'var(--success)';
            cHrd.style.color = 'white';
            cHrd.innerText = '✓';
            lHrd.style.color = 'var(--success)';
            sHrd.innerText = 'Disetujui';
            sHrd.style.color = 'var(--success)';
        } else if (status === 'Ditolak') {
            cPj.style.backgroundColor = 'var(--danger)';
            cPj.style.color = 'white';
            cPj.innerText = '✗';
            lPj.style.color = 'var(--danger)';
            sPj.innerText = 'Ditolak';
            sPj.style.color = 'var(--danger)';

            cHrd.style.backgroundColor = 'var(--danger)';
            cHrd.style.color = 'white';
            cHrd.innerText = '✗';
            lHrd.style.color = 'var(--danger)';
            sHrd.innerText = 'Ditolak';
            sHrd.style.color = 'var(--danger)';
        }

        document.getElementById('cuti-detail-modal').classList.add('active');
    }

    function closeCutiModal() {
        document.getElementById('cuti-detail-modal').classList.remove('active');
    }

    function printCutiFromModal() {
        if (currentCutiNo) {
            window.open(`{{ url('/cuti') }}/${encodeURIComponent(currentCutiNo)}/cetak`, '_blank');
        }
    }

    function openTukarDetail(no, tanggal, pihak1, pihak2, waktu, alasan, pj, status) {
        currentTukarNo = no;
        document.getElementById('detail-tukar-no').innerText = 'Nomor Dokumen: ' + no;
        document.getElementById('detail-tukar-tanggal').innerText = tanggal;
        document.getElementById('detail-tukar-pihak1').innerText = pihak1;
        document.getElementById('detail-tukar-pihak2').innerText = pihak2;
        document.getElementById('detail-tukar-waktu').innerText = waktu;
        document.getElementById('detail-tukar-alasan').innerText = alasan || '-';
        document.getElementById('detail-tukar-pj').innerText = pj;
        
        let statusBadge = '';
        if (status === 'Proses Pengajuan') {
            statusBadge = '<span class="badge badge-warning">Proses PJ</span>';
        } else if (status === 'Disetujui PJ') {
            statusBadge = '<span class="badge badge-info">Disetujui PJ</span>';
        } else if (status === 'Disetujui') {
            statusBadge = '<span class="badge badge-success">Disetujui HRD</span>';
        } else {
            statusBadge = '<span class="badge badge-danger">Ditolak</span>';
        }
        document.getElementById('detail-tukar-status').innerHTML = statusBadge;

        // Reset workflow colors
        const line = document.getElementById('tukar-workflow-line');
        const cPj = document.getElementById('tukar-step-pj-circle');
        const lPj = document.getElementById('tukar-step-pj-label');
        const sPj = document.getElementById('tukar-step-pj-sub');
        const cHrd = document.getElementById('tukar-step-hrd-circle');
        const lHrd = document.getElementById('tukar-step-hrd-label');
        const sHrd = document.getElementById('tukar-step-hrd-sub');

        line.style.background = 'var(--border-color)';
        
        // Default state
        cPj.style.backgroundColor = 'var(--border-color)';
        cPj.style.color = 'var(--text-secondary)';
        cPj.innerText = '2';
        lPj.style.color = 'var(--text-secondary)';
        lPj.innerText = 'Persetujuan PJ';
        sPj.innerText = 'Belum Diperiksa';
        sPj.style.color = 'var(--text-secondary)';

        cHrd.style.backgroundColor = 'var(--border-color)';
        cHrd.style.color = 'var(--text-secondary)';
        cHrd.innerText = '3';
        lHrd.style.color = 'var(--text-secondary)';
        lHrd.innerText = 'Persetujuan HRD';
        sHrd.innerText = 'Belum Diperiksa';
        sHrd.style.color = 'var(--text-secondary)';

        if (status === 'Proses Pengajuan') {
            cPj.style.backgroundColor = 'var(--warning)';
            cPj.style.color = 'white';
            cPj.innerText = '●';
            lPj.style.color = 'var(--warning)';
            sPj.innerText = 'Menunggu Persetujuan';
            sPj.style.color = 'var(--warning)';
        } else if (status === 'Disetujui PJ') {
            line.style.background = 'linear-gradient(to right, var(--success) 50%, var(--border-color) 50%)';
            
            cPj.style.backgroundColor = 'var(--success)';
            cPj.style.color = 'white';
            cPj.innerText = '✓';
            lPj.style.color = 'var(--success)';
            sPj.innerText = 'Disetujui';
            sPj.style.color = 'var(--success)';

            cHrd.style.backgroundColor = 'var(--warning)';
            cHrd.style.color = 'white';
            cHrd.innerText = '●';
            lHrd.style.color = 'var(--warning)';
            sHrd.innerText = 'Menunggu Persetujuan';
            sHrd.style.color = 'var(--warning)';
        } else if (status === 'Disetujui') {
            line.style.background = 'var(--success)';
            
            cPj.style.backgroundColor = 'var(--success)';
            cPj.style.color = 'white';
            cPj.innerText = '✓';
            lPj.style.color = 'var(--success)';
            sPj.innerText = 'Disetujui';
            sPj.style.color = 'var(--success)';

            cHrd.style.backgroundColor = 'var(--success)';
            cHrd.style.color = 'white';
            cHrd.innerText = '✓';
            lHrd.style.color = 'var(--success)';
            sHrd.innerText = 'Disetujui';
            sHrd.style.color = 'var(--success)';
        } else if (status === 'Ditolak') {
            cPj.style.backgroundColor = 'var(--danger)';
            cPj.style.color = 'white';
            cPj.innerText = '✗';
            lPj.style.color = 'var(--danger)';
            sPj.innerText = 'Ditolak';
            sPj.style.color = 'var(--danger)';

            cHrd.style.backgroundColor = 'var(--danger)';
            cHrd.style.color = 'white';
            cHrd.innerText = '✗';
            lHrd.style.color = 'var(--danger)';
            sHrd.innerText = 'Ditolak';
            sHrd.style.color = 'var(--danger)';
        }

        document.getElementById('tukar-detail-modal').classList.add('active');
    }

    function closeTukarModal() {
        document.getElementById('tukar-detail-modal').classList.remove('active');
    }

    function printTukarFromModal() {
        if (currentTukarNo) {
            window.open(`{{ url('/tukar-jaga') }}/${encodeURIComponent(currentTukarNo)}/cetak`, '_blank');
        }
    }
</script>
@endsection

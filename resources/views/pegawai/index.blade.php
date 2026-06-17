@extends('layouts.app')

@section('title', 'Data Kepegawaian')
@section('header_title', 'Manajemen Kepegawaian')

@section('styles')
<style>
    .index-card {
        padding: 24px 0 0 0; 
        overflow: hidden;
    }
    .index-card-header {
        display: flex; 
        flex-wrap: wrap; 
        gap: 16px; 
        align-items: center; 
        justify-content: space-between; 
        padding: 0 24px 20px 24px; 
        border-bottom: 1px solid var(--border-color);
    }
    .index-pagination {
        padding: 20px 24px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    @media (max-width: 768px) {
        .index-card {
            padding: 16px 0 0 0;
        }
        .index-card-header {
            padding: 0 16px 16px 16px;
        }
        .index-pagination {
            padding: 16px;
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')
<div class="card index-card">
    <div class="index-card-header">
        <div>
            <h3 style="font-weight: 600; font-size: 1.15rem;">Daftar Seluruh Pegawai</h3>
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 4px;">Kelola profil, jabatan, dan data administratif staf medis/non-medis.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center; width: 100%; max-width: 500px; justify-content: flex-end;">
            <!-- Search Form -->
            <form action="{{ route('pegawai.index') }}" method="GET" style="display: flex; gap: 8px; flex-grow: 1; max-width: 300px;">
                <input type="text" name="search" class="form-control" placeholder="Cari NIK, nama, jabatan..." value="{{ $search }}" style="padding: 8px 12px; font-size: 0.9rem;">
                <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0 16px;">Cari</button>
            </form>

            @if(session('khanza_user')['role'] === 'admin')
                <a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-sm" style="flex-shrink: 0; padding: 8px 16px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; display: inline; vertical-align: middle;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Pegawai
                </a>
            @endif
        </div>
    </div>

    <div class="table-container">
        @if($pegawai->isEmpty())
            <div style="padding: 40px; text-align: center; color: var(--text-secondary);">
                Data pegawai tidak ditemukan.
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Pegawai</th>
                        <th>Kontak & Alamat</th>
                        <th>Jabatan & Dept</th>
                        <th>Pendidikan</th>
                        <th>Cuti Diambil</th>
                        <th>Status</th>
                        @if(session('khanza_user')['role'] === 'admin')
                            <th style="text-align: right;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawai as $p)
                        <tr>
                            <td style="font-weight: 600; color: var(--primary);">{{ $p->nik }}</td>
                            <td>
                                <div style="font-weight: 600;">{{ $p->nama }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $p->jk === 'Pria' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </td>
                            <td>
                                <div style="font-size: 0.9rem;">{{ $p->alamat }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $p->kota }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 500;">{{ $p->jbtn }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">Dept: {{ $p->nama_departemen }} ({{ $p->departemen }})</div>
                            </td>
                            <td>
                                <div style="font-size: 0.9rem;">{{ $p->pendidikan }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">Mulai Kerja: {{ \Carbon\Carbon::parse($p->mulai_kerja)->translatedFormat('d M Y') }}</div>
                            </td>
                            <td style="font-weight: 600; text-align: center;">
                                <span style="font-size: 1rem; color: var(--warning);">{{ $p->cuti_diambil }}</span> Hari
                            </td>
                            <td>
                                @if($p->stts_aktif === 'AKTIF')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($p->stts_aktif === 'CUTI')
                                    <span class="badge badge-warning">Cuti</span>
                                @elseif($p->stts_aktif === 'KELUAR')
                                    <span class="badge badge-danger">Keluar</span>
                                @else
                                    <span class="badge badge-info">Tenaga Luar</span>
                                @endif
                            </td>
                            @if(session('khanza_user')['role'] === 'admin')
                                <td style="text-align: right;">
                                    <div class="action-buttons" style="justify-content: flex-end;">
                                        <a href="{{ route('pegawai.edit', $p->nik) }}" class="btn-icon edit" title="Edit Data">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('pegawai.destroy', $p->nik) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pegawai ini? Akun user terkait juga akan dihapus.');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon delete" title="Hapus Pegawai" style="border: none;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Pagination -->
    @if($pegawai->hasPages())
        <div class="index-pagination">
            <div style="font-size: 0.85rem; color: var(--text-secondary);">
                Menampilkan {{ $pegawai->firstItem() }} - {{ $pegawai->lastItem() }} dari {{ $pegawai->total() }} pegawai
            </div>
            <div style="display: flex; gap: 8px;">
                @if($pegawai->onFirstPage())
                    <span class="btn btn-secondary btn-sm" style="cursor: not-allowed; opacity: 0.5;">Sebelumnya</span>
                @else
                    <a href="{{ $pegawai->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                @endif

                @if($pegawai->hasMorePages())
                    <a href="{{ $pegawai->nextPageUrl() }}" class="btn btn-secondary btn-sm">Selanjutnya</a>
                @else
                    <span class="btn btn-secondary btn-sm" style="cursor: not-allowed; opacity: 0.5;">Selanjutnya</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

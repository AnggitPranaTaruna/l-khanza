@extends('layouts.app')

@section('title', 'Pengajuan Tukar Jaga')
@section('header_title', '::[ Pengajuan Tukar Jaga Pegawai ]::')

@section('content')
<!-- Input Form Container -->
<div class="khanza-desktop-form">
    <div class="form-header">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        .: Input Data Pengajuan Tukar Jaga
    </div>
    
    <div class="form-grid">
        <!-- Kolom Kiri -->
        <div>
            <div class="form-desktop-row">
                <label for="no_pengajuan">No.Pengajuan :</label>
                <input type="text" id="no_pengajuan" class="input-desktop" style="width: 160px;" value="{{ $defaultNo }}" readonly>
                
                <label style="width: auto; margin-left: 15px; margin-right: 5px;" for="tanggal">Tgl. Pengajuan :</label>
                <input type="date" id="tanggal" class="input-desktop" style="width: 130px;" value="{{ \Carbon\Carbon::now()->toDateString() }}">
            </div>
            
            <div class="form-desktop-row">
                <label>Pihak I (Pemohon) :</label>
                <input type="text" id="nik_pemohon" class="input-desktop" style="width: 80px;" placeholder="NIK" value="{{ !$isAdmin ? $user['username'] : '' }}" readonly>
                <input type="text" id="nama_pemohon" class="input-desktop" style="width: 180px;" placeholder="Nama Pegawai" value="{{ !$isAdmin ? $user['name'] : '' }}" readonly>
                @if($isAdmin)
                    <button type="button" class="btn-lookup" id="btn-lookup-pemohon" onclick="openEmployeeLookup('pemohon')">📎</button>
                @else
                    <button type="button" class="btn-lookup" disabled>📎</button>
                @endif
            </div>

            <div class="form-desktop-row">
                <label>Pihak II (Tukar) :</label>
                <input type="text" id="nik_tukar" class="input-desktop" style="width: 80px;" placeholder="NIK Tukar" readonly>
                <input type="text" id="nama_tukar" class="input-desktop" style="width: 180px;" placeholder="Nama Rekan Jaga" readonly>
                <button type="button" class="btn-lookup" onclick="openEmployeeLookup('tukar')">📎</button>
            </div>
            
            <div class="form-desktop-row">
                <label>P.J.Terkait :</label>
                <input type="text" id="nik_pj" class="input-desktop" style="width: 80px;" placeholder="NIK PJ" readonly>
                <input type="text" id="nama_pj" class="input-desktop" style="width: 180px;" placeholder="Nama Penanggung Jawab" readonly>
                <button type="button" class="btn-lookup" onclick="openEmployeeLookup('pj')">📎</button>
            </div>
        </div>
        
        <!-- Kolom Kanan -->
        <div>
            <div class="form-desktop-row">
                <label for="tanggal_tukar_mulai">Mulai Tukar :</label>
                <input type="date" id="tanggal_tukar_mulai" class="input-desktop" style="width: 130px;" value="{{ \Carbon\Carbon::now()->addDay()->toDateString() }}">
                
                <label style="width: auto; margin-left: 15px; margin-right: 5px;" for="tanggal_tukar_akhir">s/d Tanggal :</label>
                <input type="date" id="tanggal_tukar_akhir" class="input-desktop" style="width: 130px;" value="{{ \Carbon\Carbon::now()->addDay()->toDateString() }}">
            </div>

            <div class="form-desktop-row">
                <label>Bidang :</label>
                <input type="text" id="bidang" class="input-desktop" style="width: 130px;" placeholder="Bidang" value="{{ !$isAdmin ? $user['permissions']['bidang_pemohon'] ?? '' : '' }}" readonly>
                
                <label style="width: auto; margin-left: 15px; margin-right: 5px;">Departemen :</label>
                <input type="text" id="departemen" class="input-desktop" style="width: 100px;" placeholder="Dept" value="{{ !$isAdmin ? $user['permissions']['departemen_pemohon'] ?? '' : '' }}" readonly>
            </div>
            
            <div class="form-desktop-row" style="align-items: flex-start;">
                <label for="alasan">Alasan Tukar :</label>
                <textarea id="alasan" class="textarea-desktop" style="width: 280px; height: 65px;" placeholder="Masukkan alasan penukaran jadwal jaga"></textarea>
            </div>

            <div class="form-desktop-row" style="margin-top: 5px;">
                <label for="status">Status :</label>
                    <input type="text" id="status" class="input-desktop" style="width: 180px;" value="Proses Pengajuan" readonly>
            </div>
        </div>
    </div>
    
    <!-- Action Button Bar -->
    <div class="desktop-button-bar">
        <button type="button" id="btn-simpan" class="btn-desktop" onclick="saveData()">
            <span style="font-size: 0.95rem;">💾</span> Simpan
        </button>
        <button type="button" id="btn-baru" class="btn-desktop" onclick="resetForm()">
            <span style="font-size: 0.95rem;">📄</span> Baru
        </button>
        <button type="button" id="btn-hapus" class="btn-desktop" onclick="deleteData()" disabled>
            <span style="font-size: 0.95rem;">🗑️</span> Hapus
        </button>
        <button type="button" id="btn-ganti" class="btn-desktop" onclick="updateData()" disabled>
            <span style="font-size: 0.95rem;">🔄</span> Ganti
        </button>
        <button type="button" id="btn-cetak" class="btn-desktop" onclick="printData()" disabled>
            <span style="font-size: 0.95rem;">🖨️</span> Cetak
        </button>
        <a href="{{ route('dashboard') }}" class="btn-desktop">
            <span style="font-size: 0.95rem;">🚪</span> Keluar
        </a>
    </div>

    <!-- Search and Date Filter Bar -->
    <div class="desktop-filter-bar">
        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                <input type="checkbox" id="check-tanggal" onchange="toggleDateFilter()" {{ $use_date_filter === 'true' ? 'checked' : '' }}>
                Tgl. Pengajuan :
            </label>
            <input type="date" id="filter-tgl-awal" class="input-desktop" style="width: 130px;" value="{{ $tgl_awal }}" {{ $use_date_filter === 'true' ? '' : 'disabled' }}>
            <span style="color: var(--text-secondary);">s/d</span>
            <input type="date" id="filter-tgl-akhir" class="input-desktop" style="width: 130px;" value="{{ $tgl_akhir }}" {{ $use_date_filter === 'true' ? '' : 'disabled' }}>
        </div>
        
        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 500;">Key Word :</label>
            <input type="text" id="search-keyword" class="input-desktop" style="width: 180px;" placeholder="Cari..." value="{{ $search }}">
            <button type="button" class="btn-desktop" onclick="performFilter()" style="padding: 4px 12px; height: 32px;">Cari</button>
            <button type="button" class="btn-desktop" onclick="showAll()" style="padding: 4px 12px; height: 32px;">All</button>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="table-container">
        <table class="data-table" id="tukar-table">
            <thead>
                <tr>
                    <th>No. Pengajuan</th>
                    <th>Tgl. Pengajuan</th>
                    <th>Pihak I (Pemohon)</th>
                    <th>Pihak II (Tukar)</th>
                    <th>Mulai Tukar</th>
                    <th>Akhir Tukar</th>
                    <th>Alasan</th>
                    <th>P.J. Terkait</th>
                    <th>Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tukar-table-body">
                @if($tukarList->isEmpty())
                    <tr>
                        <td colspan="10" style="padding: 40px; text-align: center; color: var(--text-secondary);">
                            Belum ada pengajuan tukar jaga yang tercatat.
                        </td>
                    </tr>
                @else
                    @foreach($tukarList as $t)
                        <tr onclick="selectRow(this)" 
                            data-no_pengajuan="{{ $t->no_pengajuan }}"
                            data-tanggal="{{ $t->tanggal }}"
                            data-tanggal_tukar_mulai="{{ $t->tanggal_tukar_mulai }}"
                            data-tanggal_tukar_akhir="{{ $t->tanggal_tukar_akhir }}"
                            data-nik_pemohon="{{ $t->nik_pemohon }}"
                            data-nama_pemohon="{{ $t->nama_pemohon }}"
                            data-bidang="{{ $t->bidang_pemohon }}"
                            data-departemen="{{ $t->departemen_pemohon }}"
                            data-nik_tukar="{{ $t->nik_tukar }}"
                            data-nama_tukar="{{ $t->nama_tukar }}"
                            data-nik_pj="{{ $t->nik_pj }}"
                            data-nama_pj="{{ $t->nama_pj }}"
                            data-alasan="{{ $t->alasan }}"
                            data-status="{{ $t->status }}">
                            <td style="font-weight: 600; color: var(--primary);">{{ $t->no_pengajuan }}</td>
                            <td>{{ $t->tanggal }}</td>
                            <td style="font-weight: 500;">{{ $t->nama_pemohon }} ({{ $t->nik_pemohon }})</td>
                            <td style="font-weight: 500;">{{ $t->nama_tukar }} ({{ $t->nik_tukar }})</td>
                            <td><strong>{{ \Carbon\Carbon::parse($t->tanggal_tukar_mulai)->translatedFormat('d-m-Y') }}</strong></td>
                            <td><strong>{{ \Carbon\Carbon::parse($t->tanggal_tukar_akhir)->translatedFormat('d-m-Y') }}</strong></td>
                            <td>{{ $t->alasan }}</td>
                            <td>{{ $t->nama_pj }}</td>
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
                             <td style="text-align: center;">
                                <div style="display: flex; flex-direction: column; gap: 6px; align-items: center; justify-content: center;">
                                    @php
                                        $loggedInNik = session('khanza_user')['username'];
                                    @endphp
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); showDetailTukar('{{ $t->no_pengajuan }}', '{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d F Y') }}', '{{ $t->nama_pemohon }} ({{ $t->nik_pemohon }})', '{{ $t->nama_tukar }} ({{ $t->nik_tukar }})', '{{ \Carbon\Carbon::parse($t->tanggal_tukar_mulai)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($t->tanggal_tukar_akhir)->translatedFormat('d M Y') }}', '{{ addslashes($t->alasan) }}', '{{ $t->nama_pj }} ({{ $t->nik_pj }})', '{{ $t->status }}')" style="padding: 4px 10px; font-size: 0.75rem; width: 100%; justify-content: center; background-color: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer;">
                                        👁️ Detail
                                    </button>
                                    @if($isAdmin && $t->status === 'Disetujui PJ')
                                        <div style="display: flex; gap: 4px; justify-content: center;">
                                            <form action="{{ route('tukar-jaga.approve', $t->no_pengajuan) }}" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--success); padding: 4px 8px; font-size: 0.75rem;">Setujui (HRD)</button>
                                            </form>
                                            <form action="{{ route('tukar-jaga.reject', $t->no_pengajuan) }}" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 0.75rem;">Tolak (HRD)</button>
                                            </form>
                                        </div>
                                    @elseif(!$isAdmin && $t->nik_pj === $loggedInNik && $t->status === 'Proses Pengajuan')
                                        <div style="display: flex; gap: 4px; justify-content: center;">
                                            <form action="{{ route('tukar-jaga.approve-pj', $t->no_pengajuan) }}" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--primary); padding: 4px 8px; font-size: 0.75rem;">Setujui (PJ)</button>
                                            </form>
                                            <form action="{{ route('tukar-jaga.reject-pj', $t->no_pengajuan) }}" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 0.75rem;">Tolak (PJ)</button>
                                            </form>
                                        </div>
                                    @endif
                                    <button type="button" class="btn btn-primary btn-sm" onclick="event.stopPropagation(); printDirect('{{ $t->no_pengajuan }}')" style="padding: 4px 10px; font-size: 0.75rem; width: 100%; justify-content: center;">
                                        🖨️ Cetak
                                    </button>
                                </div>
                             </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
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

<!-- Employee Lookup Modal -->
<div class="modal-overlay" id="lookup-modal">
    <div class="modal-box">
        <div class="modal-header">
            <span id="lookup-modal-title">Pilih Pegawai</span>
            <button class="modal-close" onclick="closeLookupModal()">&times;</button>
        </div>
        <div style="padding: 16px 20px 0 20px; display: flex; gap: 8px;">
            <input type="text" id="lookup-search" class="form-control" placeholder="Cari NIK atau nama..." style="padding: 8px 12px; font-size: 0.9rem;" oninput="fetchLookupEmployees()">
        </div>
        <div class="modal-body">
            <div class="table-container">
                <table class="data-table" style="font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Bidang</th>
                            <th>Departemen</th>
                        </tr>
                    </thead>
                    <tbody id="lookup-table-body">
                        <!-- Data populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let lookupType = 'pemohon'; // 'pemohon', 'tukar', 'pj'
    let selectedNoPengajuan = null;
    const isAdmin = {{ $isAdmin ? 'true' : 'false' }};
    const loggedInNik = '{{ session('khanza_user')['username'] }}';

    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggal');

        // Fetch new number when main date changes
        tanggalInput.addEventListener('change', function() {
            if (!selectedNoPengajuan) {
                fetchNextNoPengajuan(this.value);
            }
        });
    });

    // Toggle date search filter fields
    function toggleDateFilter() {
        const check = document.getElementById('check-tanggal').checked;
        document.getElementById('filter-tgl-awal').disabled = !check;
        document.getElementById('filter-tgl-akhir').disabled = !check;
    }

    // Fetch next sequential document number
    function fetchNextNoPengajuan(date) {
        fetch(`{{ url('/tukar-jaga/new-no') }}?tanggal=${date}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('no_pengajuan').value = data.no_pengajuan;
            })
            .catch(err => console.error(err));
    }

    // Modal lookup controls
    function openEmployeeLookup(type) {
        lookupType = type;
        let title = 'Pilih Pegawai Pemohon';
        if (type === 'tukar') title = 'Pilih Pihak II (Rekan Tukar Jaga)';
        if (type === 'pj') title = 'Pilih Penanggung Jawab (PJ)';
        
        document.getElementById('lookup-modal-title').innerText = title;
        document.getElementById('lookup-search').value = '';
        document.getElementById('lookup-modal').classList.add('active');
        fetchLookupEmployees();
    }

    function closeLookupModal() {
        document.getElementById('lookup-modal').classList.remove('active');
    }

    function fetchLookupEmployees() {
        const keyword = document.getElementById('lookup-search').value;
        fetch(`{{ url('/tukar-jaga/employees') }}?search=${keyword}`)
            .then(res => res.json())
            .then(res => {
                const tbody = document.getElementById('lookup-table-body');
                tbody.innerHTML = '';
                
                if (res.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Pegawai tidak ditemukan.</td></tr>';
                    return;
                }

                res.data.forEach(emp => {
                    const tr = document.createElement('tr');
                    tr.style.cursor = 'pointer';
                    tr.innerHTML = `
                        <td style="font-weight:600;">${emp.nik}</td>
                        <td>${emp.nama}</td>
                        <td>${emp.jbtn}</td>
                        <td>${emp.bidang}</td>
                        <td>${emp.departemen}</td>
                    `;
                    tr.onclick = function() {
                        selectEmployee(emp.nik, emp.nama, emp.bidang, emp.departemen);
                    };
                    tbody.appendChild(tr);
                });
            })
            .catch(err => console.error(err));
    }

    function selectEmployee(nik, nama, bidang, departemen) {
        if (lookupType === 'pemohon') {
            document.getElementById('nik_pemohon').value = nik;
            document.getElementById('nama_pemohon').value = nama;
            document.getElementById('bidang').value = bidang;
            document.getElementById('departemen').value = departemen;
        } else if (lookupType === 'tukar') {
            document.getElementById('nik_tukar').value = nik;
            document.getElementById('nama_tukar').value = nama;
        } else {
            document.getElementById('nik_pj').value = nik;
            document.getElementById('nama_pj').value = nama;
        }
        closeLookupModal();
    }

    // Select row in the main list table
    function selectRow(trElement) {
        document.querySelectorAll('#tukar-table-body tr').forEach(r => r.classList.remove('selected-row'));
        trElement.classList.add('selected-row');

        // Populate fields
        selectedNoPengajuan = trElement.getAttribute('data-no_pengajuan');
        document.getElementById('no_pengajuan').value = selectedNoPengajuan;
        document.getElementById('tanggal').value = trElement.getAttribute('data-tanggal');
        document.getElementById('tanggal_tukar_mulai').value = trElement.getAttribute('data-tanggal_tukar_mulai');
        document.getElementById('tanggal_tukar_akhir').value = trElement.getAttribute('data-tanggal_tukar_akhir');
        document.getElementById('nik_pemohon').value = trElement.getAttribute('data-nik_pemohon');
        document.getElementById('nama_pemohon').value = trElement.getAttribute('data-nama_pemohon');
        document.getElementById('bidang').value = trElement.getAttribute('data-bidang');
        document.getElementById('departemen').value = trElement.getAttribute('data-departemen');
        document.getElementById('nik_tukar').value = trElement.getAttribute('data-nik_tukar');
        document.getElementById('nama_tukar').value = trElement.getAttribute('data-nama_tukar');
        document.getElementById('nik_pj').value = trElement.getAttribute('data-nik_pj');
        document.getElementById('nama_pj').value = trElement.getAttribute('data-nama_pj');
        document.getElementById('alasan').value = trElement.getAttribute('data-alasan');

        const statusEl = document.getElementById('status');
        statusEl.value = trElement.getAttribute('data-status');

        // Configure buttons
        document.getElementById('btn-simpan').disabled = true;
        document.getElementById('btn-ganti').disabled = false;
        document.getElementById('btn-hapus').disabled = false;
        document.getElementById('btn-cetak').disabled = false;
    }

    // Reset Form (Baru button)
    function resetForm() {
        selectedNoPengajuan = null;
        document.querySelectorAll('#tukar-table-body tr').forEach(r => r.classList.remove('selected-row'));

        const currentDate = '{{ \Carbon\Carbon::now()->toDateString() }}';
        document.getElementById('tanggal').value = currentDate;
        document.getElementById('tanggal_tukar_mulai').value = '{{ \Carbon\Carbon::now()->addDay()->toDateString() }}';
        document.getElementById('tanggal_tukar_akhir').value = '{{ \Carbon\Carbon::now()->addDay()->toDateString() }}';
        fetchNextNoPengajuan(currentDate);

        if (isAdmin) {
            document.getElementById('nik_pemohon').value = '';
            document.getElementById('nama_pemohon').value = '';
            document.getElementById('bidang').value = '';
            document.getElementById('departemen').value = '';
        } else {
            document.getElementById('nik_pemohon').value = '{{ $user['username'] }}';
            document.getElementById('nama_pemohon').value = '{{ $user['name'] }}';
            document.getElementById('bidang').value = '{{ $user['permissions']['bidang_pemohon'] ?? '' }}';
            document.getElementById('departemen').value = '{{ $user['permissions']['departemen_pemohon'] ?? '' }}';
        }
        
        document.getElementById('nik_tukar').value = '';
        document.getElementById('nama_tukar').value = '';
        document.getElementById('nik_pj').value = '';
        document.getElementById('nama_pj').value = '';
        document.getElementById('alasan').value = '';
        
        const statusEl = document.getElementById('status');
        statusEl.value = 'Proses Pengajuan';

        document.getElementById('btn-simpan').disabled = false;
        document.getElementById('btn-ganti').disabled = true;
        document.getElementById('btn-hapus').disabled = true;
        document.getElementById('btn-cetak').disabled = true;
    }

    // CRUD AJAX requests
    function saveData() {
        const payload = getFormPayload();
        if (!validatePayload(payload)) return;

        const btnSimpan = document.getElementById('btn-simpan');
        btnSimpan.disabled = true;

        fetch(`{{ url('/tukar-jaga') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(res => {
            btnSimpan.disabled = false;
            if (res.status === 'success' || res.message) {
                const msg = res.message || 'Pengajuan tukar jaga berhasil disimpan.';
                if (confirm(msg + '\n\nApakah Anda ingin langsung mencetak surat pengajuan ini?')) {
                    printDirect(payload.no_pengajuan);
                }
                resetForm();
                refreshTable();
            } else {
                alert('Gagal menyimpan: ' + (res.message || 'Kesalahan Server'));
            }
        })
        .catch(err => {
            console.error(err);
            btnSimpan.disabled = false;
            alert('Kesalahan jaringan atau server saat menyimpan data.');
        });
    }

    function updateData() {
        if (!selectedNoPengajuan) return;
        const payload = getFormPayload();
        if (!validatePayload(payload)) return;

        const btnGanti = document.getElementById('btn-ganti');
        btnGanti.disabled = true;

        fetch(`{{ url('/tukar-jaga') }}/${selectedNoPengajuan}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(res => {
            btnGanti.disabled = false;
            if (res.status === 'success' || res.message) {
                const msg = res.message || 'Pengajuan tukar jaga berhasil diubah.';
                if (confirm(msg + '\n\nApakah Anda ingin mencetak surat pengajuan ini?')) {
                    printDirect(selectedNoPengajuan);
                }
                resetForm();
                refreshTable();
            } else {
                alert('Gagal mengubah: ' + (res.message || 'Kesalahan Server'));
            }
        })
        .catch(err => {
            console.error(err);
            btnGanti.disabled = false;
            alert('Kesalahan jaringan atau server saat mengubah data.');
        });
    }

    function deleteData() {
        if (!selectedNoPengajuan) return;
        if (!confirm(`Apakah Anda yakin ingin menghapus pengajuan ${selectedNoPengajuan}?`)) return;

        const btnHapus = document.getElementById('btn-hapus');
        btnHapus.disabled = true;

        fetch(`{{ url('/tukar-jaga') }}/${selectedNoPengajuan}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(res => {
            btnHapus.disabled = false;
            if (res.status === 'success' || res.message) {
                alert(res.message || 'Pengajuan tukar jaga berhasil dihapus.');
                resetForm();
                refreshTable();
            } else {
                alert('Gagal menghapus: ' + (res.message || 'Kesalahan Server'));
            }
        })
        .catch(err => {
            console.error(err);
            btnHapus.disabled = false;
            alert('Kesalahan jaringan atau server saat menghapus data.');
        });
    }

    // Gather payload from inputs
    function getFormPayload() {
        return {
            no_pengajuan: document.getElementById('no_pengajuan').value,
            tanggal: document.getElementById('tanggal').value,
            tanggal_tukar_mulai: document.getElementById('tanggal_tukar_mulai').value,
            tanggal_tukar_akhir: document.getElementById('tanggal_tukar_akhir').value,
            nik_pemohon: document.getElementById('nik_pemohon').value,
            nik_tukar: document.getElementById('nik_tukar').value,
            alasan: document.getElementById('alasan').value,
            nik_pj: document.getElementById('nik_pj').value,
            status: document.getElementById('status').value || document.getElementById('status').getAttribute('value')
        };
    }

    function validatePayload(payload) {
        if (!payload.no_pengajuan) { alert('Nomor pengajuan tidak boleh kosong.'); return false; }
        if (!payload.nik_pemohon) { alert('Pihak I (Pemohon) harus dipilih.'); return false; }
        if (!payload.nik_tukar) { alert('Pihak II (Rekan Tukar) harus dipilih.'); return false; }
        if (payload.nik_pemohon === payload.nik_tukar) { alert('Pemohon dan Rekan Tukar tidak boleh orang yang sama.'); return false; }
        if (!payload.tanggal_tukar_mulai) { alert('Tanggal mulai tukar tidak boleh kosong.'); return false; }
        if (!payload.tanggal_tukar_akhir) { alert('Tanggal akhir tukar tidak boleh kosong.'); return false; }
        if (new Date(payload.tanggal_tukar_mulai) > new Date(payload.tanggal_tukar_akhir)) { alert('Tanggal akhir tukar tidak boleh sebelum tanggal mulai.'); return false; }
        if (!payload.nik_pj) { alert('Penanggung Jawab (PJ) harus dipilih.'); return false; }
        if (!payload.alasan) { alert('Alasan penukaran jadwal jaga tidak boleh kosong.'); return false; }
        return true;
    }

    // Print actions
    function printData() {
        if (!selectedNoPengajuan) return;
        printDirect(selectedNoPengajuan);
    }

    function printDirect(noPengajuan) {
        const url = `{{ url('/tukar-jaga') }}/${encodeURIComponent(noPengajuan)}/cetak`;
        window.open(url, '_blank');
    }

    // Refresh table content via AJAX
    function refreshTable() {
        const useDateFilter = document.getElementById('check-tanggal').checked ? 'true' : 'false';
        const tglAwal = document.getElementById('filter-tgl-awal').value;
        const tglAkhir = document.getElementById('filter-tgl-akhir').value;
        const keyword = document.getElementById('search-keyword').value;

        fetch(`{{ url('/tukar-jaga') }}?use_date_filter=${useDateFilter}&tgl_awal=${tglAwal}&tgl_akhir=${tglAkhir}&search=${keyword}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('tukar-table-body');
            tbody.innerHTML = '';

            if (res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" style="padding: 40px; text-align: center; color: var(--text-secondary);">Tidak ada data pengajuan tukar jaga yang ditemukan.</td></tr>';
                return;
            }

            res.data.forEach(t => {
                const tr = document.createElement('tr');
                tr.onclick = function() { selectRow(tr); };
                
                tr.setAttribute('data-no_pengajuan', t.no_pengajuan);
                tr.setAttribute('data-tanggal', t.tanggal);
                tr.setAttribute('data-tanggal_tukar_mulai', t.tanggal_tukar_mulai);
                tr.setAttribute('data-tanggal_tukar_akhir', t.tanggal_tukar_akhir);
                tr.setAttribute('data-nik_pemohon', t.nik_pemohon);
                tr.setAttribute('data-nama_pemohon', t.nama_pemohon);
                tr.setAttribute('data-bidang', t.bidang_pemohon || '');
                tr.setAttribute('data-departemen', t.departemen_pemohon || '');
                tr.setAttribute('data-nik_tukar', t.nik_tukar);
                tr.setAttribute('data-nama_tukar', t.nama_tukar || '');
                tr.setAttribute('data-nik_pj', t.nik_pj);
                tr.setAttribute('data-nama_pj', t.nama_pj || '');
                tr.setAttribute('data-alasan', t.alasan);
                tr.setAttribute('data-status', t.status);

                let badgeStatus = 'badge-warning';
                let statusLabel = 'Proses PJ';
                if (t.status === 'Disetujui PJ') {
                    badgeStatus = 'badge-info';
                    statusLabel = 'Disetujui PJ';
                } else if (t.status === 'Disetujui') {
                    badgeStatus = 'badge-success';
                    statusLabel = 'Disetujui HRD';
                } else if (t.status === 'Ditolak') {
                    badgeStatus = 'badge-danger';
                    statusLabel = 'Ditolak';
                }

                // Format tanggal tukar
                const partsMulai = t.tanggal_tukar_mulai.split('-');
                const formattedTglTukarMulai = `${partsMulai[2]}-${partsMulai[1]}-${partsMulai[0]}`;
                const partsAkhir = t.tanggal_tukar_akhir.split('-');
                const formattedTglTukarAkhir = `${partsAkhir[2]}-${partsAkhir[1]}-${partsAkhir[0]}`;

                let actionHtml = '';
                if (isAdmin && t.status === 'Disetujui PJ') {
                    actionHtml = `
                        <div style="display: flex; gap: 4px; justify-content: center;">
                            <form action="{{ url('/tukar-jaga') }}/${encodeURIComponent(t.no_pengajuan)}/approve" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--success); padding: 4px 8px; font-size: 0.75rem;">Setujui (HRD)</button>
                            </form>
                            <form action="{{ url('/tukar-jaga') }}/${encodeURIComponent(t.no_pengajuan)}/reject" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 0.75rem;">Tolak (HRD)</button>
                            </form>
                        </div>
                    `;
                } else if (!isAdmin && t.nik_pj === loggedInNik && t.status === 'Proses Pengajuan') {
                    actionHtml = `
                        <div style="display: flex; gap: 4px; justify-content: center;">
                            <form action="{{ url('/tukar-jaga') }}/${encodeURIComponent(t.no_pengajuan)}/approve-pj" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-primary btn-sm" style="background-color: var(--primary); padding: 4px 8px; font-size: 0.75rem;">Setujui (PJ)</button>
                            </form>
                            <form action="{{ url('/tukar-jaga') }}/${encodeURIComponent(t.no_pengajuan)}/reject-pj" method="POST" style="display:inline;" onclick="event.stopPropagation();">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 0.75rem;">Tolak (PJ)</button>
                            </form>
                        </div>
                    `;
                }

                tr.innerHTML = `
                    <td style="font-weight: 600; color: var(--primary);">${t.no_pengajuan}</td>
                    <td>${t.tanggal}</td>
                    <td style="font-weight: 500;">${t.nama_pemohon} (${t.nik_pemohon})</td>
                    <td style="font-weight: 500;">${t.nama_tukar} (${t.nik_tukar})</td>
                    <td><strong>${formattedTglTukarMulai}</strong></td>
                    <td><strong>${formattedTglTukarAkhir}</strong></td>
                    <td>${t.alasan}</td>
                    <td>${t.nama_pj || ''}</td>
                    <td><span class="badge ${badgeStatus}">${statusLabel}</span></td>
                    <td style="text-align: center;">
                        <div style="display: flex; flex-direction: column; gap: 6px; align-items: center; justify-content: center;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); showDetailTukar('${t.no_pengajuan}', '${t.tanggal}', '${t.nama_pemohon} (${t.nik_pemohon})', '${t.nama_tukar} (${t.nik_tukar})', '${formattedTglTukarMulai} s.d. ${formattedTglTukarAkhir}', '${t.alasan.replace(/'/g, "\\'")}', '${t.nama_pj} (${t.nik_pj})', '${t.status}')" style="padding: 4px 10px; font-size: 0.75rem; width: 100%; justify-content: center; background-color: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer;">
                                👁️ Detail
                            </button>
                            ${actionHtml}
                            <button type="button" class="btn btn-primary btn-sm" onclick="event.stopPropagation(); printDirect('${t.no_pengajuan}')" style="padding: 4px 10px; font-size: 0.75rem; width: 100%; justify-content: center;">
                                🖨️ Cetak
                            </button>
                        </div>
                    </td>
                `;

                tbody.appendChild(tr);
            });
        })
        .catch(err => console.error(err));
    }

    // Run filters
    function performFilter() {
        refreshTable();
    }

    function showAll() {
        document.getElementById('check-tanggal').checked = false;
        toggleDateFilter();
        document.getElementById('search-keyword').value = '';
        refreshTable();
    }

    let currentTukarNo = null;

    function showDetailTukar(no, tanggal, pihak1, pihak2, waktu, alasan, pj, status) {
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
            printDirect(currentTukarNo);
        }
    }
</script>
@endsection

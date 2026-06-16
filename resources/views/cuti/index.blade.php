@extends('layouts.app')

@section('title', 'Pengajuan Cuti Pegawai')
@section('header_title', '::[ Pengajuan Cuti Pegawai ]::')

@section('styles')
<style>
    /* Styling khusus menyerupai Desktop Swing dengan sentuhan modern */
    .khanza-desktop-form {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0;
        margin-bottom: 24px;
        box-shadow: var(--shadow);
    }
    
    .form-header {
        background-color: rgba(255, 255, 255, 0.02);
        padding: 10px 16px;
        font-weight: 600;
        font-size: 0.9rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        padding: 20px 24px;
    }

    .form-desktop-row {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        gap: 8px;
        flex-wrap: wrap;
    }

    .form-desktop-row label {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-primary);
        width: 110px;
        flex-shrink: 0;
        text-align: left;
    }

    .input-desktop {
        background-color: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--border-color);
        border-radius: 20px; /* rounded pill style seperti di desktop */
        padding: 6px 14px;
        color: var(--text-primary);
        font-family: inherit;
        font-size: 0.85rem;
        outline: none;
        transition: var(--transition);
        height: 32px;
    }

    .input-desktop:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.15);
    }

    .input-desktop:disabled {
        opacity: 0.65;
        background-color: rgba(255, 255, 255, 0.02);
        cursor: not-allowed;
    }

    .select-desktop {
        background-color: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 6px 14px;
        color: var(--text-primary);
        font-family: inherit;
        font-size: 0.85rem;
        outline: none;
        height: 32px;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;
        padding-right: 32px;
    }

    .select-desktop:focus {
        border-color: var(--primary);
    }

    .textarea-desktop {
        background-color: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 8px 14px;
        color: var(--text-primary);
        font-family: inherit;
        font-size: 0.85rem;
        outline: none;
        resize: none;
        transition: var(--transition);
    }

    .textarea-desktop:focus {
        border-color: var(--primary);
    }

    .btn-lookup {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-primary);
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9rem;
    }

    .btn-lookup:hover:not(:disabled) {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .btn-lookup:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Desktop Button Bar */
    .desktop-button-bar {
        display: flex;
        gap: 10px;
        padding: 12px 24px;
        background-color: rgba(255, 255, 255, 0.01);
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        flex-wrap: wrap;
    }

    .btn-desktop {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 4px;
        border: 1px solid var(--border-color);
        background: linear-gradient(to bottom, rgba(255,255,255,0.05), rgba(255,255,255,0.01));
        color: var(--text-primary);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        height: 32px;
    }

    .btn-desktop:hover:not(:disabled) {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .btn-desktop:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* Filter Bar */
    .desktop-filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 24px;
        background-color: rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid var(--border-color);
        flex-wrap: wrap;
        gap: 16px;
    }

    /* Table Highlight */
    .data-table tbody tr.selected-row {
        background-color: rgba(14, 165, 233, 0.15) !important;
        border-left: 3px solid var(--primary);
    }

    /* Modal Styling */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: var(--transition);
    }

    .modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .modal-box {
        background-color: #1e293b;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        width: 100%;
        max-width: 600px;
        box-shadow: var(--shadow-lg);
        display: flex;
        flex-direction: column;
        max-height: 80vh;
    }

    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-secondary);
        font-size: 1.25rem;
        cursor: pointer;
    }

    .modal-body {
        padding: 20px;
        overflow-y: auto;
        flex-grow: 1;
    }
</style>
@endsection

@section('content')
<!-- Input Form Container -->
<div class="khanza-desktop-form">
    <div class="form-header">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        .: Input Data
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
                <label>Diajukan Oleh :</label>
                <input type="text" id="nik" class="input-desktop" style="width: 80px;" placeholder="NIK" value="{{ !$isAdmin ? $user['username'] : '' }}" readonly>
                <input type="text" id="nama" class="input-desktop" style="width: 180px;" placeholder="Nama Pegawai" value="{{ !$isAdmin ? $user['name'] : '' }}" readonly>
                @if($isAdmin)
                    <button type="button" class="btn-lookup" id="btn-lookup-pemohon" onclick="openEmployeeLookup('pemohon')">📎</button>
                @else
                    <button type="button" class="btn-lookup" disabled>📎</button>
                @endif
            </div>
            
            <div class="form-desktop-row">
                <label>P.J.Terkait :</label>
                <input type="text" id="nik_pj" class="input-desktop" style="width: 80px;" placeholder="NIK PJ" readonly>
                <input type="text" id="nama_pj" class="input-desktop" style="width: 180px;" placeholder="Nama Penanggung Jawab" readonly>
                <button type="button" class="btn-lookup" onclick="openEmployeeLookup('pj')">📎</button>
            </div>
            
            <div class="form-desktop-row">
                <label>Tanggal Cuti :</label>
                <input type="date" id="tanggal_awal" class="input-desktop" style="width: 130px;">
                <span style="color: var(--text-secondary);">s/d</span>
                <input type="date" id="tanggal_akhir" class="input-desktop" style="width: 130px;">
                
                <label style="width: auto; margin-left: 10px; margin-right: 5px;" for="jumlah">Jml. Cuti :</label>
                <input type="text" id="jumlah" class="input-desktop" style="width: 40px; text-align: center;" value="0" readonly>
                <span style="font-size: 0.85rem; color: var(--text-secondary);">Hari</span>
            </div>
            
            <div class="form-desktop-row">
                <label for="status">Status :</label>
                @if($isAdmin)
                    <select id="status" class="select-desktop" style="width: 180px;">
                        <option value="Proses Pengajuan">Proses Pengajuan</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                @else
                    <input type="text" id="status" class="input-desktop" style="width: 180px;" value="Proses Pengajuan" readonly>
                @endif
            </div>
        </div>
        
        <!-- Kolom Kanan -->
        <div>
            <div class="form-desktop-row">
                <label for="urgensi">Jenis Cuti :</label>
                <select id="urgensi" class="select-desktop" style="width: 180px;">
                    <option value="Tahunan">Tahunan</option>
                    <option value="Besar">Besar</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Bersalin">Bersalin</option>
                    <option value="Alasan Penting">Alasan Penting</option>
                    <option value="Keterangan Lainnya">Keterangan Lainnya</option>
                </select>
            </div>
            
            <div class="form-desktop-row">
                <label>Bidang :</label>
                <input type="text" id="bidang" class="input-desktop" style="width: 130px;" placeholder="Bidang" value="{{ !$isAdmin ? $user['permissions']['bidang_pemohon'] ?? '' : '' }}" readonly>
                
                <label style="width: auto; margin-left: 15px; margin-right: 5px;">Departemen :</label>
                <input type="text" id="departemen" class="input-desktop" style="width: 100px;" placeholder="Dept" value="{{ !$isAdmin ? $user['permissions']['departemen_pemohon'] ?? '' : '' }}" readonly>
            </div>
            
            <div class="form-desktop-row" style="align-items: flex-start;">
                <label for="alamat">Alamat Tujuan :</label>
                <textarea id="alamat" class="textarea-desktop" style="width: 280px; height: 75px;" placeholder="Masukkan alamat tujuan cuti"></textarea>
            </div>
            
            <div class="form-desktop-row" style="margin-top: 15px;">
                <label for="kepentingan">Kepentingan Cuti :</label>
                <input type="text" id="kepentingan" class="input-desktop" style="width: 280px;" placeholder="Keperluan/Kepentingan cuti">
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
        <table class="data-table" id="cuti-table">
            <thead>
                <tr>
                    <th>No. Pengajuan</th>
                    <th>Tgl. Pengajuan</th>
                    <th>NIK</th>
                    <th>Nama Pemohon</th>
                    <th>Jenis Cuti</th>
                    <th>Tgl. Mulai</th>
                    <th>Tgl. Selesai</th>
                    <th>Jml. Cuti</th>
                    <th>P.J. Terkait</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="cuti-table-body">
                @if($cutiList->isEmpty())
                    <tr>
                        <td colspan="10" style="padding: 40px; text-align: center; color: var(--text-secondary);">
                            Belum ada pengajuan cuti yang tercatat.
                        </td>
                    </tr>
                @else
                    @foreach($cutiList as $c)
                        <tr onclick="selectRow(this)" 
                            data-no_pengajuan="{{ $c->no_pengajuan }}"
                            data-tanggal="{{ $c->tanggal }}"
                            data-nik="{{ $c->nik }}"
                            data-nama="{{ $c->nama_pemohon }}"
                            data-bidang="{{ $c->bidang_pemohon }}"
                            data-departemen="{{ $c->departemen_pemohon }}"
                            data-nik_pj="{{ $c->nik_pj }}"
                            data-nama_pj="{{ $c->nama_pj }}"
                            data-tanggal_awal="{{ $c->tanggal_awal }}"
                            data-tanggal_akhir="{{ $c->tanggal_akhir }}"
                            data-jumlah="{{ $c->jumlah }}"
                            data-urgensi="{{ $c->urgensi }}"
                            data-alamat="{{ $c->alamat }}"
                            data-kepentingan="{{ $c->kepentingan }}"
                            data-status="{{ $c->status }}">
                            <td style="font-weight: 600; color: var(--primary);">{{ $c->no_pengajuan }}</td>
                            <td>{{ $c->tanggal }}</td>
                            <td>{{ $c->nik }}</td>
                            <td style="font-weight: 500;">{{ $c->nama_pemohon }}</td>
                            <td><span class="badge badge-info">{{ $c->urgensi }}</span></td>
                            <td>{{ $c->tanggal_awal }}</td>
                            <td>{{ $c->tanggal_akhir }}</td>
                            <td style="text-align: center; font-weight: 600;">{{ $c->jumlah }} Hari</td>
                            <td>{{ $c->nama_pj }}</td>
                            <td>
                                @if($c->status === 'Proses Pengajuan')
                                    <span class="badge badge-warning">Proses</span>
                                @elseif($c->status === 'Disetujui')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
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
    let lookupType = 'pemohon'; // 'pemohon' or 'pj'
    let selectedNoPengajuan = null;
    const isAdmin = {{ $isAdmin ? 'true' : 'false' }};

    document.addEventListener('DOMContentLoaded', function() {
        const tglAwalInput = document.getElementById('tanggal_awal');
        const tglAkhirInput = document.getElementById('tanggal_akhir');
        const tanggalInput = document.getElementById('tanggal');

        // Auto calculate days when date inputs change
        tglAwalInput.addEventListener('change', calculateDays);
        tglAkhirInput.addEventListener('change', calculateDays);

        // Fetch new number when main date changes (for new entries)
        tanggalInput.addEventListener('change', function() {
            if (!selectedNoPengajuan) {
                fetchNextNoPengajuan(this.value);
            }
        });
    });

    // Calculate duration in days
    function calculateDays() {
        const tglAwal = document.getElementById('tanggal_awal').value;
        const tglAkhir = document.getElementById('tanggal_akhir').value;
        const jumlahInput = document.getElementById('jumlah');

        if (tglAwal && tglAkhir) {
            const date1 = new Date(tglAwal);
            const date2 = new Date(tglAkhir);
            
            date1.setHours(0,0,0,0);
            date2.setHours(0,0,0,0);

            if (date2 >= date1) {
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                jumlahInput.value = diffDays;
            } else {
                jumlahInput.value = 0;
            }
        } else {
            jumlahInput.value = 0;
        }
    }

    // Toggle date search filter fields
    function toggleDateFilter() {
        const check = document.getElementById('check-tanggal').checked;
        document.getElementById('filter-tgl-awal').disabled = !check;
        document.getElementById('filter-tgl-akhir').disabled = !check;
    }

    // Fetch next sequential document number
    function fetchNextNoPengajuan(date) {
        fetch(`{{ url('/cuti/new-no') }}?tanggal=${date}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('no_pengajuan').value = data.no_pengajuan;
            })
            .catch(err => console.error(err));
    }

    // Modal lookup controls
    function openEmployeeLookup(type) {
        lookupType = type;
        document.getElementById('lookup-modal-title').innerText = type === 'pemohon' ? 'Pilih Pegawai Pemohon' : 'Pilih Penanggung Jawab (PJ)';
        document.getElementById('lookup-search').value = '';
        document.getElementById('lookup-modal').classList.add('active');
        fetchLookupEmployees();
    }

    function closeLookupModal() {
        document.getElementById('lookup-modal').classList.remove('active');
    }

    function fetchLookupEmployees() {
        const keyword = document.getElementById('lookup-search').value;
        fetch(`{{ url('/cuti/employees') }}?search=${keyword}`)
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
            document.getElementById('nik').value = nik;
            document.getElementById('nama').value = nama;
            document.getElementById('bidang').value = bidang;
            document.getElementById('departemen').value = departemen;
        } else {
            document.getElementById('nik_pj').value = nik;
            document.getElementById('nama_pj').value = nama;
        }
        closeLookupModal();
    }

    // Select row in the main list table
    function selectRow(trElement) {
        // Remove selection highlight from all rows
        document.querySelectorAll('#cuti-table-body tr').forEach(r => r.classList.remove('selected-row'));
        
        // Add highlight to selected row
        trElement.classList.add('selected-row');

        // Populate fields
        selectedNoPengajuan = trElement.getAttribute('data-no_pengajuan');
        document.getElementById('no_pengajuan').value = selectedNoPengajuan;
        document.getElementById('tanggal').value = trElement.getAttribute('data-tanggal');
        document.getElementById('nik').value = trElement.getAttribute('data-nik');
        document.getElementById('nama').value = trElement.getAttribute('data-nama');
        document.getElementById('bidang').value = trElement.getAttribute('data-bidang');
        document.getElementById('departemen').value = trElement.getAttribute('data-departemen');
        document.getElementById('nik_pj').value = trElement.getAttribute('data-nik_pj');
        document.getElementById('nama_pj').value = trElement.getAttribute('data-nama_pj');
        document.getElementById('tanggal_awal').value = trElement.getAttribute('data-tanggal_awal');
        document.getElementById('tanggal_akhir').value = trElement.getAttribute('data-tanggal_akhir');
        document.getElementById('jumlah').value = trElement.getAttribute('data-jumlah');
        document.getElementById('urgensi').value = trElement.getAttribute('data-urgensi');
        document.getElementById('alamat').value = trElement.getAttribute('data-alamat');
        document.getElementById('kepentingan').value = trElement.getAttribute('data-kepentingan');

        const statusEl = document.getElementById('status');
        if (statusEl.tagName === 'SELECT') {
            statusEl.value = trElement.getAttribute('data-status');
        } else {
            statusEl.value = trElement.getAttribute('data-status');
        }

        // Configure buttons
        document.getElementById('btn-simpan').disabled = true;
        document.getElementById('btn-ganti').disabled = false;
        document.getElementById('btn-hapus').disabled = false;
    }

    // Reset Form (Baru button)
    function resetForm() {
        selectedNoPengajuan = null;
        document.querySelectorAll('#cuti-table-body tr').forEach(r => r.classList.remove('selected-row'));

        const currentDate = '{{ \Carbon\Carbon::now()->toDateString() }}';
        document.getElementById('tanggal').value = currentDate;
        fetchNextNoPengajuan(currentDate);

        if (isAdmin) {
            document.getElementById('nik').value = '';
            document.getElementById('nama').value = '';
            document.getElementById('bidang').value = '';
            document.getElementById('departemen').value = '';
        }
        
        document.getElementById('nik_pj').value = '';
        document.getElementById('nama_pj').value = '';
        document.getElementById('tanggal_awal').value = '';
        document.getElementById('tanggal_akhir').value = '';
        document.getElementById('jumlah').value = 0;
        document.getElementById('urgensi').value = 'Tahunan';
        document.getElementById('alamat').value = '';
        document.getElementById('kepentingan').value = '';
        
        const statusEl = document.getElementById('status');
        if (statusEl.tagName === 'SELECT') {
            statusEl.value = 'Proses Pengajuan';
        } else {
            statusEl.value = 'Proses Pengajuan';
        }

        document.getElementById('btn-simpan').disabled = false;
        document.getElementById('btn-ganti').disabled = true;
        document.getElementById('btn-hapus').disabled = true;
    }

    // CRUD AJAX requests
    function saveData() {
        const payload = getFormPayload();
        if (!validatePayload(payload)) return;

        fetch(`{{ url('/cuti') }}`, {
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
            if (res.status === 'success') {
                alert(res.message);
                resetForm();
                refreshTable();
            } else {
                alert('Gagal menyimpan: ' + (res.message || 'Kesalahan Server'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Kesalahan jaringan atau server saat menyimpan data.');
        });
    }

    function updateData() {
        if (!selectedNoPengajuan) return;
        const payload = getFormPayload();
        if (!validatePayload(payload)) return;

        fetch(`{{ url('/cuti') }}/${selectedNoPengajuan}`, {
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
            if (res.status === 'success') {
                alert(res.message);
                resetForm();
                refreshTable();
            } else {
                alert('Gagal mengubah: ' + (res.message || 'Kesalahan Server'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Kesalahan jaringan atau server saat mengubah data.');
        });
    }

    function deleteData() {
        if (!selectedNoPengajuan) return;
        if (!confirm(`Apakah Anda yakin ingin menghapus pengajuan ${selectedNoPengajuan}?`)) return;

        fetch(`{{ url('/cuti') }}/${selectedNoPengajuan}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                alert(res.message);
                resetForm();
                refreshTable();
            } else {
                alert('Gagal menghapus: ' + (res.message || 'Kesalahan Server'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Kesalahan jaringan atau server saat menghapus data.');
        });
    }

    // Gather payload from inputs
    function getFormPayload() {
        return {
            no_pengajuan: document.getElementById('no_pengajuan').value,
            tanggal: document.getElementById('tanggal').value,
            nik: document.getElementById('nik').value,
            nik_pj: document.getElementById('nik_pj').value,
            tanggal_awal: document.getElementById('tanggal_awal').value,
            tanggal_akhir: document.getElementById('tanggal_akhir').value,
            urgensi: document.getElementById('urgensi').value,
            alamat: document.getElementById('alamat').value,
            kepentingan: document.getElementById('kepentingan').value,
            status: document.getElementById('status').value || document.getElementById('status').getAttribute('value')
        };
    }

    function validatePayload(payload) {
        if (!payload.no_pengajuan) { alert('Nomor pengajuan tidak boleh kosong.'); return false; }
        if (!payload.nik) { alert('Pegawai pengaju harus dipilih.'); return false; }
        if (!payload.nik_pj) { alert('Penanggung Jawab (PJ) harus dipilih.'); return false; }
        if (!payload.tanggal_awal) { alert('Tanggal awal cuti tidak boleh kosong.'); return false; }
        if (!payload.tanggal_akhir) { alert('Tanggal akhir cuti tidak boleh kosong.'); return false; }
        if (new Date(payload.tanggal_akhir) < new Date(payload.tanggal_awal)) {
            alert('Tanggal akhir tidak boleh mendahului tanggal awal.');
            return false;
        }
        if (!payload.alamat) { alert('Alamat tujuan tidak boleh kosong.'); return false; }
        if (!payload.kepentingan) { alert('Kepentingan cuti tidak boleh kosong.'); return false; }
        return true;
    }

    // Refresh table content via AJAX
    function refreshTable() {
        const useDateFilter = document.getElementById('check-tanggal').checked ? 'true' : 'false';
        const tglAwal = document.getElementById('filter-tgl-awal').value;
        const tglAkhir = document.getElementById('filter-tgl-akhir').value;
        const keyword = document.getElementById('search-keyword').value;

        fetch(`{{ url('/cuti') }}?use_date_filter=${useDateFilter}&tgl_awal=${tglAwal}&tgl_akhir=${tglAkhir}&search=${keyword}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('cuti-table-body');
            tbody.innerHTML = '';

            if (res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" style="padding: 40px; text-align: center; color: var(--text-secondary);">Tidak ada data pengajuan cuti yang ditemukan.</td></tr>';
                return;
            }

            res.data.forEach(c => {
                const tr = document.createElement('tr');
                tr.onclick = function() { selectRow(tr); };
                
                tr.setAttribute('data-no_pengajuan', c.no_pengajuan);
                tr.setAttribute('data-tanggal', c.tanggal);
                tr.setAttribute('data-nik', c.nik);
                tr.setAttribute('data-nama', c.nama_pemohon);
                tr.setAttribute('data-bidang', c.bidang_pemohon || '');
                tr.setAttribute('data-departemen', c.departemen_pemohon || '');
                tr.setAttribute('data-nik_pj', c.nik_pj);
                tr.setAttribute('data-nama_pj', c.nama_pj || '');
                tr.setAttribute('data-tanggal_awal', c.tanggal_awal);
                tr.setAttribute('data-tanggal_akhir', c.tanggal_akhir);
                tr.setAttribute('data-jumlah', c.jumlah);
                tr.setAttribute('data-urgensi', c.urgensi);
                tr.setAttribute('data-alamat', c.alamat);
                tr.setAttribute('data-kepentingan', c.kepentingan);
                tr.setAttribute('data-status', c.status);

                let badgeStatus = 'badge-warning';
                let statusLabel = 'Proses';
                if (c.status === 'Disetujui') {
                    badgeStatus = 'badge-success';
                    statusLabel = 'Disetujui';
                } else if (c.status === 'Ditolak') {
                    badgeStatus = 'badge-danger';
                    statusLabel = 'Ditolak';
                }

                tr.innerHTML = `
                    <td style="font-weight: 600; color: var(--primary);">${c.no_pengajuan}</td>
                    <td>${c.tanggal}</td>
                    <td>${c.nik}</td>
                    <td style="font-weight: 500;">${c.nama_pemohon}</td>
                    <td><span class="badge badge-info">${c.urgensi}</span></td>
                    <td>${c.tanggal_awal}</td>
                    <td>${c.tanggal_akhir}</td>
                    <td style="text-align: center; font-weight: 600;">${c.jumlah} Hari</td>
                    <td>${c.nama_pj || ''}</td>
                    <td><span class="badge ${badgeStatus}">${statusLabel}</span></td>
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
</script>
@endsection

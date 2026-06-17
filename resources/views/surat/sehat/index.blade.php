@extends('layouts.app')

@section('title', 'Surat Keterangan Sehat')
@section('header_title', 'Surat Keterangan Sehat')

@section('content')
<!-- Input Form Container -->
<div class="khanza-desktop-form">
    <div class="form-header">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <span>Input Data Surat Keterangan Sehat</span>
    </div>

    <form id="surat-sehat-form">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">
        <div class="form-grid">
            <!-- Left Column -->
            <div>
                <div class="form-desktop-row">
                    <label for="no_rawat">No. Rawat</label>
                    <div style="display: flex; gap: 6px; flex-grow: 1; min-width: 0;">
                        <input type="text" id="no_rawat" name="no_rawat" class="input-desktop" placeholder="Nomor Rawat Pasien" style="flex-grow: 1; min-width: 0;" required readonly>
                        <button type="button" class="btn-lookup" onclick="openLookupModal()" title="Cari Registrasi Pasien">📎</button>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label>No. R.M. & Pasien</label>
                    <div style="display: flex; gap: 8px; flex-grow: 1;">
                        <input type="text" id="no_rkm_medis" class="input-desktop" style="width: 90px;" placeholder="No. R.M." disabled>
                        <input type="text" id="nm_pasien" class="input-desktop" style="flex-grow: 1;" placeholder="Nama Pasien" disabled>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="no_surat">No. Surat</label>
                    <input type="text" id="no_surat" name="no_surat" class="input-desktop" style="flex-grow: 1;" placeholder="Auto" required>
                </div>

                <div class="form-desktop-row">
                    <label for="tanggalsurat">Tanggal Surat</label>
                    <input type="date" id="tanggalsurat" name="tanggalsurat" class="input-desktop" style="width: 150px;" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-desktop-row">
                    <label for="keperluan">Keperluan</label>
                    <input type="text" id="keperluan" name="keperluan" class="input-desktop" style="flex-grow: 1;" placeholder="Keperluan surat dibuat" required>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div class="form-desktop-row">
                    <label for="berat">Berat Badan (BB)</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="berat" name="berat" class="input-desktop" style="width: 80px; text-align: right;" maxlength="3" required>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Kg</span>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="tinggi">Tinggi Badan (TB)</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="tinggi" name="tinggi" class="input-desktop" style="width: 80px; text-align: right;" maxlength="3" required>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Cm</span>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="tensi">Tekanan Darah</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="tensi" name="tensi" class="input-desktop" style="width: 100px; text-align: center;" placeholder="120/80" maxlength="8" required>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">mmHg</span>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="suhu">Suhu Badan</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="suhu" name="suhu" class="input-desktop" style="width: 80px; text-align: right;" placeholder="36.5" maxlength="4" required>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">°C</span>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="butawarna">Buta Warna</label>
                    <select id="butawarna" name="butawarna" class="select-desktop" style="width: 120px;" required>
                        <option value="Tidak">Tidak</option>
                        <option value="Ya">Ya</option>
                    </select>
                </div>

                <div class="form-desktop-row">
                    <label for="kesimpulan">Kesimpulan</label>
                    <select id="kesimpulan" name="kesimpulan" class="select-desktop" style="width: 150px;" required>
                        <option value="Sehat">Sehat</option>
                        <option value="Tidak Sehat">Tidak Sehat</option>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <!-- Swing Action Button Bar -->
    <div class="desktop-button-bar">
        <button id="btn-save" class="btn-desktop" onclick="saveRecord()">
            💾 Simpan
        </button>
        <button id="btn-new" class="btn-desktop" onclick="resetForm()">
            📝 Baru
        </button>
        <button id="btn-delete" class="btn-desktop" onclick="deleteRecord()" disabled>
            ❌ Hapus
        </button>
        <button id="btn-edit" class="btn-desktop" onclick="updateRecord()" disabled>
            🔄 Ganti
        </button>
        <button id="btn-print" class="btn-desktop" onclick="printRecord()" disabled>
            🖨️ Cetak
        </button>
        <button id="btn-exit" class="btn-desktop" onclick="window.location.href='{{ route('surat.dashboard') }}'" style="margin-left: auto;">
            🚪 Keluar
        </button>
    </div>
</div>

<!-- Data Search & Table Container -->
<div class="card" style="padding: 0; overflow: hidden;">
    <!-- Filter & Search Bar -->
    <div class="desktop-filter-bar">
        <!-- Date Picker Filter -->
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <span style="font-size: 0.85rem; color: var(--text-secondary);">Tgl. Surat :</span>
            <input type="date" id="tgl_mulai" class="input-desktop" style="width: 140px; height: 30px;" value="{{ $tgl_mulai }}">
            <span style="font-size: 0.85rem; color: var(--text-secondary);">s.d.</span>
            <input type="date" id="tgl_selesai" class="input-desktop" style="width: 140px; height: 30px;" value="{{ $tgl_selesai }}">
        </div>

        <!-- Search Keyword -->
        <div style="display: flex; align-items: center; gap: 8px; flex-grow: 1; max-width: 450px;">
            <span style="font-size: 0.85rem; color: var(--text-secondary);">Key Word :</span>
            <input type="text" id="search-keyword" class="input-desktop" style="flex-grow: 1; height: 30px;" placeholder="Cari nomor, NIK, nama pasien..." value="{{ $search }}">
            <button class="btn btn-secondary btn-sm" onclick="filterData()" style="padding: 6px 14px; height: 30px;">Cari</button>
        </div>

        <!-- Record Count -->
        <div style="font-size: 0.85rem; color: var(--text-secondary);">
            Record : <strong id="record-count" style="color: var(--primary);">{{ $suratList->total() }}</strong>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container" style="border: none; border-radius: 0; box-shadow: none; margin-bottom: 0;">
        <table class="data-table" id="surat-table">
            <thead>
                <tr>
                    <th>No. Surat</th>
                    <th>No. Rawat</th>
                    <th>No. R.M.</th>
                    <th>Nama Pasien</th>
                    <th>Tanggal Surat</th>
                    <th>BB</th>
                    <th>TB</th>
                    <th>Tensi</th>
                    <th>Suhu</th>
                    <th>Buta Warna</th>
                    <th>Keperluan</th>
                    <th>Kesimpulan</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($suratList->isEmpty())
                    <tr>
                        <td colspan="13" style="text-align: center; padding: 30px; color: var(--text-secondary);">
                            Tidak ada data surat keterangan sehat.
                        </td>
                    </tr>
                @else
                    @foreach($suratList as $s)
                        <tr onclick="selectRow(this)" 
                            data-no_surat="{{ $s->no_surat }}"
                            data-no_rawat="{{ $s->no_rawat }}"
                            data-no_rkm_medis="{{ $s->no_rkm_medis }}"
                            data-nm_pasien="{{ $s->nm_pasien }}"
                            data-tanggalsurat="{{ $s->tanggalsurat }}"
                            data-berat="{{ $s->berat }}"
                            data-tinggi="{{ $s->tinggi }}"
                            data-tensi="{{ $s->tensi }}"
                            data-suhu="{{ $s->suhu }}"
                            data-butawarna="{{ $s->butawarna }}"
                            data-keperluan="{{ $s->keperluan }}"
                            data-kesimpulan="{{ $s->kesimpulan }}">
                            <td style="font-weight: 600; color: var(--primary);">{{ $s->no_surat }}</td>
                            <td>{{ $s->no_rawat }}</td>
                            <td>{{ $s->no_rkm_medis }}</td>
                            <td>{{ $s->nm_pasien }}</td>
                            <td>{{ \Carbon\Carbon::parse($s->tanggalsurat)->translatedFormat('d-m-Y') }}</td>
                            <td style="text-align: right;">{{ $s->berat }} Kg</td>
                            <td style="text-align: right;">{{ $s->tinggi }} Cm</td>
                            <td style="text-align: center;">{{ $s->tensi }}</td>
                            <td style="text-align: right;">{{ $s->suhu }} °C</td>
                            <td style="text-align: center;">
                                <span class="badge {{ $s->butawarna === 'Ya' ? 'badge-danger' : 'badge-success' }}">{{ $s->butawarna }}</span>
                            </td>
                            <td>{{ $s->keperluan }}</td>
                            <td>
                                <span class="badge {{ $s->kesimpulan === 'Sehat' ? 'badge-success' : 'badge-danger' }}">{{ $s->kesimpulan }}</span>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-primary btn-sm" onclick="event.stopPropagation(); printDirect('{{ rawurlencode($s->no_surat) }}')" style="padding: 4px 10px; font-size: 0.75rem;">
                                    🖨️ Cetak
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($suratList->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background-color: rgba(0, 0, 0, 0.02);">
            <div style="font-size: 0.8rem; color: var(--text-secondary);">
                Menampilkan {{ $suratList->firstItem() }} - {{ $suratList->lastItem() }} dari {{ $suratList->total() }} surat
            </div>
            <div style="display: flex; gap: 8px;">
                @if($suratList->onFirstPage())
                    <span class="btn btn-secondary btn-sm" style="cursor: not-allowed; opacity: 0.5;">Sebelumnya</span>
                @else
                    <a href="{{ $suratList->appends(request()->all())->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                @endif

                @if($suratList->hasMorePages())
                    <a href="{{ $suratList->appends(request()->all())->nextPageUrl() }}" class="btn btn-secondary btn-sm">Selanjutnya</a>
                @else
                    <span class="btn btn-secondary btn-sm" style="cursor: not-allowed; opacity: 0.5;">Selanjutnya</span>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Patient Registration Lookup Modal -->
<div class="modal-overlay" id="lookup-modal">
    <div class="modal-box">
        <div class="modal-header">
            <span>Pilih Registrasi Pasien</span>
            <button class="modal-close" onclick="closeLookupModal()">&times;</button>
        </div>
        <div class="modal-body">
            <!-- Search Modal Box -->
            <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                <input type="text" id="lookup-search" class="input-desktop" style="flex-grow: 1; height: 32px;" placeholder="Cari No. Rawat, No. R.M. atau Nama..." onkeyup="searchRegistrations(event)">
                <button class="btn btn-primary btn-sm" onclick="performLookupSearch()" style="height: 32px;">Cari</button>
            </div>
            
            <!-- Lookup Table -->
            <div class="table-container" style="max-height: 350px; overflow-y: auto; border-radius: 4px;">
                <table class="data-table" style="font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th>No. Rawat</th>
                            <th>No. R.M.</th>
                            <th>Nama Pasien</th>
                            <th>Pilih</th>
                        </tr>
                    </thead>
                    <tbody id="lookup-results">
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px; color: var(--text-secondary);">
                                Masukkan kata kunci pencarian...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let selectedNoSurat = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Auto fetch number on load
        fetchNextNoSurat();

        // Bind date change to auto-update number format
        document.getElementById('tanggalsurat').addEventListener('change', function() {
            if (!selectedNoSurat) {
                fetchNextNoSurat(this.value);
            }
        });
    });

    // Fetch next sequential number
    function fetchNextNoSurat(dateVal = '') {
        const dateParam = dateVal || document.getElementById('tanggalsurat').value;
        fetch(`{{ route('surat.sehat.new-no') }}?date=${dateParam}`)
            .then(res => res.json())
            .then(data => {
                if (!selectedNoSurat) {
                    document.getElementById('no_surat').value = data.no_surat;
                }
            })
            .catch(err => console.error('Gagal mengambil nomor surat:', err));
    }

    // Reset Form to Clean State
    function resetForm() {
        selectedNoSurat = null;
        
        // Reset fields manually to guarantee it clears in all browsers
        document.getElementById('no_rawat').value = '';
        document.getElementById('no_rkm_medis').value = '';
        document.getElementById('nm_pasien').value = '';
        document.getElementById('no_surat').value = '';
        document.getElementById('tanggalsurat').value = new Date().toISOString().substring(0, 10);
        document.getElementById('berat').value = '';
        document.getElementById('tinggi').value = '';
        document.getElementById('tensi').value = '';
        document.getElementById('suhu').value = '';
        document.getElementById('butawarna').value = 'Tidak';
        document.getElementById('keperluan').value = '';
        document.getElementById('kesimpulan').value = 'Sehat';
        
        document.getElementById('form-method').value = 'POST';
        
        // Remove row selection highlight
        const selected = document.querySelector('#surat-table tr.selected-row');
        if (selected) {
            selected.classList.remove('selected-row');
        }

        // Enable/Disable buttons
        document.getElementById('btn-save').disabled = false;
        document.getElementById('btn-delete').disabled = true;
        document.getElementById('btn-edit').disabled = true;
        document.getElementById('btn-print').disabled = true;

        fetchNextNoSurat();
    }

    // Modal lookup controls
    function openLookupModal() {
        document.getElementById('lookup-modal').classList.add('active');
        document.getElementById('lookup-search').value = '';
        document.getElementById('lookup-search').focus();
        performLookupSearch();
    }

    function closeLookupModal() {
        document.getElementById('lookup-modal').classList.remove('active');
    }

    // Perform search inside lookup modal
    function searchRegistrations(event) {
        if (event.key === 'Enter') {
            performLookupSearch();
        }
    }

    function performLookupSearch() {
        const query = document.getElementById('lookup-search').value;
        const tbody = document.getElementById('lookup-results');
        
        tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 20px; color: var(--text-secondary);">Mencari data...</td></tr>`;

        fetch(`{{ route('surat.sehat.registrasi-lookup') }}?search=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 20px; color: var(--text-secondary);">Registrasi pasien tidak ditemukan.</td></tr>`;
                    return;
                }

                tbody.innerHTML = '';
                data.forEach(reg => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td style="font-weight:600;">${reg.no_rawat}</td>
                        <td>${reg.no_rkm_medis}</td>
                        <td style="font-weight:500;">${reg.nm_pasien}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" style="padding: 4px 8px; font-size:0.75rem;" onclick="selectPatient('${reg.no_rawat}', '${reg.no_rkm_medis}', '${reg.nm_pasien}')">Pilih</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 20px; color: var(--danger);">Gagal memuat data.</td></tr>`;
            });
    }

    function selectPatient(no_rawat, no_rkm_medis, nm_pasien) {
        document.getElementById('no_rawat').value = no_rawat;
        document.getElementById('no_rkm_medis').value = no_rkm_medis;
        document.getElementById('nm_pasien').value = nm_pasien;
        closeLookupModal();
    }

    // Save record (INSERT)
    function saveRecord() {
        const form = document.getElementById('surat-sehat-form');
        const formData = new FormData(form);

        // Prevent double submit
        const btnSave = document.getElementById('btn-save');
        btnSave.disabled = true;
        const originalText = btnSave.innerHTML;
        btnSave.innerHTML = '💾 Menyimpan...';

        fetch(`{{ route('surat.sehat.store') }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async res => {
            if (res.status === 419) {
                alert('Sesi Anda telah berakhir. Halaman akan dimuat ulang.');
                window.location.reload();
                return;
            }
            if (res.status === 401) {
                alert('Silakan login terlebih dahulu.');
                window.location.href = '{{ route("login") }}';
                return;
            }
            
            const data = await res.json();
            if (res.ok) {
                btnSave.disabled = false;
                btnSave.innerHTML = originalText;
                
                // Prompt print action directly
                if (confirm(data.success + '\n\nApakah Anda ingin langsung mencetak surat ini?')) {
                    const noSurat = document.getElementById('no_surat').value;
                    printDirect(encodeURIComponent(noSurat));
                }
                window.location.reload();
            } else {
                btnSave.disabled = false;
                btnSave.innerHTML = originalText;
                alert(data.error || 'Terjadi kesalahan sistem.');
            }
        })
        .catch(err => {
            console.error(err);
            btnSave.disabled = false;
            btnSave.innerHTML = originalText;
            alert('Gagal mengirim data.');
        });
    }

    // Select row for editing/deleting
    function selectRow(row) {
        // Remove active class from previous selected
        const active = document.querySelector('#surat-table tr.selected-row');
        if (active) {
            active.classList.remove('selected-row');
        }

        row.classList.add('selected-row');

        // Populate fields
        selectedNoSurat = row.dataset.no_surat;
        document.getElementById('no_surat').value = row.dataset.no_surat;
        document.getElementById('no_rawat').value = row.dataset.no_rawat;
        document.getElementById('no_rkm_medis').value = row.dataset.no_rkm_medis;
        document.getElementById('nm_pasien').value = row.dataset.nm_pasien;
        document.getElementById('tanggalsurat').value = row.dataset.tanggalsurat;
        document.getElementById('berat').value = row.dataset.berat;
        document.getElementById('tinggi').value = row.dataset.tinggi;
        document.getElementById('tensi').value = row.dataset.tensi;
        document.getElementById('suhu').value = row.dataset.suhu;
        document.getElementById('butawarna').value = row.dataset.butawarna;
        document.getElementById('keperluan').value = row.dataset.keperluan;
        document.getElementById('kesimpulan').value = row.dataset.kesimpulan;

        // Change method to PUT
        document.getElementById('form-method').value = 'PUT';

        // Toggle buttons
        document.getElementById('btn-save').disabled = true;
        document.getElementById('btn-delete').disabled = false;
        document.getElementById('btn-edit').disabled = false;
        document.getElementById('btn-print').disabled = false;
    }

    // Update record (PUT)
    function updateRecord() {
        if (!selectedNoSurat) return;

        const form = document.getElementById('surat-sehat-form');
        const formData = new FormData(form);

        const btnEdit = document.getElementById('btn-edit');
        btnEdit.disabled = true;
        const originalText = btnEdit.innerHTML;
        btnEdit.innerHTML = '🔄 Mengubah...';

        // Append NIK update logic
        fetch(`{{ url('/surat/sehat') }}/${encodeURIComponent(selectedNoSurat)}`, {
            method: 'POST', // Use post with method spoofing
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async res => {
            if (res.status === 419) {
                alert('Sesi Anda telah berakhir. Halaman akan dimuat ulang.');
                window.location.reload();
                return;
            }
            if (res.status === 401) {
                alert('Silakan login terlebih dahulu.');
                window.location.href = '{{ route("login") }}';
                return;
            }

            const data = await res.json();
            if (res.ok) {
                btnEdit.disabled = false;
                btnEdit.innerHTML = originalText;
                
                if (confirm(data.success + '\n\nApakah Anda ingin mencetak surat ini?')) {
                    printDirect(encodeURIComponent(selectedNoSurat));
                }
                window.location.reload();
            } else {
                btnEdit.disabled = false;
                btnEdit.innerHTML = originalText;
                alert(data.error || 'Terjadi kesalahan sistem.');
            }
        })
        .catch(err => {
            console.error(err);
            btnEdit.disabled = false;
            btnEdit.innerHTML = originalText;
            alert('Gagal merubah data.');
        });
    }

    // Delete record (DELETE)
    function deleteRecord() {
        if (!selectedNoSurat) return;

        if (!confirm('Apakah Anda yakin ingin menghapus data surat sehat ini?')) {
            return;
        }

        const token = document.querySelector('input[name="_token"]').value;
        const btnDelete = document.getElementById('btn-delete');
        btnDelete.disabled = true;

        fetch(`{{ url('/surat/sehat') }}/${encodeURIComponent(selectedNoSurat)}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async res => {
            if (res.status === 419) {
                alert('Sesi Anda telah berakhir. Halaman akan dimuat ulang.');
                window.location.reload();
                return;
            }
            if (res.status === 401) {
                alert('Silakan login terlebih dahulu.');
                window.location.href = '{{ route("login") }}';
                return;
            }

            const data = await res.json();
            btnDelete.disabled = false;
            if (res.ok) {
                alert(data.success);
                window.location.reload();
            } else {
                alert(data.error || 'Gagal menghapus data.');
            }
        })
        .catch(err => {
            console.error(err);
            btnDelete.disabled = false;
            alert('Gagal mengirim perintah hapus.');
        });
    }

    // Print record
    function printRecord() {
        if (!selectedNoSurat) return;
        printDirect(encodeURIComponent(selectedNoSurat));
    }

    // Direct Print from row action or post-save
    function printDirect(noSurat) {
        const url = `{{ url('/surat/sehat') }}/${noSurat}/cetak`;
        window.open(url, '_blank');
    }

    // Filter list by date and keyword search
    function filterData() {
        const tgl_mulai = document.getElementById('tgl_mulai').value;
        const tgl_selesai = document.getElementById('tgl_selesai').value;
        const search = document.getElementById('search-keyword').value;

        let url = `{{ route('surat.sehat.index') }}?tgl_mulai=${tgl_mulai}&tgl_selesai=${tgl_selesai}`;
        if (search) {
            url += `&search=${encodeURIComponent(search)}`;
        }

        window.location.href = url;
    }
</script>
@endsection

@extends('layouts.app')

@section('title', 'Surat Keterangan Kelahiran Bayi')
@section('header_title', 'Surat Keterangan Kelahiran Bayi')

@section('content')
<div class="khanza-desktop-form">
    <div class="form-header">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
            <line x1="9" y1="9" x2="9.01" y2="9"></line>
            <line x1="15" y1="9" x2="15.01" y2="9"></line>
        </svg>
        <span>Registrasi Surat Keterangan Kelahiran Bayi</span>
    </div>

    <form id="kelahiran-bayi-form">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST">
        
        <div class="form-grid">
            <!-- Left Column: Identitas Bayi & Ukuran -->
            <div>
                <div class="form-section-title">
                    👶 Identitas & Ukuran Fisik Bayi
                </div>

                <div class="form-desktop-row">
                    <label for="no_rkm_medis">No. Rekam Medis (RM)</label>
                    <input type="text" id="no_rkm_medis" name="no_rkm_medis" class="input-desktop" style="width: 150px;" placeholder="Auto" value="{{ $nextRM }}" required>
                </div>

                <div class="form-desktop-row">
                    <label for="no_skl">No. SKL</label>
                    <input type="text" id="no_skl" name="no_skl" class="input-desktop" style="flex-grow: 1;" placeholder="Auto" value="{{ $nextSKL }}" required>
                </div>

                <div class="form-desktop-row">
                    <label for="nm_pasien">Nama Bayi</label>
                    <input type="text" id="nm_pasien" name="nm_pasien" class="input-desktop" style="flex-grow: 1;" placeholder="Nama Anak/Bayi Ny. ..." required>
                </div>

                <div class="form-desktop-row">
                    <label for="jk">Jenis Kelamin</label>
                    <select id="jk" name="jk" class="select-desktop" style="width: 150px;" required>
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="form-desktop-row">
                    <label for="tgl_lahir">Tanggal Lahir</label>
                    <input type="date" id="tgl_lahir" name="tgl_lahir" class="input-desktop" style="width: 160px;" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-desktop-row">
                    <label for="jam_lahir">Jam Lahir</label>
                    <input type="time" id="jam_lahir" name="jam_lahir" class="input-desktop" style="width: 120px;" step="1" value="{{ date('H:i:s') }}" required>
                </div>

                <div class="form-desktop-row">
                    <label for="anakke">Kelahiran Ke</label>
                    <input type="text" id="anakke" name="anakke" class="input-desktop" style="width: 80px; text-align: center;" placeholder="1, 2..." required>
                </div>

                <div class="form-desktop-row">
                    <label for="berat_badan">Berat Badan (BB)</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="berat_badan" name="berat_badan" class="input-desktop" style="width: 100px; text-align: right;" placeholder="3000" required>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Gram</span>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="panjang_badan">Panjang Badan (PB)</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="panjang_badan" name="panjang_badan" class="input-desktop" style="width: 100px; text-align: right;" placeholder="50" required>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Cm</span>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="lingkar_kepala">Lingkar Kepala</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="lingkar_kepala" name="lingkar_kepala" class="input-desktop" style="width: 100px; text-align: right;" placeholder="33" required>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Cm</span>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="lingkar_perut">Lingkar Perut</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="lingkar_perut" name="lingkar_perut" class="input-desktop" style="width: 100px; text-align: right;" placeholder="32">
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Cm</span>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="lingkar_dada">Lingkar Dada</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="lingkar_dada" name="lingkar_dada" class="input-desktop" style="width: 100px; text-align: right;" placeholder="31">
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Cm</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Data Orang Tua & Persalinan -->
            <div>
                <div class="form-section-title">
                    👪 Data Orang Tua & Persalinan
                </div>

                <div class="form-desktop-row">
                    <label for="nm_ibu">Nama Ibu</label>
                    <input type="text" id="nm_ibu" name="nm_ibu" class="input-desktop" style="flex-grow: 1;" placeholder="Nama Ibu Kandung" required>
                </div>

                <div class="form-desktop-row">
                    <label for="umur_ibu">Umur Ibu</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="umur_ibu" name="umur_ibu" class="input-desktop" style="width: 80px; text-align: center;" placeholder="28" required>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Tahun</span>
                    </div>
                </div>

                <div class="form-desktop-row">
                    <label for="nama_ayah">Nama Ayah</label>
                    <input type="text" id="nama_ayah" name="nama_ayah" class="input-desktop" style="flex-grow: 1;" placeholder="Nama Ayah Kandung" required>
                </div>

                <div class="form-desktop-row">
                    <label for="umur_ayah">Umur Ayah</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="text" id="umur_ayah" name="umur_ayah" class="input-desktop" style="width: 80px; text-align: center;" placeholder="30" required>
                        <span style="font-size: 0.8rem; color: var(--text-secondary);">Tahun</span>
                    </div>
                </div>

                <div class="form-desktop-row" style="align-items: flex-start;">
                    <label for="alamat">Alamat Orang Tua</label>
                    <textarea id="alamat" name="alamat" class="input-desktop" style="flex-grow: 1; height: 64px; border-radius: 8px; resize: none; padding: 8px 14px;" required placeholder="Alamat lengkap keluarga..."></textarea>
                </div>

                <div class="form-desktop-row">
                    <label for="proses_lahir">Proses Lahir</label>
                    <input type="text" id="proses_lahir" name="proses_lahir" class="input-desktop" style="flex-grow: 1;" placeholder="Normal / Caesar / Vakum" required>
                </div>

                <div class="form-desktop-row">
                    <label for="penyulit_kehamilan">Penyulit Kehamilan</label>
                    <input type="text" id="penyulit_kehamilan" name="penyulit_kehamilan" class="input-desktop" style="flex-grow: 1;" placeholder="Misal: Preeklampsia, atau -">
                </div>

                <div class="form-desktop-row">
                    <label for="ketuban">Ketuban</label>
                    <input type="text" id="ketuban" name="ketuban" class="input-desktop" style="flex-grow: 1;" placeholder="Jernih / Keruh / Meconium">
                </div>

                <div class="form-desktop-row">
                    <label for="diagnosa">Diagnosa</label>
                    <input type="text" id="diagnosa" name="diagnosa" class="input-desktop" style="flex-grow: 1;" placeholder="Diagnosa medis, misal: Hidup Lahir Tunggal">
                </div>

                <div class="form-desktop-row">
                    <label for="penolong">Penolong Persalinan</label>
                    <select id="penolong" name="penolong" class="select-desktop" style="flex-grow: 1;" required>
                        <option value="">-- Pilih Penolong --</option>
                        @foreach($employees as $e)
                            <option value="{{ $e->nik }}">{{ $e->nama }} ({{ $e->jbtn }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-desktop-row">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" id="keterangan" name="keterangan" class="input-desktop" style="flex-grow: 1;" placeholder="Catatan tambahan">
                </div>
            </div>
        </div>

        <!-- Section: APGAR & Obstetrik -->
        <div class="apgar-container">
            <div class="form-section-title">
                📊 Riwayat Obstetrik & Skor APGAR
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 16px; flex-wrap: wrap;">
                <div class="form-desktop-row" style="margin-bottom: 0;">
                    <label for="g" style="width: 80px;">Gravida (G)</label>
                    <input type="text" id="g" name="g" class="input-desktop" style="width: 80px; text-align: center;" placeholder="G">
                </div>
                <div class="form-desktop-row" style="margin-bottom: 0;">
                    <label for="p" style="width: 80px;">Para (P)</label>
                    <input type="text" id="p" name="p" class="input-desktop" style="width: 80px; text-align: center;" placeholder="P">
                </div>
                <div class="form-desktop-row" style="margin-bottom: 0;">
                    <label for="a" style="width: 80px;">Abortus (A)</label>
                    <input type="text" id="a" name="a" class="input-desktop" style="width: 80px; text-align: center;" placeholder="A">
                </div>
            </div>

            <!-- APGAR Evaluation Table Grid -->
            <table class="apgar-table">
                <thead>
                    <tr>
                        <th style="width: 200px;">Tanda</th>
                        <th style="width: 80px; text-align: center;">Skor 0</th>
                        <th style="text-align: center;">Skor 1</th>
                        <th style="width: 120px; text-align: center;">Skor 2</th>
                        <th style="width: 90px; text-align: center;">N 1' (1 Menit)</th>
                        <th style="width: 90px; text-align: center;">N 5' (5 Menit)</th>
                        <th style="width: 90px; text-align: center;">N 10' (10 Menit)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1: Jantung -->
                    <tr>
                        <td><strong>Frekuensi Jantung</strong></td>
                        <td style="text-align: center; color: var(--text-secondary);">Tidak ada</td>
                        <td style="text-align: center; color: var(--text-secondary);">&lt; 100 / menit</td>
                        <td style="text-align: center; color: var(--text-secondary);">&gt; 100 / menit</td>
                        <td>
                            <select id="f1" name="f1" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="f5" name="f5" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="f10" name="f10" onchange="calculateApgar()"></select>
                        </td>
                    </tr>

                    <!-- Row 2: Nafas -->
                    <tr>
                        <td><strong>Usaha Bernafas</strong></td>
                        <td style="text-align: center; color: var(--text-secondary);">Tidak ada</td>
                        <td style="text-align: center; color: var(--text-secondary);">Lambat, tidak teratur</td>
                        <td style="text-align: center; color: var(--text-secondary);">Menangis kuat</td>
                        <td>
                            <select id="u1" name="u1" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="u5" name="u5" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="u10" name="u10" onchange="calculateApgar()"></select>
                        </td>
                    </tr>

                    <!-- Row 3: Otot -->
                    <tr>
                        <td><strong>Tonus Otot</strong></td>
                        <td style="text-align: center; color: var(--text-secondary);">Lumpuh / lemas</td>
                        <td style="text-align: center; color: var(--text-secondary);">Ext. fleksi sedikit</td>
                        <td style="text-align: center; color: var(--text-secondary);">Gerakan aktif</td>
                        <td>
                            <select id="t1" name="t1" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="t5" name="t5" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="t10" name="t10" onchange="calculateApgar()"></select>
                        </td>
                    </tr>

                    <!-- Row 4: Refleks -->
                    <tr>
                        <td><strong>Refleks (Irritability)</strong></td>
                        <td style="text-align: center; color: var(--text-secondary);">Tidak ada respon</td>
                        <td style="text-align: center; color: var(--text-secondary);">Gerakan sedikit</td>
                        <td style="text-align: center; color: var(--text-secondary);">Menangis / meringis</td>
                        <td>
                            <select id="r1" name="r1" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="r5" name="r5" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="r10" name="r10" onchange="calculateApgar()"></select>
                        </td>
                    </tr>

                    <!-- Row 5: Warna -->
                    <tr>
                        <td><strong>Warna Kulit</strong></td>
                        <td style="text-align: center; color: var(--text-secondary);">Biru pucat</td>
                        <td style="text-align: center; color: var(--text-secondary);">Tubuh kemerahan, tangan/kaki biru</td>
                        <td style="text-align: center; color: var(--text-secondary);">Kemerahan seluruhnya</td>
                        <td>
                            <select id="w1" name="w1" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="w5" name="w5" onchange="calculateApgar()"></select>
                        </td>
                        <td>
                            <select id="w10" name="w10" onchange="calculateApgar()"></select>
                        </td>
                    </tr>

                    <!-- Row 6: Total -->
                    <tr style="background-color: rgba(14,165,233,0.05); font-weight: bold;">
                        <td colspan="4" style="text-align: right; padding-right: 20px;">TOTAL SCORE APGAR:</td>
                        <td style="text-align: center;">
                            <input type="text" id="n1" name="n1" class="input-desktop" style="width: 70px; text-align: center; font-weight: bold;" value="0" readonly>
                        </td>
                        <td style="text-align: center;">
                            <input type="text" id="n5" name="n5" class="input-desktop" style="width: 70px; text-align: center; font-weight: bold;" value="0" readonly>
                        </td>
                        <td style="text-align: center;">
                            <input type="text" id="n10" name="n10" class="input-desktop" style="width: 70px; text-align: center; font-weight: bold;" value="0" readonly>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Post-birth medical actions -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
                <div>
                    <div class="form-desktop-row">
                        <label for="resusitas">Resusitasi</label>
                        <input type="text" id="resusitas" name="resusitas" class="input-desktop" style="flex-grow: 1;" placeholder="Tindakan resusitasi">
                    </div>
                    <div class="form-desktop-row" style="align-items: flex-start;">
                        <label for="obat_diberikan">Obat Yang Diberikan</label>
                        <textarea id="obat_diberikan" name="obat_diberikan" class="input-desktop" style="flex-grow: 1; height: 60px; border-radius: 8px; resize: none; padding: 6px 14px;"></textarea>
                    </div>
                </div>
                <div>
                    <div class="form-desktop-row">
                        <label for="mikasi">Miksi (Kencing)</label>
                        <input type="text" id="mikasi" name="mikasi" class="input-desktop" style="flex-grow: 1;" placeholder="Kondisi miksi">
                    </div>
                    <div class="form-desktop-row">
                        <label for="mikonium">Mekonium (BAB)</label>
                        <input type="text" id="mikonium" name="mikonium" class="input-desktop" style="flex-grow: 1;" placeholder="Kondisi mekonium">
                    </div>
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
            <span style="font-size: 0.85rem; color: var(--text-secondary);">Tgl. Lahir :</span>
            <input type="date" id="tgl_mulai" class="input-desktop" style="width: 140px; height: 30px;" value="{{ $tgl_mulai }}">
            <span style="font-size: 0.85rem; color: var(--text-secondary);">s.d.</span>
            <input type="date" id="tgl_selesai" class="input-desktop" style="width: 140px; height: 30px;" value="{{ $tgl_selesai }}">
        </div>

        <!-- Search Keyword -->
        <div style="display: flex; align-items: center; gap: 8px; flex-grow: 1; max-width: 450px;">
            <span style="font-size: 0.85rem; color: var(--text-secondary);">Key Word :</span>
            <input type="text" id="search-keyword" class="input-desktop" style="flex-grow: 1; height: 30px;" placeholder="Cari No.RM, No.SKL, Nama Bayi, Ibu..." value="{{ $search }}">
            <button class="btn btn-secondary btn-sm" onclick="filterData()" style="padding: 6px 14px; height: 30px;">Cari</button>
        </div>

        <!-- Record Count -->
        <div style="font-size: 0.85rem; color: var(--text-secondary);">
            Record : <strong id="record-count" style="color: var(--primary);">{{ $bayiList->total() }}</strong>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container" style="border: none; border-radius: 0; box-shadow: none; margin-bottom: 0;">
        <table class="data-table" id="bayi-table">
            <thead>
                <tr>
                    <th>No. RM</th>
                    <th>No. SKL</th>
                    <th>Nama Bayi</th>
                    <th>J.K</th>
                    <th>Tgl. Lahir</th>
                    <th>Jam Lahir</th>
                    <th>Anak Ke</th>
                    <th>BB</th>
                    <th>PB</th>
                    <th>Nama Ibu</th>
                    <th>Nama Ayah</th>
                    <th>Penolong</th>
                    <th>Apgar Score (1'/5'/10')</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($bayiList->isEmpty())
                    <tr>
                        <td colspan="14" style="text-align: center; padding: 30px; color: var(--text-secondary);">
                            Tidak ada data surat keterangan kelahiran bayi.
                        </td>
                    </tr>
                @else
                    @foreach($bayiList as $b)
                        <tr onclick="selectRow(this)" 
                            data-no_rkm_medis="{{ $b->no_rkm_medis }}"
                            data-no_skl="{{ $b->no_skl }}"
                            data-nm_pasien="{{ $b->nm_pasien }}"
                            data-jk="{{ $b->jk }}"
                            data-tgl_lahir="{{ $b->tgl_lahir }}"
                            data-jam_lahir="{{ $b->jam_lahir }}"
                            data-anakke="{{ $b->anakke }}"
                            data-berat_badan="{{ $b->berat_badan }}"
                            data-panjang_badan="{{ $b->panjang_badan }}"
                            data-lingkar_kepala="{{ $b->lingkar_kepala }}"
                            data-lingkar_perut="{{ $b->lingkar_perut }}"
                            data-lingkar_dada="{{ $b->lingkar_dada }}"
                            
                            data-nm_ibu="{{ $b->nm_ibu }}"
                            data-umur_ibu="{{ $b->umur_ibu }}"
                            data-nama_ayah="{{ $b->nama_ayah }}"
                            data-umur_ayah="{{ $b->umur_ayah }}"
                            data-alamat="{{ $b->alamat }}"
                            data-proses_lahir="{{ $b->proses_lahir }}"
                            data-penyulit_kehamilan="{{ $b->penyulit_kehamilan }}"
                            data-ketuban="{{ $b->ketuban }}"
                            data-diagnosa="{{ $b->diagnosa }}"
                            data-penolong="{{ $b->penolong }}"
                            data-keterangan="{{ $b->keterangan }}"
                            
                            data-g="{{ $b->g }}"
                            data-p="{{ $b->p }}"
                            data-a="{{ $b->a }}"
                            
                            data-f1="{{ $b->f1 }}"
                            data-u1="{{ $b->u1 }}"
                            data-t1="{{ $b->t1 }}"
                            data-r1="{{ $b->r1 }}"
                            data-w1="{{ $b->w1 }}"
                            data-n1="{{ $b->n1 }}"
                            
                            data-f5="{{ $b->f5 }}"
                            data-u5="{{ $b->u5 }}"
                            data-t5="{{ $b->t5 }}"
                            data-r5="{{ $b->r5 }}"
                            data-w5="{{ $b->w5 }}"
                            data-n5="{{ $b->n5 }}"
                            
                            data-f10="{{ $b->f10 }}"
                            data-u10="{{ $b->u10 }}"
                            data-t10="{{ $b->t10 }}"
                            data-r10="{{ $b->r10 }}"
                            data-w10="{{ $b->w10 }}"
                            data-n10="{{ $b->n10 }}"
                            
                            data-resusitas="{{ $b->resusitas }}"
                            data-obat_diberikan="{{ $b->obat_diberikan }}"
                            data-mikasi="{{ $b->mikasi }}"
                            data-mikonium="{{ $b->mikonium }}">
                            
                            <td style="font-weight: 600; color: var(--primary);">{{ $b->no_rkm_medis }}</td>
                            <td style="font-weight: 600;">{{ $b->no_skl }}</td>
                            <td>{{ $b->nm_pasien }}</td>
                            <td style="text-align: center;">{{ $b->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ \Carbon\Carbon::parse($b->tgl_lahir)->translatedFormat('d-m-Y') }}</td>
                            <td style="text-align: center;">{{ $b->jam_lahir }}</td>
                            <td style="text-align: center;">{{ $b->anakke }}</td>
                            <td style="text-align: right;">{{ $b->berat_badan }} Gr</td>
                            <td style="text-align: right;">{{ $b->panjang_badan }} Cm</td>
                            <td>{{ $b->nm_ibu }}</td>
                            <td>{{ $b->nama_ayah }}</td>
                            <td>{{ $b->nama_penolong ?: '-' }}</td>
                            <td style="text-align: center; font-weight: 500;">
                                {{ $b->n1 }} / {{ $b->n5 }} / {{ $b->n10 }}
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-primary btn-sm" onclick="event.stopPropagation(); printDirect('{{ rawurlencode($b->no_rkm_medis) }}')" style="padding: 4px 10px; font-size: 0.75rem;">
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
    @if($bayiList->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background-color: rgba(0, 0, 0, 0.02);">
            <div style="font-size: 0.8rem; color: var(--text-secondary);">
                Menampilkan {{ $bayiList->firstItem() }} - {{ $bayiList->lastItem() }} dari {{ $bayiList->total() }} record
            </div>
            <div style="display: flex; gap: 8px;">
                @if($bayiList->onFirstPage())
                    <span class="btn btn-secondary btn-sm" style="cursor: not-allowed; opacity: 0.5;">Sebelumnya</span>
                @else
                    <a href="{{ $bayiList->appends(request()->all())->previousPageUrl() }}" class="btn btn-secondary btn-sm">Sebelumnya</a>
                @endif

                @if($bayiList->hasMorePages())
                    <a href="{{ $bayiList->appends(request()->all())->nextPageUrl() }}" class="btn btn-secondary btn-sm">Selanjutnya</a>
                @else
                    <span class="btn btn-secondary btn-sm" style="cursor: not-allowed; opacity: 0.5;">Selanjutnya</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    let selectedRM = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Build score options (0, 1, 2) programmatically for APGAR rows
        const apgarSelects = ['f1', 'f5', 'f10', 'u1', 'u5', 'u10', 't1', 't5', 't10', 'r1', 'r5', 'r10', 'w1', 'w5', 'w10'];
        apgarSelects.forEach(id => {
            const select = document.getElementById(id);
            for (let i = 0; i <= 2; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = i;
                select.appendChild(opt);
            }
        });

        // Initialize next SKL and RM numbers
        fetchNextNumbers();

        // Bind date change to update SKL number format
        document.getElementById('tgl_lahir').addEventListener('change', function() {
            if (!selectedRM) {
                fetchNextNumbers(this.value);
            }
        });
    });

    // Fetch next sequential RM and SKL numbers
    function fetchNextNumbers(dateVal = '') {
        const dateParam = dateVal || document.getElementById('tgl_lahir').value;
        fetch(`{{ route('surat.kelahiran.new-no') }}?date=${dateParam}`)
            .then(res => res.json())
            .then(data => {
                if (!selectedRM) {
                    document.getElementById('no_rkm_medis').value = data.no_rkm_medis;
                    document.getElementById('no_skl').value = data.no_skl;
                }
            })
            .catch(err => console.error('Gagal mengambil nomor sequence:', err));
    }

    // Auto-calculate APGAR Scores
    function calculateApgar() {
        // 1 Minute Score
        const n1Val = 
            parseInt(document.getElementById('f1').value) +
            parseInt(document.getElementById('u1').value) +
            parseInt(document.getElementById('t1').value) +
            parseInt(document.getElementById('r1').value) +
            parseInt(document.getElementById('w1').value);
        document.getElementById('n1').value = n1Val;

        // 5 Minutes Score
        const n5Val = 
            parseInt(document.getElementById('f5').value) +
            parseInt(document.getElementById('u5').value) +
            parseInt(document.getElementById('t5').value) +
            parseInt(document.getElementById('r5').value) +
            parseInt(document.getElementById('w5').value);
        document.getElementById('n5').value = n5Val;

        // 10 Minutes Score
        const n10Val = 
            parseInt(document.getElementById('f10').value) +
            parseInt(document.getElementById('u10').value) +
            parseInt(document.getElementById('t10').value) +
            parseInt(document.getElementById('r10').value) +
            parseInt(document.getElementById('w10').value);
        document.getElementById('n10').value = n10Val;
    }

    // Reset Form
    function resetForm() {
        selectedRM = null;
        
        // Reset inputs
        document.getElementById('no_rkm_medis').value = '';
        document.getElementById('no_skl').value = '';
        document.getElementById('nm_pasien').value = '';
        document.getElementById('jk').value = 'L';
        document.getElementById('tgl_lahir').value = new Date().toISOString().substring(0, 10);
        document.getElementById('jam_lahir').value = new Date().toTimeString().substring(0, 8);
        document.getElementById('anakke').value = '';
        document.getElementById('berat_badan').value = '';
        document.getElementById('panjang_badan').value = '';
        document.getElementById('lingkar_kepala').value = '';
        document.getElementById('lingkar_perut').value = '';
        document.getElementById('lingkar_dada').value = '';
        
        document.getElementById('nm_ibu').value = '';
        document.getElementById('umur_ibu').value = '';
        document.getElementById('nama_ayah').value = '';
        document.getElementById('umur_ayah').value = '';
        document.getElementById('alamat').value = '';
        document.getElementById('proses_lahir').value = '';
        document.getElementById('penyulit_kehamilan').value = '';
        document.getElementById('ketuban').value = '';
        document.getElementById('diagnosa').value = '';
        document.getElementById('penolong').value = '';
        document.getElementById('keterangan').value = '';
        
        document.getElementById('g').value = '';
        document.getElementById('p').value = '';
        document.getElementById('a').value = '';
        
        // Reset APGAR
        const apgarSelects = ['f1', 'f5', 'f10', 'u1', 'u5', 'u10', 't1', 't5', 't10', 'r1', 'r5', 'r10', 'w1', 'w5', 'w10'];
        apgarSelects.forEach(id => {
            document.getElementById(id).value = '0';
        });
        document.getElementById('n1').value = '0';
        document.getElementById('n5').value = '0';
        document.getElementById('n10').value = '0';
        
        document.getElementById('resusitas').value = '';
        document.getElementById('obat_diberikan').value = '';
        document.getElementById('mikasi').value = '';
        document.getElementById('mikonium').value = '';

        document.getElementById('form-method').value = 'POST';
        
        // Remove row selection highlight
        const selected = document.querySelector('#bayi-table tr.selected-row');
        if (selected) {
            selected.classList.remove('selected-row');
        }

        // Enable/Disable buttons
        document.getElementById('btn-save').disabled = false;
        document.getElementById('btn-delete').disabled = true;
        document.getElementById('btn-edit').disabled = true;
        document.getElementById('btn-print').disabled = true;

        fetchNextNumbers();
    }

    // Save record (INSERT)
    function saveRecord() {
        const form = document.getElementById('kelahiran-bayi-form');
        const formData = new FormData(form);

        // Prevent double submit
        const btnSave = document.getElementById('btn-save');
        btnSave.disabled = true;
        const originalText = btnSave.innerHTML;
        btnSave.innerHTML = '💾 Menyimpan...';

        fetch(`{{ route('surat.kelahiran.store') }}`, {
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
                
                if (confirm(data.success + '\n\nApakah Anda ingin langsung mencetak surat keterangan kelahiran ini?')) {
                    const noRM = document.getElementById('no_rkm_medis').value;
                    printDirect(encodeURIComponent(noRM));
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

    // Update record (PUT)
    function updateRecord() {
        if (!selectedRM) return;

        const form = document.getElementById('kelahiran-bayi-form');
        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        const btnEdit = document.getElementById('btn-edit');
        btnEdit.disabled = true;
        const originalText = btnEdit.innerHTML;
        btnEdit.innerHTML = '🔄 Mengubah...';

        fetch(`/surat/kelahiran/${encodeURIComponent(selectedRM)}`, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async res => {
            const data = await res.json();
            if (res.ok) {
                btnEdit.disabled = false;
                btnEdit.innerHTML = originalText;
                alert(data.success);
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
            alert('Gagal mengubah data.');
        });
    }

    // Delete record (DELETE)
    function deleteRecord() {
        if (!selectedRM) return;

        if (!confirm('Apakah Anda yakin ingin menghapus data kelahiran bayi dengan No. RM ' + selectedRM + '? Data pasien ini juga akan terhapus secara permanen.')) {
            return;
        }

        const btnDelete = document.getElementById('btn-delete');
        btnDelete.disabled = true;

        fetch(`/surat/kelahiran/${encodeURIComponent(selectedRM)}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(async res => {
            const data = await res.json();
            if (res.ok) {
                alert(data.success);
                window.location.reload();
            } else {
                btnDelete.disabled = false;
                alert(data.error || 'Terjadi kesalahan sistem.');
            }
        })
        .catch(err => {
            console.error(err);
            btnDelete.disabled = false;
            alert('Gagal menghapus data.');
        });
    }

    // Select row
    function selectRow(row) {
        const active = document.querySelector('#bayi-table tr.selected-row');
        if (active) {
            active.classList.remove('selected-row');
        }

        row.classList.add('selected-row');

        selectedRM = row.dataset.no_rkm_medis;
        
        // Populate inputs
        document.getElementById('no_rkm_medis').value = row.dataset.no_rkm_medis;
        document.getElementById('no_skl').value = row.dataset.no_skl;
        document.getElementById('nm_pasien').value = row.dataset.nm_pasien;
        document.getElementById('jk').value = row.dataset.jk;
        document.getElementById('tgl_lahir').value = row.dataset.tgl_lahir;
        document.getElementById('jam_lahir').value = row.dataset.jam_lahir;
        document.getElementById('anakke').value = row.dataset.anakke;
        document.getElementById('berat_badan').value = row.dataset.berat_badan;
        document.getElementById('panjang_badan').value = row.dataset.panjang_badan;
        document.getElementById('lingkar_kepala').value = row.dataset.lingkar_kepala;
        document.getElementById('lingkar_perut').value = row.dataset.lingkar_perut;
        document.getElementById('lingkar_dada').value = row.dataset.lingkar_dada;
        
        document.getElementById('nm_ibu').value = row.dataset.nm_ibu;
        document.getElementById('umur_ibu').value = row.dataset.umur_ibu;
        document.getElementById('nama_ayah').value = row.dataset.nama_ayah;
        document.getElementById('umur_ayah').value = row.dataset.umur_ayah;
        document.getElementById('alamat').value = row.dataset.alamat;
        document.getElementById('proses_lahir').value = row.dataset.proses_lahir;
        document.getElementById('penyulit_kehamilan').value = row.dataset.penyulit_kehamilan;
        document.getElementById('ketuban').value = row.dataset.ketuban;
        document.getElementById('diagnosa').value = row.dataset.diagnosa;
        document.getElementById('penolong').value = row.dataset.penolong;
        document.getElementById('keterangan').value = row.dataset.keterangan;
        
        document.getElementById('g').value = row.dataset.g;
        document.getElementById('p').value = row.dataset.p;
        document.getElementById('a').value = row.dataset.a;
        
        // Populate APGAR scores
        document.getElementById('f1').value = row.dataset.f1;
        document.getElementById('u1').value = row.dataset.u1;
        document.getElementById('t1').value = row.dataset.t1;
        document.getElementById('r1').value = row.dataset.r1;
        document.getElementById('w1').value = row.dataset.w1;
        document.getElementById('n1').value = row.dataset.n1;
        
        document.getElementById('f5').value = row.dataset.f5;
        document.getElementById('u5').value = row.dataset.u5;
        document.getElementById('t5').value = row.dataset.t5;
        document.getElementById('r5').value = row.dataset.r5;
        document.getElementById('w5').value = row.dataset.w5;
        document.getElementById('n5').value = row.dataset.n5;
        
        document.getElementById('f10').value = row.dataset.f10;
        document.getElementById('u10').value = row.dataset.u10;
        document.getElementById('t10').value = row.dataset.t10;
        document.getElementById('r10').value = row.dataset.r10;
        document.getElementById('w10').value = row.dataset.w10;
        document.getElementById('n10').value = row.dataset.n10;
        
        document.getElementById('resusitas').value = row.dataset.resusitas;
        document.getElementById('obat_diberikan').value = row.dataset.obat_diberikan;
        document.getElementById('mikasi').value = row.dataset.mikasi;
        document.getElementById('mikonium').value = row.dataset.mikonium;

        // Enable buttons
        document.getElementById('btn-save').disabled = true;
        document.getElementById('btn-delete').disabled = false;
        document.getElementById('btn-edit').disabled = false;
        document.getElementById('btn-print').disabled = false;
    }

    // Search and filters
    function filterData() {
        const query = document.getElementById('search-keyword').value;
        const start = document.getElementById('tgl_mulai').value;
        const end = document.getElementById('tgl_selesai').value;
        
        window.location.href = `?search=${encodeURIComponent(query)}&tgl_mulai=${start}&tgl_selesai=${end}`;
    }

    // Trigger Print Action
    function printRecord() {
        if (!selectedRM) return;
        printDirect(encodeURIComponent(selectedRM));
    }

    function printDirect(noRM) {
        const w = window.open(`/surat/kelahiran/${noRM}/cetak`, '_blank');
        w.focus();
    }
</script>
@endsection

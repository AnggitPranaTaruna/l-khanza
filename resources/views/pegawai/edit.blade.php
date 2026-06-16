@extends('layouts.app')

@section('title', 'Edit Pegawai')
@section('header_title', 'Edit Data Pegawai')

@section('content')
<div class="card" style="max-width: 1000px; margin: 0 auto;">
    <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 20px; margin-bottom: 32px;">
        <h3 style="font-weight: 600; font-size: 1.25rem;">Formulir Perubahan Profil Pegawai</h3>
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 4px;">Ubah data profil pegawai. NIK (Nomor Induk Karyawan) tidak dapat diubah karena merupakan kunci referensi sistem.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div style="display:flex; flex-direction:column; gap:4px;">
                <strong>Mohon perbaiki kesalahan berikut:</strong>
                <ul style="margin-left: 16px; font-size: 0.85rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('pegawai.update', $pegawai->nik) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Section 1: Personal Info -->
        <div style="margin-bottom: 32px;">
            <h4 style="font-weight: 600; font-size: 1rem; color: var(--primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:50%; background-color:rgba(14,165,233,0.15); font-size:0.75rem;">1</span>
                Informasi Pribadi Pegawai
            </h4>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="nik">NIK (Nomor Induk Karyawan) *</label>
                    <input type="text" id="nik" class="form-control" value="{{ $pegawai->nik }}" disabled style="opacity: 0.6; cursor: not-allowed; background-color: rgba(255, 255, 255, 0.02);">
                    <input type="hidden" name="nik" value="{{ $pegawai->nik }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="nama">Nama Lengkap *</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="{{ old('nama', $pegawai->nama) }}" placeholder="Masukkan nama lengkap" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="no_ktp">Nomor KTP (NIK KTP) *</label>
                    <input type="text" id="no_ktp" name="no_ktp" class="form-control" value="{{ old('no_ktp', $pegawai->no_ktp) }}" placeholder="16 digit nomor KTP" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="jk">Jenis Kelamin *</label>
                    <select id="jk" name="jk" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Pria" {{ old('jk', $pegawai->jk) === 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ old('jk', $pegawai->jk) === 'Wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="tmp_lahir">Tempat Lahir *</label>
                    <input type="text" id="tmp_lahir" name="tmp_lahir" class="form-control" value="{{ old('tmp_lahir', $pegawai->tmp_lahir) }}" placeholder="Tempat lahir" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="tgl_lahir">Tanggal Lahir *</label>
                    <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $pegawai->tgl_lahir) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="alamat">Alamat Tinggal *</label>
                    <input type="text" id="alamat" name="alamat" class="form-control" value="{{ old('alamat', $pegawai->alamat) }}" placeholder="Masukkan alamat lengkap rumah" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="kota">Kota *</label>
                    <input type="text" id="kota" name="kota" class="form-control" value="{{ old('kota', $pegawai->kota) }}" placeholder="Kota tempat tinggal" required>
                </div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 32px;">

        <!-- Section 2: Employment Info -->
        <div style="margin-bottom: 32px;">
            <h4 style="font-weight: 600; font-size: 1rem; color: var(--primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:50%; background-color:rgba(14,165,233,0.15); font-size:0.75rem;">2</span>
                Jabatan & Departemen Pekerjaan
            </h4>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="jbtn">Nama Jabatan *</label>
                    <input type="text" id="jbtn" name="jbtn" class="form-control" value="{{ old('jbtn', $pegawai->jbtn) }}" placeholder="Contoh: Perawat Gigi" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="jnj_jabatan">Jenjang Jabatan *</label>
                    <select id="jnj_jabatan" name="jnj_jabatan" class="form-control" required>
                        <option value="">-- Pilih Jenjang --</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->kode }}" {{ old('jnj_jabatan', $pegawai->jnj_jabatan) === $pos->kode ? 'selected' : '' }}>{{ $pos->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="kode_kelompok">Kelompok Jabatan *</label>
                    <select id="kode_kelompok" name="kode_kelompok" class="form-control" required>
                        <option value="">-- Pilih Kelompok --</option>
                        @foreach($groups as $grp)
                            <option value="{{ $grp->kode_kelompok }}" {{ old('kode_kelompok', $pegawai->kode_kelompok) === $grp->kode_kelompok ? 'selected' : '' }}>{{ $grp->nama_kelompok }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="departemen">Departemen *</label>
                    <select id="departemen" name="departemen" class="form-control" required>
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->dep_id }}" {{ old('departemen', $pegawai->departemen) === $dept->dep_id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="bidang">Bidang *</label>
                    <select id="bidang" name="bidang" class="form-control" required>
                        <option value="">-- Pilih Bidang --</option>
                        @foreach($bidangList as $bd)
                            <option value="{{ $bd->nama }}" {{ old('bidang', $pegawai->bidang) === $bd->nama ? 'selected' : '' }}>{{ $bd->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="indexins">Index Instansi *</label>
                    <select id="indexins" name="indexins" class="form-control" required>
                        <option value="">-- Pilih Index Instansi --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->dep_id }}" {{ old('indexins', $pegawai->indexins) === $dept->dep_id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="stts_kerja">Status Kerja *</label>
                    <select id="stts_kerja" name="stts_kerja" class="form-control" required>
                        <option value="">-- Pilih Status Kerja --</option>
                        @foreach($workStatuses as $ws)
                            <option value="{{ $ws->stts }}" {{ old('stts_kerja', $pegawai->stts_kerja) === $ws->stts ? 'selected' : '' }}>{{ $ws->ktg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="stts_aktif">Status Aktif *</label>
                    <select id="stts_aktif" name="stts_aktif" class="form-control" required>
                        <option value="AKTIF" {{ old('stts_aktif', $pegawai->stts_aktif) === 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                        <option value="CUTI" {{ old('stts_aktif', $pegawai->stts_aktif) === 'CUTI' ? 'selected' : '' }}>CUTI</option>
                        <option value="KELUAR" {{ old('stts_aktif', $pegawai->stts_aktif) === 'KELUAR' ? 'selected' : '' }}>KELUAR</option>
                        <option value="TENAGA LUAR" {{ old('stts_aktif', $pegawai->stts_aktif) === 'TENAGA LUAR' ? 'selected' : '' }}>TENAGA LUAR</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="mulai_kerja">Tanggal Mulai Kerja *</label>
                    <input type="date" id="mulai_kerja" name="mulai_kerja" class="form-control" value="{{ old('mulai_kerja', $pegawai->mulai_kerja) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="ms_kerja">Masa Kerja *</label>
                    <select id="ms_kerja" name="ms_kerja" class="form-control" required>
                        <option value="<1" {{ old('ms_kerja', $pegawai->ms_kerja) === '<1' ? 'selected' : '' }}>Kurang dari 1 tahun (&lt;1)</option>
                        <option value="PT" {{ old('ms_kerja', $pegawai->ms_kerja) === 'PT' ? 'selected' : '' }}>Masa Percobaan (PT)</option>
                        <option value="FT>1" {{ old('ms_kerja', $pegawai->ms_kerja) === 'FT>1' ? 'selected' : '' }}>Pegawai Tetap &gt; 1 Tahun (FT&gt;1)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="wajibmasuk">Hari Wajib Masuk (Bulanan) *</label>
                    <input type="number" id="wajibmasuk" name="wajibmasuk" class="form-control" value="{{ old('wajibmasuk', $pegawai->wajibmasuk) }}" min="1" max="31" required>
                </div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 32px;">

        <!-- Section 3: Financial & Tax Info -->
        <div style="margin-bottom: 32px;">
            <h4 style="font-weight: 600; font-size: 1rem; color: var(--primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:50%; background-color:rgba(14,165,233,0.15); font-size:0.75rem;">3</span>
                Pajak, Bank, Finansial & Risiko Kerja
            </h4>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="stts_wp">Status Wajib Pajak (Status WP) *</label>
                    <select id="stts_wp" name="stts_wp" class="form-control" required>
                        <option value="">-- Pilih Status WP --</option>
                        @foreach($taxStatuses as $ts)
                            <option value="{{ $ts->stts }}" {{ old('stts_wp', $pegawai->stts_wp) === $ts->stts ? 'selected' : '' }}>{{ $ts->ktg }} ({{ $ts->stts }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="npwp">Nomor NPWP</label>
                    <input type="text" id="npwp" name="npwp" class="form-control" value="{{ old('npwp', $pegawai->npwp) }}" placeholder="Masukkan NPWP (opsional)">
                </div>
                <div class="form-group">
                    <label class="form-label" for="pendidikan">Tingkat Pendidikan *</label>
                    <select id="pendidikan" name="pendidikan" class="form-control" required>
                        <option value="">-- Pilih Pendidikan --</option>
                        @foreach($educations as $edu)
                            <option value="{{ $edu->tingkat }}" {{ old('pendidikan', $pegawai->pendidikan) === $edu->tingkat ? 'selected' : '' }}>{{ $edu->tingkat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="bpd">Rekening Bank *</label>
                    <select id="bpd" name="bpd" class="form-control" required>
                        <option value="">-- Pilih Bank --</option>
                        @foreach($banks as $b)
                            <option value="{{ $b->namabank }}" {{ old('bpd', $pegawai->bpd) === $b->namabank ? 'selected' : '' }}>{{ $b->namabank }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="rekening">Nomor Rekening *</label>
                    <input type="text" id="rekening" name="rekening" class="form-control" value="{{ old('rekening', $pegawai->rekening) }}" placeholder="Nomor rekening bank" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="gapok">Gaji Pokok (IDR) *</label>
                    <input type="number" id="gapok" name="gapok" class="form-control" value="{{ old('gapok', $pegawai->gapok) }}" placeholder="Gaji pokok nominal" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="kode_resiko">Tingkat Risiko Kerja *</label>
                    <select id="kode_resiko" name="kode_resiko" class="form-control" required>
                        <option value="">-- Pilih Risiko --</option>
                        @foreach($risks as $r)
                            <option value="{{ $r->kode_resiko }}" {{ old('kode_resiko', $pegawai->kode_resiko) === $r->kode_resiko ? 'selected' : '' }}>{{ $r->nama_resiko }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="kode_emergency">Emergency Index *</label>
                    <select id="kode_emergency" name="kode_emergency" class="form-control" required>
                        <option value="">-- Pilih Emergency Index --</option>
                        @foreach($emergencyIndexes as $em)
                            <option value="{{ $em->kode_emergency }}" {{ old('kode_emergency', $pegawai->kode_emergency) === $em->kode_emergency ? 'selected' : '' }}>{{ $em->nama_emergency }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="button-group">
            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Batalkan</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

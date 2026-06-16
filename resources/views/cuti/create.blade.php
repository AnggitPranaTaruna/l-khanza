@extends('layouts.app')

@section('title', 'Ajukan Cuti')
@section('header_title', 'Formulir Pengajuan Cuti')

@section('content')
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 20px; margin-bottom: 32px;">
        <h3 style="font-weight: 600; font-size: 1.25rem;">Formulir Permohonan Cuti</h3>
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 4px;">Isi rentang tanggal, kategori cuti, alasan cuti, dan tunjuk rekan kerja aktif sebagai penanggung jawab selama Anda cuti.</p>
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

    <form action="{{ route('cuti.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="tanggal_awal">Tanggal Mulai Cuti *</label>
                <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="{{ old('tanggal_awal') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="tanggal_akhir">Tanggal Selesai Cuti *</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="{{ old('tanggal_akhir') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Total Durasi Cuti (Kalkulasi Otomatis)</label>
            <div style="display: flex; align-items: center; gap: 8px;">
                <input type="text" id="total_days" class="form-control" value="0 Hari" disabled style="opacity: 0.8; max-width: 150px; background-color: rgba(255,255,255,0.02); font-weight: 700; color: var(--warning); text-align: center;">
                <span style="font-size: 0.85rem; color: var(--text-secondary);">Dihitung dari tanggal mulai s/d selesai (inklusif).</span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="urgensi">Kategori / Urgensi Cuti *</label>
                <select id="urgensi" name="urgensi" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Tahunan" {{ old('urgensi') === 'Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="Besar" {{ old('urgensi') === 'Besar' ? 'selected' : '' }}>Cuti Besar</option>
                    <option value="Sakit" {{ old('urgensi') === 'Sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                    <option value="Bersalin" {{ old('urgensi') === 'Bersalin' ? 'selected' : '' }}>Cuti Bersalin (Melahirkan)</option>
                    <option value="Alasan Penting" {{ old('urgensi') === 'Alasan Penting' ? 'selected' : '' }}>Cuti Alasan Penting</option>
                    <option value="Keterangan Lainnya" {{ old('urgensi') === 'Keterangan Lainnya' ? 'selected' : '' }}>Keterangan Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="nik_pj">Penanggung Jawab (PJ Sementara) *</label>
                <select id="nik_pj" name="nik_pj" class="form-control" required>
                    <option value="">-- Pilih Staf PJ --</option>
                    @foreach($supervisors as $sv)
                        <option value="{{ $sv->nik }}" {{ old('nik_pj') === $sv->nik ? 'selected' : '' }}>{{ $sv->nama }} (NIK: {{ $sv->nik }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="alamat">Alamat Selama Cuti *</label>
            <input type="text" id="alamat" name="alamat" class="form-control" value="{{ old('alamat') }}" placeholder="Masukkan alamat domisili/lokasi Anda selama masa cuti" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="kepentingan">Alasan Cuti / Kepentingan *</label>
            <textarea id="kepentingan" name="kepentingan" class="form-control" rows="3" placeholder="Jelaskan alasan pengajuan cuti secara singkat" style="resize: none;" required>{{ old('kepentingan') }}</textarea>
        </div>

        <div class="button-group">
            <a href="{{ route('cuti.index') }}" class="btn btn-secondary">Batalkan</a>
            <button type="submit" class="btn btn-primary">Ajukan Cuti Sekarang</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tglAwal = document.getElementById('tanggal_awal');
        const tglAkhir = document.getElementById('tanggal_akhir');
        const totalDays = document.getElementById('total_days');

        function calculateDays() {
            if (tglAwal.value && tglAkhir.value) {
                const date1 = new Date(tglAwal.value);
                const date2 = new Date(tglAkhir.value);
                
                // Reset hours to avoid daylight savings discrepancies
                date1.setHours(0,0,0,0);
                date2.setHours(0,0,0,0);

                if (date2 >= date1) {
                    const diffTime = Math.abs(date2 - date1);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    totalDays.value = diffDays + ' Hari';
                } else {
                    totalDays.value = 'Tanggal Invalid';
                }
            } else {
                totalDays.value = '0 Hari';
            }
        }

        tglAwal.addEventListener('change', calculateDays);
        tglAkhir.addEventListener('change', calculateDays);
        
        // Initial run in case of validation back-binding
        calculateDays();
    });
</script>
@endsection

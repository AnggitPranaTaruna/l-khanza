<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Permohonan Cuti - {{ $cuti->no_pengajuan }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
            padding: 0;
            margin: 0;
            background-color: #fff;
        }

        /* Hospital Header */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed #ccc; /* fallback placeholder */
        }

        .logo-container svg {
            width: 50px;
            height: 50px;
            color: #10b981;
        }

        .kop-detail {
            flex-grow: 1;
            text-align: center;
        }

        .kop-detail h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .kop-detail p {
            font-size: 10pt;
            margin: 2px 0;
            color: #333;
        }

        /* Title */
        .judul-surat {
            text-align: center;
            margin-bottom: 30px;
        }

        .judul-surat h2 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .judul-surat p {
            font-size: 11pt;
            margin: 0;
        }

        /* Content */
        .pembuka {
            margin-bottom: 20px;
            text-align: justify;
            text-indent: 40px;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 6px 8px;
            vertical-align: top;
        }

        .data-table td.label-col {
            width: 200px;
            font-weight: 500;
        }

        .data-table td.colon-col {
            width: 15px;
            text-align: center;
        }

        /* Signatures Layout */
        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-col {
            text-align: center;
            width: 30%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .signature-title {
            margin-bottom: 80px;
            font-size: 11pt;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
        }

        .signature-id {
            font-size: 9pt;
            color: #555;
            margin-top: 4px;
        }

        /* Digital Signature Box */
        .digital-sig-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #10b981;
            background-color: #f0fdf4;
            color: #14532d;
            padding: 6px 12px;
            border-radius: 6px;
            margin: 5px 0;
            font-size: 8pt;
            font-family: sans-serif;
            max-width: 180px;
            text-align: center;
            line-height: 1.3;
        }

        .digital-sig-box svg {
            color: #10b981;
            margin-bottom: 4px;
        }

        .badge-status-print {
            border: 1.5px solid #000;
            padding: 4px 10px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
            font-size: 10pt;
            margin-top: 5px;
        }

        /* Print Settings */
        @media print {
            body {
                padding: 0;
            }
            .digital-sig-box {
                background-color: transparent !important;
                border-color: #ccc !important;
                color: #000 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <div class="logo-container">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke-width="2"></path>
            </svg>
        </div>
        <div class="kop-detail">
            <h1>{{ $rs->nama_instansi }}</h1>
            <p>{{ $rs->alamat_instansi }}, Kabupaten/Kota {{ $rs->kabupaten }}, Prov. {{ $rs->propinsi }}</p>
            <p>No. Telp: {{ $rs->kontak ?? ($rs->no_telp ?? '-') }} | Email: {{ $rs->email }}</p>
        </div>
    </div>

    <!-- Judul Surat -->
    <div class="judul-surat">
        <h2>Surat Pengajuan Cuti Pegawai</h2>
        <p>Nomor Dokumen : {{ $cuti->no_pengajuan }}</p>
    </div>

    <!-- Pembuka -->
    <p class="pembuka">
        Yang bertanda tangan di bawah ini mengajukan permohonan izin meninggalkan tugas / cuti kerja dengan rincian sebagai berikut:
    </p>

    <!-- Data Cuti -->
    <table class="data-table">
        <tr>
            <td class="label-col">NIK / Nama Pemohon</td>
            <td class="colon-col">:</td>
            <td><strong>{{ $cuti->nik }}</strong> / <strong>{{ $cuti->nama_pemohon }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Jabatan / Departemen</td>
            <td class="colon-col">:</td>
            <td>{{ $cuti->jabatan_pemohon ?: '-' }} / {{ $cuti->departemen_pemohon ?: '-' }} ({{ $cuti->bidang_pemohon ?: '-' }})</td>
        </tr>
        <tr>
            <td class="label-col">Jenis Cuti (Urgensi)</td>
            <td class="colon-col">:</td>
            <td><strong>{{ $cuti->urgensi }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Waktu Pelaksanaan</td>
            <td class="colon-col">:</td>
            <td>{{ $tgl_awal_formatted }} s/d {{ $tgl_akhir_formatted }} (<strong>{{ $cuti->jumlah }} Hari Kerja</strong>)</td>
        </tr>
        <tr>
            <td class="label-col">Alamat selama Cuti</td>
            <td class="colon-col">:</td>
            <td>{{ $cuti->alamat }}</td>
        </tr>
        <tr>
            <td class="label-col">Alasan/Kepentingan Cuti</td>
            <td class="colon-col">:</td>
            <td>{{ $cuti->kepentingan }}</td>
        </tr>
        <tr>
            <td class="label-col">Penanggung Jawab (PJ)</td>
            <td class="colon-col">:</td>
            <td>{{ $cuti->nama_pj }} ({{ $cuti->jabatan_pj ?: 'Staf' }})</td>
        </tr>
        <tr>
            <td class="label-col">Status Pengajuan</td>
            <td class="colon-col">:</td>
            <td>
                <span class="badge-status-print">
                    {{ $cuti->status }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan -->
    <div class="signature-container">
        <!-- Pemohon -->
        <div class="signature-col">
            <div class="signature-title">Pemohon,</div>
            <div style="height: 90px; display: flex; align-items: center; justify-content: center;">
                <div class="digital-sig-box" style="border-color: #3b82f6; background-color: #eff6ff; color: #1e3a8a;">
                    Diajukan secara digital oleh:<br>
                    <strong>{{ $cuti->nama_pemohon }}</strong><br>
                    Tgl: {{ $tgl_pengajuan_formatted }}
                </div>
            </div>
            <div class="signature-name">{{ $cuti->nama_pemohon }}</div>
            <div class="signature-id">NIK. {{ $cuti->nik }}</div>
        </div>

        <!-- PJ Terkait -->
        <div class="signature-col">
            <div class="signature-title">Penanggung Jawab (PJ),</div>
            <div style="height: 90px; display: flex; align-items: center; justify-content: center;">
                @if($cuti->status === 'Disetujui PJ' || $cuti->status === 'Disetujui')
                    <div class="digital-sig-box" style="border-color: #10b981; background-color: #f0fdf4; color: #14532d;">
                        Disetujui secara digital oleh:<br>
                        <strong>{{ $cuti->nama_pj }}</strong><br>
                        PJ Terkait
                    </div>
                @elseif($cuti->status === 'Ditolak')
                    <div class="digital-sig-box" style="border-color: #ef4444; background-color: #fef2f2; color: #7f1d1d; font-weight: bold;">
                        DITOLAK
                    </div>
                @else
                    <div style="font-style: italic; color: #999; font-size: 8pt;">Menunggu Persetujuan</div>
                @endif
            </div>
            <div class="signature-name">{{ $cuti->nama_pj }}</div>
            <div class="signature-id">NIK. {{ $cuti->nik_pj }}</div>
        </div>

        <!-- HRD / Direktur -->
        <div class="signature-col">
            <div class="signature-title">Menyetujui,<br>Direktur / HRD</div>
            <div style="height: 90px; display: flex; align-items: center; justify-content: center;">
                @if($cuti->status === 'Disetujui')
                    <div class="digital-sig-box" style="border-color: #10b981; background-color: #f0fdf4; color: #14532d;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 2px;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        APPROVED BY SYSTEM<br>
                        Tgl Setuju: {{ $tgl_pengajuan_formatted }}
                    </div>
                @elseif($cuti->status === 'Ditolak')
                    <div class="digital-sig-box" style="border-color: #ef4444; background-color: #fef2f2; color: #7f1d1d; font-weight: bold;">
                        REJECTED BY SYSTEM
                    </div>
                @else
                    <div style="font-style: italic; color: #999; font-size: 8pt;">Menunggu Persetujuan</div>
                @endif
            </div>
            <div class="signature-name">Bagian Kepegawaian</div>
            <div class="signature-id">R.S. Asy-Syifa Medika</div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>
</html>

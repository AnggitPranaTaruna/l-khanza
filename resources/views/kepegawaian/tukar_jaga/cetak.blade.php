<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pengajuan Tukar Jaga - {{ $tukar->no_pengajuan }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10.5pt;
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
            margin-bottom: 25px;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-container svg {
            width: 50px;
            height: 50px;
            color: #0ea5e9;
        }

        .kop-detail {
            flex-grow: 1;
            text-align: center;
        }

        .kop-detail h1 {
            font-size: 15pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .kop-detail p {
            font-size: 9.5pt;
            margin: 2px 0;
            color: #333;
        }

        /* Title */
        .judul-surat {
            text-align: center;
            margin-bottom: 25px;
        }

        .judul-surat h2 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .judul-surat p {
            font-size: 10.5pt;
            margin: 0;
        }

        /* Party Details Grid */
        .grid-parties {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .party-box {
            border: 1px solid #000;
            padding: 12px;
            border-radius: 4px;
        }

        .party-box h3 {
            margin: 0 0 8px 0;
            font-size: 11pt;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }

        .party-table {
            width: 100%;
            border-collapse: collapse;
        }

        .party-table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 10.5pt;
        }

        .party-table td.label-col {
            width: 80px;
        }

        .party-table td.colon-col {
            width: 12px;
            text-align: center;
        }

        /* Details */
        .details-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 6px 8px;
            vertical-align: top;
            border: 1px solid #000;
        }

        .details-table td.label-col {
            width: 220px;
            font-weight: bold;
            background-color: #f5f5f5;
        }

        /* Signatures Grid */
        .signatures-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 30px;
            text-align: center;
        }

        .sig-col {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sig-title {
            font-size: 10.5pt;
            margin-bottom: 8px;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
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

        .status-stamp {
            border: 2px solid #059669;
            color: #059669;
            display: inline-block;
            padding: 4px 12px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            transform: rotate(-5deg);
            margin: 15px auto;
            font-size: 12pt;
            letter-spacing: 0.1em;
        }

        .status-stamp.ditolak {
            border-color: #dc2626;
            color: #dc2626;
        }

        .status-stamp.proses {
            border-color: #d97706;
            color: #d97706;
        }

        /* Print Settings */
        @media print {
            body {
                padding: 0;
            }
            .party-box {
                background-color: transparent !important;
            }
            .details-table td.label-col {
                background-color: transparent !important;
            }
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <div class="logo-container">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                <path d="M2 17L12 22L22 17"/>
                <path d="M2 12L17L22 12"/>
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
        <h2>Formulir Pengajuan Tukar Jaga</h2>
        <p>Nomor Dokumen : {{ $tukar->no_pengajuan }}</p>
    </div>

    <!-- Parties Grid -->
    <div class="grid-parties">
        <!-- Pihak I -->
        <div class="party-box">
            <h3>Pihak I (Pemohon)</h3>
            <table class="party-table">
                <tr>
                    <td class="label-col">Nama</td>
                    <td class="colon-col">:</td>
                    <td><strong>{{ $tukar->nama_pemohon }}</strong></td>
                </tr>
                <tr>
                    <td class="label-col">NIK</td>
                    <td class="colon-col">:</td>
                    <td>{{ $tukar->nik_pemohon }}</td>
                </tr>
                <tr>
                    <td class="label-col">Jabatan</td>
                    <td class="colon-col">:</td>
                    <td>{{ $tukar->jabatan_pemohon ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Departemen</td>
                    <td class="colon-col">:</td>
                    <td>{{ $tukar->departemen_pemohon ?: ($tukar->bidang_pemohon ?: '-') }}</td>
                </tr>
            </table>
        </div>

        <!-- Pihak II -->
        <div class="party-box">
            <h3>Pihak II (Rekan Pengganti)</h3>
            <table class="party-table">
                <tr>
                    <td class="label-col">Nama</td>
                    <td class="colon-col">:</td>
                    <td><strong>{{ $tukar->nama_tukar }}</strong></td>
                </tr>
                <tr>
                    <td class="label-col">NIK</td>
                    <td class="colon-col">:</td>
                    <td>{{ $tukar->nik_tukar }}</td>
                </tr>
                <tr>
                    <td class="label-col">Jabatan</td>
                    <td class="colon-col">:</td>
                    <td>{{ $tukar->jabatan_tukar ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Departemen</td>
                    <td class="colon-col">:</td>
                    <td>{{ $tukar->departemen_tukar ?: ($tukar->bidang_tukar ?: '-') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Details Table -->
    <table class="details-table">
        <tr>
            <td class="label-col">Tanggal Pengajuan Permohonan</td>
            <td>{{ $tgl_pengajuan_formatted }}</td>
        </tr>
        <tr>
            <td class="label-col">Tanggal Pelaksanaan Tukar Jaga</td>
            <td><strong>{{ $tgl_tukar_formatted }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Alasan Tukar Jaga</td>
            <td>{{ $tukar->alasan }}</td>
        </tr>
    </table>

    <div style="text-align: center; margin-bottom: 20px;">
        @if($tukar->status === 'Disetujui')
            <div class="status-stamp">DISETUJUI</div>
        @elseif($tukar->status === 'Disetujui PJ')
            <div class="status-stamp" style="border-color: #0284c7; color: #0284c7;">DISETUJUI PJ</div>
        @elseif($tukar->status === 'Ditolak')
            <div class="status-stamp ditolak">DITOLAK</div>
        @else
            <div class="status-stamp proses">PROSES PENGAJUAN</div>
        @endif
    </div>

    <!-- Signatures -->
    <div class="signatures-grid">
        <!-- Pihak I -->
        <div class="sig-col">
            <span class="sig-title">Pihak I (Pemohon),</span>
            <div style="height: 80px; display: flex; align-items: center; justify-content: center;">
                <div class="digital-sig-box" style="border-color: #3b82f6; background-color: #eff6ff; color: #1e3a8a;">
                    Diajukan secara digital oleh:<br>
                    <strong>{{ $tukar->nama_pemohon }}</strong><br>
                    Tgl: {{ $tgl_pengajuan_formatted }}
                </div>
            </div>
            <span class="sig-name">{{ $tukar->nama_pemohon }}</span>
            <span style="font-size: 8.5pt; color: #555; margin-top: 4px;">NIK. {{ $tukar->nik_pemohon }}</span>
        </div>

        <!-- Pihak II -->
        <div class="sig-col">
            <span class="sig-title">Pihak II (Penerima Tukar),</span>
            <div style="height: 80px; display: flex; align-items: center; justify-content: center;">
                <div class="digital-sig-box" style="border-color: #3b82f6; background-color: #eff6ff; color: #1e3a8a;">
                    Disetujui secara digital oleh:<br>
                    <strong>{{ $tukar->nama_tukar }}</strong><br>
                    Tgl: {{ $tgl_pengajuan_formatted }}
                </div>
            </div>
            <span class="sig-name">{{ $tukar->nama_tukar }}</span>
            <span style="font-size: 8.5pt; color: #555; margin-top: 4px;">NIK. {{ $tukar->nik_tukar }}</span>
        </div>

        <!-- PJ -->
        <div class="sig-col">
            <span class="sig-title">Penanggung Jawab (PJ),</span>
            <div style="height: 80px; display: flex; align-items: center; justify-content: center;">
                @if($tukar->status === 'Disetujui PJ' || $tukar->status === 'Disetujui')
                    <div class="digital-sig-box" style="border-color: #10b981; background-color: #f0fdf4; color: #14532d;">
                        Disetujui secara digital oleh:<br>
                        <strong>{{ $tukar->nama_pj }}</strong><br>
                        PJ Terkait
                    </div>
                @elseif($tukar->status === 'Ditolak')
                    <div class="digital-sig-box" style="border-color: #ef4444; background-color: #fef2f2; color: #7f1d1d; font-weight: bold;">
                        DITOLAK
                    </div>
                @else
                    <div style="font-style: italic; color: #999; font-size: 8pt;">Menunggu Persetujuan</div>
                @endif
            </div>
            <span class="sig-name">{{ $tukar->nama_pj ?: '(Belum Memilih)' }}</span>
            <span style="font-size: 8.5pt; color: #555; margin-top: 4px;">NIK. {{ $tukar->nik_pj }}</span>
        </div>

        <!-- HRD -->
        <div class="sig-col">
            <span class="sig-title">Menyetujui,<br>Direktur / HRD</span>
            <div style="height: 80px; display: flex; align-items: center; justify-content: center;">
                @if($tukar->status === 'Disetujui')
                    <div class="digital-sig-box" style="border-color: #10b981; background-color: #f0fdf4; color: #14532d;">
                        APPROVED BY SYSTEM<br>
                        Tgl Setuju: {{ $tgl_pengajuan_formatted }}
                    </div>
                @elseif($tukar->status === 'Ditolak')
                    <div class="digital-sig-box" style="border-color: #ef4444; background-color: #fef2f2; color: #7f1d1d; font-weight: bold;">
                        REJECTED BY SYSTEM
                    </div>
                @else
                    <div style="font-style: italic; color: #999; font-size: 8pt;">Menunggu Persetujuan</div>
                @endif
            </div>
            <span class="sig-name">Bagian Kepegawaian</span>
            <span style="font-size: 8.5pt; color: #555; margin-top: 4px;">R.S. Asy-Syifa Medika</span>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>
</html>

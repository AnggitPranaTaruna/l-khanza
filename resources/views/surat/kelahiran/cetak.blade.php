<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Keterangan Kelahiran - No. RM {{ $bayi->no_rkm_medis }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.6;
            padding: 30px;
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
            width: 85px;
            height: 85px;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-container svg {
            width: 60px;
            height: 60px;
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
            margin-bottom: 25px;
            margin-left: 20px;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 5px 8px;
            vertical-align: top;
        }

        .data-table td.label-col {
            width: 200px;
        }

        .data-table td.colon-col {
            width: 15px;
            text-align: center;
        }

        .penutup {
            margin-bottom: 40px;
            text-indent: 40px;
            text-align: justify;
        }

        /* Signature block */
        .signature-block {
            float: right;
            text-align: center;
            width: 300px;
            margin-top: 10px;
        }

        .signature-date {
            margin-bottom: 10px;
        }

        .signature-title {
            margin-bottom: 80px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Digital Signature Box */
        .digital-sig-box {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #10b981;
            background-color: #f0fdf4;
            color: #14532d;
            padding: 6px 12px;
            border-radius: 6px;
            margin: 10px 0;
            font-size: 8pt;
            font-family: sans-serif;
            max-width: 250px;
            text-align: center;
            line-height: 1.3;
        }

        .digital-sig-box svg {
            color: #10b981;
            margin-bottom: 4px;
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
        <h2>Surat Keterangan Kelahiran</h2>
        <p>Nomor : {{ $bayi->no_skl }}</p>
    </div>

    <!-- Pembuka -->
    <p class="pembuka">
        Yang bertanda tangan di bawah ini menerangkan dengan sebenarnya bahwa pada:
    </p>

    <!-- Data Pasien -->
    <table class="data-table">
        <tr>
            <td class="label-col">Hari / Tanggal Lahir</td>
            <td class="colon-col">:</td>
            <td>
                <strong>
                    {{ \Carbon\Carbon::parse($bayi->tgl_lahir)->translatedFormat('l') }} / 
                    {{ $tgl_lahir_formatted }}
                </strong>
            </td>
        </tr>
        <tr>
            <td class="label-col">Pukul / Jam</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->jam_lahir }} WIB</td>
        </tr>
        <tr>
            <td class="label-col">Tempat Lahir</td>
            <td class="colon-col">:</td>
            <td>{{ $rs->nama_instansi }}</td>
        </tr>
        <tr>
            <td class="label-col">No. Rekam Medik</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->no_rkm_medis }}</td>
        </tr>
        <tr>
            <td class="label-col">Nama Bayi</td>
            <td class="colon-col">:</td>
            <td><strong>{{ $bayi->nm_pasien }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Jenis Kelamin</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->jk === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</td>
        </tr>
        <tr>
            <td class="label-col">Berat Badan (BB)</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->berat_badan }} Gram</td>
        </tr>
        <tr>
            <td class="label-col">Panjang Badan (PB)</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->panjang_badan }} Cm</td>
        </tr>
        <tr>
            <td class="label-col">Lingkar Kepala (LK)</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->lingkar_kepala }} Cm</td>
        </tr>
        @if($bayi->lingkar_perut)
        <tr>
            <td class="label-col">Lingkar Perut</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->lingkar_perut }} Cm</td>
        </tr>
        @endif
        @if($bayi->lingkar_dada)
        <tr>
            <td class="label-col">Lingkar Dada</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->lingkar_dada }} Cm</td>
        </tr>
        @endif
        <tr>
            <td class="label-col">Kelahiran Ke</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->anakke }}</td>
        </tr>
    </table>

    <p style="margin-left: 20px; margin-bottom: 15px;">
        Dari orang tua kandung:
    </p>

    <!-- Data Orang Tua -->
    <table class="data-table">
        <tr>
            <td class="label-col">Nama Ibu</td>
            <td class="colon-col">:</td>
            <td><strong>{{ $bayi->nm_ibu }}</strong> (Umur: {{ $bayi->umur_ibu }} Tahun)</td>
        </tr>
        <tr>
            <td class="label-col">Nama Ayah</td>
            <td class="colon-col">:</td>
            <td><strong>{{ $bayi->nama_ayah }}</strong> (Umur: {{ $bayi->umur_ayah }} Tahun)</td>
        </tr>
        <tr>
            <td class="label-col">Alamat Keluarga</td>
            <td class="colon-col">:</td>
            <td>{{ $bayi->alamat }}</td>
        </tr>
    </table>

    <!-- Penutup -->
    <p class="penutup">
        Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
    </p>

    <!-- Tanda Tangan -->
    <div class="signature-block">
        <div class="signature-date">{{ $rs->kabupaten }}, {{ $tgl_surat_formatted }}</div>
        <div class="signature-title">Penolong Persalinan,</div>
        
        <!-- Digital Signature E-Sign -->
        <div class="digital-sig-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            Ditandatangani secara elektronik oleh:<br>
            <strong>{{ $bayi->nama_penolong }}</strong><br>
            ID: {{ sha1($bayi->penolong) }}
        </div>
        
        <div class="signature-name">{{ $bayi->nama_penolong }}</div>
    </div>

    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>
</html>

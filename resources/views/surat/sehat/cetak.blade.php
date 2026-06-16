<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Keterangan Sehat - {{ $surat->no_surat }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.5;
            padding: 30px;
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

        /* Patient Data Table */
        .data-table {
            width: 100%;
            margin-bottom: 20px;
            margin-left: 20px;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 4px 8px;
            vertical-align: top;
        }

        .data-table td.label-col {
            width: 200px;
        }

        .data-table td.colon-col {
            width: 15px;
            text-align: center;
        }

        /* Physical Exam Card */
        .exam-section {
            border: 1px solid #000;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0 20px 20px;
            width: 320px;
            background-color: #fafafa;
        }

        .exam-section h3 {
            font-size: 11pt;
            font-weight: bold;
            margin: 0 0 10px 0;
            text-decoration: underline;
        }

        .exam-table {
            width: 100%;
            font-size: 11pt;
        }

        .exam-table td {
            padding: 3px 0;
        }

        .exam-table td.label-col {
            width: 130px;
        }

        .exam-table td.colon-col {
            width: 10px;
        }

        .kesimpulan {
            margin-bottom: 20px;
            text-align: justify;
        }

        .kesimpulan strong {
            text-decoration: underline;
        }

        .penutup {
            margin-bottom: 40px;
            text-indent: 40px;
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
            .exam-section {
                background-color: transparent !important;
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
        <h2>Surat Keterangan Sehat</h2>
        <p>Nomor : {{ $surat->no_surat }}</p>
    </div>

    <!-- Pembuka -->
    <p class="pembuka">
        Yang bertanda tangan di bawah ini menerangkan bahwa telah diperiksa kesehatan badan, panca indera, serta kondisi fisik seorang pasien yang bernama di bawah ini:
    </p>

    <!-- Data Pasien -->
    <table class="data-table">
        <tr>
            <td class="label-col">Nama Pasien</td>
            <td class="colon-col">:</td>
            <td><strong>{{ $surat->nm_pasien }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Tempat / Tanggal Lahir</td>
            <td class="colon-col">:</td>
            <td>{{ $surat->tmp_lahir ?: '-' }} / {{ $tgl_lahir_formatted }}</td>
        </tr>
        <tr>
            <td class="label-col">Umur</td>
            <td class="colon-col">:</td>
            <td>{{ $umur }} Tahun</td>
        </tr>
        <tr>
            <td class="label-col">Jenis Kelamin</td>
            <td class="colon-col">:</td>
            <td>{{ $surat->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="label-col">Pekerjaan</td>
            <td class="colon-col">:</td>
            <td>{{ $surat->pekerjaan ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Alamat</td>
            <td class="colon-col">:</td>
            <td>{{ $surat->alamat }}</td>
        </tr>
    </table>

    <!-- Hasil Pemeriksaan Fisik -->
    <div class="exam-section">
        <h3>Hasil Pemeriksaan Fisik:</h3>
        <table class="exam-table">
            <tr>
                <td class="label-col">Berat Badan (BB)</td>
                <td class="colon-col">:</td>
                <td>{{ $surat->berat }} Kg</td>
            </tr>
            <tr>
                <td class="label-col">Tinggi Badan (TB)</td>
                <td class="colon-col">:</td>
                <td>{{ $surat->tinggi }} Cm</td>
            </tr>
            <tr>
                <td class="label-col">Tekanan Darah (Tensi)</td>
                <td class="colon-col">:</td>
                <td>{{ $surat->tensi }} mmHg</td>
            </tr>
            <tr>
                <td class="label-col">Suhu Badan</td>
                <td class="colon-col">:</td>
                <td>{{ $surat->suhu }} °C</td>
            </tr>
            <tr>
                <td class="label-col">Buta Warna</td>
                <td class="colon-col">:</td>
                <td>{{ $surat->butawarna }}</td>
            </tr>
        </table>
    </div>

    <!-- Kesimpulan -->
    <div class="kesimpulan">
        Berdasarkan hasil pemeriksaan fisik dan panca indera tersebut di atas, dinyatakan dalam keadaan:
        <br>
        <span style="font-size: 14pt; font-weight: bold; margin-left: 20px; display: inline-block; padding: 10px 0;">
            👉 <u>{{ strtoupper($surat->kesimpulan) }}</u>
        </span>
        <br>
        Surat keterangan sehat ini dibuat untuk memenuhi persyaratan keperluan: <strong>{{ $surat->keperluan }}</strong>.
    </div>

    <!-- Penutup -->
    <p class="penutup">
        Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
    </p>

    <!-- Tanda Tangan -->
    <div class="signature-block">
        <div class="signature-date">{{ $rs->kabupaten }}, {{ $tgl_surat_formatted }}</div>
        <div class="signature-title">Dokter Pemeriksa,</div>
        
        <!-- Digital Signature E-Sign -->
        <div class="digital-sig-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            Ditandatangani secara elektronik oleh:<br>
            <strong>{{ $surat->nm_dokter }}</strong><br>
            ID: {{ sha1($surat->kd_dokter) }}
        </div>
        
        <div class="signature-name">{{ $surat->nm_dokter }}</div>
    </div>

    <script>
        window.addEventListener('load', function() {
            // Automatically trigger browser print dialog on load
            window.print();
        });
    </script>
</body>
</html>

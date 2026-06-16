# Product Requirement Document (PRD) - L-Khanza

L-Khanza adalah web port berbasis framework **Laravel 12** yang dirancang untuk mengadopsi struktur, skema basis data, otentikasi, dan hak akses dari sistem **SIMRS Khanza**.

---

## 1. 🛡️ Keamanan & Otentikasi (Hak Akses SIMRS Khanza)

Spesifikasi otentikasi dan otorisasi L-Khanza mengikuti standar **SIMRS Khanza** menggunakan enkripsi **AES-128-ECB** pada tingkat basis data MySQL dengan kunci enkripsi (secret keys) spesifik:

### A. Mekanisme Kredensial Enkripsi
* **Key Username / ID**: `'nur'`
* **Key Password**: `'windi'`
* Enkripsi data saat penyimpanan menggunakan fungsi MySQL `AES_ENCRYPT(?, 'nur')` untuk username/ID dan `AES_ENCRYPT(?, 'windi')` untuk password.
* Pencocokan login menggunakan perbandingan raw SQL:
  ```sql
  -- Cek Admin Utama
  SELECT * FROM admin WHERE usere = AES_ENCRYPT(username, 'nur') AND passworde = AES_ENCRYPT(password, 'windi');
  
  -- Cek User/Pegawai
  SELECT * FROM user WHERE id_user = AES_ENCRYPT(nik, 'nur') AND password = AES_ENCRYPT(password, 'windi');
  ```

### B. Matriks Hak Akses (Otorisasi)
* **Admin Utama (Superuser)**: Akun yang terdaftar di tabel `admin`. Admin Utama otomatis mem-bypass semua pemeriksaan hak akses dan memiliki akses penuh (`true`) ke semua fitur sistem.
* **User / Pegawai**: Akun yang terdaftar di tabel `user`. Hak akses bersifat granular dan dinilai langsung dari ratusan kolom bertipe `enum('true','false')` di dalam tabel `user`.
  * **Modul Kepegawaian**: Diatur oleh kolom `pegawai_admin` (untuk akses administratif/CRUD) dan `pegawai_user` (untuk akses operasional/view).
  * **Modul Cuti**: Diatur oleh kolom `pengajuan_cuti` (untuk mengajukan cuti dan melihat daftar cuti).
  * Pemrosesan (Persetujuan/Penolakan) cuti diatur oleh kolom `pegawai_admin` (hanya dapat dilakukan oleh admin/atasan).

---

## 2. 🗄️ Spesifikasi Skema & Relasi Basis Data

Sistem berjalan menggunakan database MySQL bernama **`sik`** dengan tabel-tabel utama berikut:

### A. Tabel `pegawai` (Profil Karyawan)
* Menyimpan profil detail karyawan dengan primary key `id` (Auto Increment) dan unique key `nik` (Nomor Induk Karyawan).
* Berelasi dengan 10 tabel lookup referensi untuk menjamin integritas data:
  * `jnj_jabatan` (Jenjang Jabatan)
  * `kelompok_jabatan` (Kelompok Medis/Administrasi)
  * `resiko_kerja` (Tingkat Risiko Kerja)
  * `emergency_index` (Indeks Darurat)
  * `departemen` (Departemen Karyawan)
  * `bidang` (Bidang Kerja Karyawan)
  * `stts_wp` (Status Wajib Pajak)
  * `stts_kerja` (Status Kepegawaian)
  * `pendidikan` (Tingkat Pendidikan)
  * `bank` (Rekening Bank Gaji)
* Kolom `cuti_diambil` (int) mencatat total akumulasi hari cuti yang telah disetujui.

### B. Tabel `pengajuan_cuti` (Data Cuti Karyawan)
* Primary key: `no_pengajuan` (varchar 17).
* Kolom:
  * `tanggal` (date) - tanggal input pengajuan.
  * `tanggal_awal` & `tanggal_akhir` (date) - rentang masa cuti.
  * `nik` (varchar) - berelasi ke `pegawai.nik` (pemohon).
  * `urgensi` (enum: Tahunan, Besar, Sakit, Bersalin, Alasan Penting, Keterangan Lainnya) - jenis cuti.
  * `alamat` (varchar 100) - alamat selama cuti.
  * `jumlah` (int) - durasi cuti dalam hari (inklusif).
  * `kepentingan` (varchar 70) - alasan cuti.
  * `nik_pj` (varchar) - berelasi ke `pegawai.nik` (penanggung jawab selama cuti).
  * `status` (enum: Proses Pengajuan, Disetujui, Ditolak) - status persetujuan.

---

## 3. 🗺️ Alur Navigasi (Module Selection Launcher)

Sistem menggunakan alur modular untuk memisahkan launcher utama dengan workspace subsystem:

1. **Dashboard Utama (Launcher) - `/`**
   * Diakses pertama kali setelah login sukses.
   * Menampilkan grid kartu pemilihan modul (Kepegawaian, Farmasi, Rekam Medis, dll.).
   * Sidebar disederhanakan hanya menampilkan navigasi utama "Pilih Modul".
2. **Workspace Subsystem - `/kepegawaian/dashboard`**
   * Diakses saat pengguna memilih "Modul Kepegawaian" pada launcher.
   * Sidebar meluas secara dinamis memuat submenu internal Kepegawaian (Dashboard Modul, Data Pegawai, Cuti Pegawai).
   * Menampilkan tombol **`⬅️ Menu Utama`** pada sidebar dan header untuk kembali ke launcher modul utama.

---

## 4. 👥 Modul Kepegawaian (HR Module)

* **Manajemen CRUD**: Memungkinkan admin (pengguna dengan hak `pegawai_admin`) untuk menambah, mengedit, dan menghapus profil pegawai.
* **Otomatisasi Akun User**: Saat pegawai baru berhasil ditambahkan ke tabel `pegawai`, sistem secara otomatis membuat akun di tabel `user` dengan username = NIK, password bawaan = `password123` (dienkripsi AES), hak akses `pegawai_user = 'true'`, dan `pengajuan_cuti = 'true'`.
* **Lookups Form**: Semua pilihan drop-down pada formulir pegawai di-load langsung dari 10 tabel referensi basis data.

---

## 5. ✈️ Modul Cuti Pegawai (Leave Subsystem)

* **Antarmuka Desktop-Style (SPA)**: Halaman `index` cuti didesain satu halaman utuh menyerupai aplikasi desktop Java Swing SIMRS Khanza:
  * Formulir input berada di bagian atas.
  * Barisan tombol aksi (*Simpan*, *Baru*, *Hapus*, *Ganti*, *Keluar*) berada di tengah.
  * Pencarian keyword, filter tanggal, dan tabel data berada di bawah.
* **Pencarian Pegawai Modal (Lookup)**: Tombol clip (`📎`) membuka popup modal untuk mencari dan memilih NIK pemohon dan NIK PJ secara instan dari tabel pegawai aktif.
* **Format Penomoran Otomatis**: Bidang `No.Pengajuan` diisi otomatis saat form dalam keadaan baru menggunakan format `PCYYYYMMDDXXX` (contoh: `PC20260616001`), berurut sekuensial per tanggal input.
* **Sinkronisasi Kuota Transaksional**:
  * Saat pengajuan cuti berstatus **`Disetujui`** (di-approve admin), kolom `cuti_diambil` pada tabel `pegawai` otomatis bertambah sesuai jumlah hari cuti.
  * Jika persetujuan dibatalkan atau pengajuan dihapus, kuota cuti pada tabel `pegawai` otomatis berkurang kembali.
  * Menggunakan sistem pemutakhiran dinamis untuk melacak perubahan durasi cuti pada baris data yang sudah disetujui.

---

## 6. Desain Visual & Pengalaman Pengguna

* **Vanilla CSS**: Murni tanpa Tailwind/Bootstrap.
* **Modern Themes (Dark & Light Mode)**:
  * **Tema Gelap (Default)**: Slate theme (`#0f172a` bg, `#1e293b` cards/sidebar), aksen biru neon (`#0ea5e9`).
  * **Tema Terang**: Light theme (`#f8fafc` bg, `#ffffff` sidebar/cards), aksen biru langit (`#0284c7`).
  * **Pencegahan FOUC**: Skrip pemuatan dini disematkan pada `<head>` untuk menyetel tema secara instan dari `localStorage` sebelum halaman melakukan rendering visual.
* **Responsive Layout (Mobile, Tablet, Laptop)**:
  * **Desktop / Laptop (Layar > 768px)**: Sidebar dapat disembunyikan (collapsed) dengan menggeser keluar (`margin-left: -260px;`) via tombol hamburger di header untuk memperluas ruang kerja, dan statusnya disimpan di `localStorage`.
  * **Tablet & Mobile (Layar ≤ 768px)**: Sidebar secara default disembunyikan off-screen dan berubah menjadi overlay laci (drawer) interaktif dengan backdrop gelap (overlay) ketika dibuka.
  * **Form Stack & Grid Adaptive**: Form input pada Modul Cuti dan Pegawai yang tadinya sejajar horizontal otomatis ditumpuk secara vertikal (stacked) pada layar kecil untuk mencegah pemotongan kolom.
  * **Responsive Tables**: Seluruh tabel dibungkus dalam `.table-container` dengan `overflow-x: auto` untuk gulir horizontal yang mulus tanpa merusak tata letak layar handphone.
* **Glassmorphism**: Desain semi-transparan dengan efek blur pada kartu login dan launcher.
* **Micro-Animations**: Hover transitions pada navigasi, tombol, dan baris tabel untuk meningkatkan keaktifan interaksi antarmuka.

---

## 7. ✉️ Modul Surat Keterangan (Letters Subsystem)

* **Skema & Integritas Data**: Berjalan di atas tabel `surat_keterangan_sehat` (10 kolom) yang berelasi dengan `reg_periksa` (tabel registrasi) dan `pasien` (tabel profil pasien).
* **Format Penomoran Otomatis**: Bidang `No.Surat` diisi otomatis saat form dalam keadaan baru menggunakan format `urut/D/SS/ROMAN_MONTH/AM/TBB/YEAR` (contoh: `001/D/SS/VI/AM/TBB/2026`). Nilai `urut` diperoleh dengan mengambil angka tertinggi yang terdaftar pada tahun berjalan lalu diinkrementasi (+1). Format nomor akan menyesuaikan secara real-time apabila tanggal surat diubah.
* **Pencarian Registrasi Pasien (Lookup)**: Tombol clip (`📎`) membuka popup modal untuk mencari data registrasi pemeriksaan aktif (`no_rawat`) dari database. Hasil pencarian menampilkan data No. Rawat, No. Rekam Medis, dan Nama Pasien secara terperinci. Ketika baris dipilih, data langsung terisi di form utama.
* **Layout Cetak Surat Resmi (High Fidelity)**: 
  * Menu *Cetak* membuka jendela baru berisi berkas Surat Keterangan Sehat resmi siap cetak.
  * Dilengkapi kop surat double-line dengan logo instansi dan detail alamat rumah sakit.
  * Memuat data diri lengkap pasien beserta hasil pemeriksaan fisik (BB, TB, Tensi, Suhu, Buta Warna).
  * Menampilkan kesimpulan kelaikan kondisi fisik (**SEHAT / TIDAK SEHAT**).
  * Blok tanda tangan dokter pemeriksa dilengkapi tanda tangan elektronik (e-sign verification block) berbasis hashing SHA1 dari kode dokter.
  * Otomatis memicu dialog cetak browser (`window.print()`).


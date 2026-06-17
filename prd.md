# Product Requirements Document (PRD) - SIMRS KHANZA

Dokumen ini mendokumentasikan spesifikasi kebutuhan sistem, aturan keamanan, hak akses, dan implementasi fitur yang dikembangkan pada **SIMRS KHANZA** (Subsystem Kepegawaian & Surat Keterangan).

---

## 1. Keamanan & Autentikasi (Standar SIMRS Khanza)

Keamanan dan skema otorisasi sistem ini mereplikasi standar **SIMRS Khanza** asli yang berbasis basis data:

### Enkripsi Basis Data (AES-128-ECB)
Sistem menggunakan enkripsi dua arah pada tingkat basis data (MySQL) untuk otentikasi login:
1. **Username / ID User / NIK**: Dienkripsi menggunakan fungsi `AES_ENCRYPT(username, 'nur')`.
2. **Password**: Dienkripsi menggunakan fungsi `AES_ENCRYPT(password, 'windi')`.

### Skema Tabel Hak Akses (`user`)
Tabel `user` memiliki ratusan kolom bertipe `enum('true','false')` yang merepresentasikan akses fitur individu:
* `pegawai_admin` & `pegawai_user` : Mengontrol akses ke data profil pegawai.
* `pengajuan_cuti` : Mengontrol akses pengajuan cuti & tukar jaga.
* `surat_keterangan_sehat` : Mengontrol akses pembuatan Surat Ket. Sehat.
* `kelahiran_bayi` : Mengontrol akses Surat Ket. Kelahiran Bayi.

---

## 2. Struktur Hak Akses & Peran Pengguna (RBAC)

Akses menu dan tombol aksi diatur ketat berdasarkan login pengguna:

| Peran (Role) | Kriteria / Kolom Izin | Cakupan Hak Akses (Permissions) |
| :--- | :--- | :--- |
| **Admin Utama** | Ada di tabel `admin` | Memiliki kontrol penuh atas semua menu dan dapat melakukan persetujuan akhir (HRD) untuk Cuti & Tukar Jaga. |
| **Penanggung Jawab (PJ)** | NIK dicantumkan sebagai PJ pada pengajuan | Dapat menyetujui (`approve-pj`) atau menolak (`reject-pj`) pengajuan cuti/tukar jaga staf di bawahnya. Tombol aksi hanya muncul jika PJ login dengan akun miliknya sendiri. |
| **Pegawai Biasa / Staff** | Ada di tabel `user` | Mengisi permohonan cuti/tukar jaga sendiri, dan hanya bisa melihat histori pengajuan miliknya sendiri. |

---

## 3. Alur Kerja (Workflow) Fitur Utama

### A. Pengajuan Cuti Berjenjang
1. **Pengisian**: Pegawai mengisi data pengajuan cuti dan menunjuk Penanggung Jawab (PJ). Status awal: `Proses Pengajuan`.
2. **Persetujuan PJ**: PJ harus masuk ke sistem menggunakan akunnya sendiri. Menu persetujuan PJ akan tampil dinamis. Jika disetujui, status berubah menjadi `Disetujui PJ`.
3. **Persetujuan HRD**: Admin Utama/HRD memeriksa pengajuan yang telah disetujui PJ. Jika disetujui, status berubah menjadi `Disetujui` dan kuota cuti pegawai terpotong secara otomatis (`cuti_diambil` bertambah).
4. **Pembatalan / Penghapusan**: Jika pengajuan yang sudah disetujui HRD dihapus, kuota cuti pegawai otomatis dikembalikan.

### B. Pengajuan Tukar Jaga Berjenjang
1. **Pengisian**: Pemohon (Pihak I) mengisi formulir, memilih Rekan Pengganti (Pihak II), dan menunjuk PJ. Status awal: `Proses Pengajuan`.
2. **Persetujuan PJ**: PJ login untuk memberikan penyetujuan awal. Status berubah menjadi `Disetujui PJ`.
3. **Persetujuan HRD**: HRD menyetujui pengajuan akhir. Status berubah menjadi `Disetujui`.
4. **Tanda Tangan Otomatis**: Hasil cetak surat menampilkan digital signature / status persetujuan Pihak I, Pihak II, PJ, dan HRD secara dinamis sesuai status database.

---

## 4. Desain & Antarmuka (UI/UX)
Sistem menggunakan **Vanilla CSS** modern dengan pendekatan premium dan responsif:
* **Tema Gelap & Terang**: Dilengkapi tombol toggle theme di header. Latar belakang memiliki gradien radial halus (*accent glow*).
* **Live Background (Animated Mesh & Interactive Particles)**: Ditambahkan 3 lingkaran cahaya dinamis (`.orb-1`, `.orb-2`, `.orb-3`) di latar belakang yang bergerak lambat, dilapisi dengan `<canvas>` interaktif (`#bg-canvas` & `live-bg.js`) yang memancarkan partikel cahaya mengambang dan jaring konstelasi halus. Partikel merespons gerakan mouse (gaya repulsi), mendeteksi kerapatan piksel layar (Retina/High-DPI), mendukung sistem aksesibilitas (`prefers-reduced-motion`), serta bertransisi warna secara dinamis mengikuti perubahan tema.
* **Glassmorphism**: Desain card menggunakan background semi-transparan (`rgba(22, 28, 45, 0.45)`) dengan `backdrop-filter: blur(20px)`.
* **Desain Responsif**:
  * Di komputer/laptop, navigasi sidebar berada di sisi kiri.
  * Di HP/tablet, sidebar otomatis tersembunyi (*offscreen drawer*) dan dapat dipicu menggunakan tombol hamburger menu di header dengan overlay blur yang elegan.
  * Formulir input otomatis tersusun 1 kolom pada layar HP.
* **Layout Cetak**: Seluruh hasil cetak dokumen surat dikonfigurasi menggunakan layout **A4 Portrait** agar rapi saat diprint ke kertas fisik.

---

## 5. Perbaikan & Penanganan Error Terbaru

1. **Tombol Logout Terhambat**:
   * *Masalah*: Logout POST memicu error 419 (Page Expired) jika sesi pengguna telah habis/idle.
   * *Solusi*: Mengubah rute logout menjadi `Route::match(['get', 'post'])` dan mengganti form logout di layout utama menjadi link `<a>` berbasis GET. Controller menggunakan `session()->invalidate()` and `session()->regenerateToken()` agar pembersihan sesi lebih aman.
2. **Undefined Variable `$isAdmin`**:
   * *Masalah*: Error 500 saat memuat `/surat/dashboard` karena variabel otorisasi belum didefinisikan secara lokal di view dashboard surat.
   * *Solusi*: Menambahkan inisialisasi `$user` dan `$isAdmin` dari session aktif di bagian atas view `surat/dashboard.blade.php`.
3. **Penyelarasan Nama Brand**:
   * Semua referensi nama "L-Khanza" diubah menjadi **SIMRS KHANZA** (termasuk pada judul halaman, logo sidebar, dan portal utama dashboard) agar selaras dengan nama resmi produk.
4. **Pembaruan Tampilan Login**:
   * Menghapus catatan/informasi akun demo uji coba dan menggantinya dengan penayangan hari, tanggal, serta jam digital secara realtime berbahasa Indonesia.
5. **Dinamisasi Nama Instansi pada Portal Menu Utama**:
   * *Masalah*: Judul halaman dan tajuk utama portal masih bertuliskan teks statis "Portal Modul Utama" dan "SIMRS KHANZA".
   * *Solusi*: Mengubah controller `DashboardController.php` agar mengambil data `nama_instansi` secara langsung dari tabel `setting` di basis data. Nilai dinamis ini kemudian dilewatkan ke `dashboard.blade.php` untuk menampilkan nama instansi (seperti "RS SIMRS") secara otomatis sebagai pengganti "Portal Modul Utama" pada header halaman dan kartu portal utama.

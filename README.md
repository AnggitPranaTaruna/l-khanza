# L-Khanza SIMRS (Web Port Kepegawaian & Cuti)

L-Khanza adalah aplikasi web berbasis framework **Laravel 12** yang diporting dari sistem **SIMRS Khanza (Desktop)**. Aplikasi ini mengintegrasikan database, sistem enkripsi kredensial (AES-128-ECB), dan otorisasi matriks hak akses yang sama persis dengan SIMRS Khanza Java Swing Desktop.

Detail mengenai spesifikasi teknis basis data, sistem enkripsi, dan hak akses dapat dibaca pada file [prd.md](file:///Users/anggit/Project/l-khanza/prd.md).

---

## 💻 1. Cara Menjalankan di macOS (Local Development)

Untuk menjalankan kembali aplikasi di Mac Anda dengan menggunakan PHP bawaan **XAMPP**, ikuti langkah-langkah berikut:

### A. Langkah Menjalankan Aplikasi
1. Buka aplikasi **Terminal** di Mac Anda.
2. Pindah ke direktori proyek L-Khanza:
   ```bash
   cd /Users/anggit/Project/l-khanza
   ```
3. Jalankan server lokal Laravel menggunakan path PHP binary dari XAMPP Anda:
   ```bash
   /Applications/XAMPP/xamppfiles/bin/php artisan serve --port=8080
   ```
4. Buka browser (Chrome/Safari) dan akses alamat:
   👉 **[http://127.0.0.1:8080](http://127.0.0.1:8080)**

> [!NOTE]  
> **Tidak perlu menjalankan `npm run dev`** saat pengembangan biasa. Proyek ini menggunakan **Vanilla CSS** statis di [style.css](file:///Users/anggit/Project/l-khanza/public/css/style.css) untuk mereplikasi tampilan Khansa desktop secara presisi dan cepat tanpa kompilasi tambahan.

### B. Mengatasi Port 8080 Terpakai (Port Conflict)
Jika muncul pesan error port 8080 sudah digunakan oleh proses lain, Anda dapat mencari tahu PID (Process ID) dan mematikannya:
1. Cari tahu program apa yang menggunakan port 8080:
   ```bash
   lsof -i :8080
   ```
2. Hentikan proses tersebut berdasarkan PID yang muncul (misal PID-nya `12345`):
   ```bash
   kill -9 12345
   ```
3. Setelah itu, jalankan kembali perintah `/Applications/XAMPP/xamppfiles/bin/php artisan serve --port=8080`.

### C. Mengatasi Sesi Login Tidak Mau Keluar (Reset Session)
Sistem dikonfigurasi untuk menghapus sesi saat browser ditutup (`SESSION_EXPIRE_ON_CLOSE=true`). Jika Anda ingin menghapus paksa seluruh sesi login di Mac untuk memulai dari awal:
1. Jalankan perintah ini di Terminal proyek Anda:
   ```bash
   rm -f storage/framework/sessions/*
   ```

---

## 🌐 2. Cara Install & Deploy di aaPanel (Linux VPS)

Laravel 12 memerlukan spesifikasi server minimal **PHP 8.2** atau **PHP 8.3**. Berikut adalah panduan langkah demi langkah cara memasang L-Khanza di control panel **aaPanel**:

### Langkah 1: Persiapan Server di aaPanel
1. Masuk ke dashboard aaPanel Anda.
2. Buka menu **App Store** di panel sebelah kiri.
3. Cari dan pastikan Anda sudah menginstal:
   * **Nginx** (atau Apache).
   * **PHP 8.2** atau **PHP 8.3** (Wajib, jangan PHP 8.1 ke bawah karena Laravel 12 tidak mendukungnya).
   * **MySQL** (tempat database `sik` SIMRS Khanza berada).
4. Klik pada pengaturan **PHP 8.2/8.3** -> Pilih menu **Install extensions** -> Pasang ekstensi **`fileinfo`** dan **`opcache`** jika belum terpasang.

### Langkah 2: Membuat Website Baru
1. Buka menu **Website** -> Klik tombol **Add site**.
2. Masukkan nama domain Anda (misal `lkhanza.internal` atau domain publik Anda).
3. Pada pilihan **PHP Version**, pilih **PHP 8.2** atau **PHP 8.3**.
4. Klik **Submit**.

### Langkah 3: Mengunggah Berkas Proyek
1. Masuk ke direktori website Anda (biasanya di `/www/wwwroot/nama-domain-anda`).
2. Hapus file bawaan aaPanel seperti `index.html` dan `404.html`.
3. Unggah seluruh file proyek L-Khanza Anda (atau lakukan `git clone` langsung dari repositori GitHub Anda ke dalam folder tersebut).

### Langkah 4: Pengaturan Direktori Jalan (Running Directory)
Karena Laravel menyajikan aplikasi lewat folder `public`, Anda harus mengarahkan root web server ke sana:
1. Di menu **Website** aaPanel, klik nama domain situs Anda untuk membuka **Site Settings**.
2. Pilih tab **Site directory**.
3. Pada bagian **Running directory**, ubah dropdown dari `/` menjadi **`/public`**.
4. Klik **Save**.

### Langkah 5: Mengatur URL Rewrite (Penting untuk Router Laravel)
Agar rute web Laravel (seperti `/login`, `/kepegawaian/data`, dll.) tidak mengalami error 404 saat diakses:
1. Masih di dalam menu **Site Settings**, pilih tab **URL Rewrite**.
2. Pilih template **`laravel5`** dari dropdown menu.
3. Kode konfigurasi berikut akan otomatis terisi (untuk web server Nginx):
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```
4. Klik **Save**.

### Langkah 6: Konfigurasi File `.env` di Server
1. Buka tab **Files** di aaPanel, lalu cari file **`.env.example`** di folder proyek Anda.
2. Salin atau ubah nama file menjadi **`.env`**.
3. Edit file **`.env`** tersebut dan sesuaikan baris-baris berikut:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=http://domain-anda.com

   # Koneksi ke database sik SIMRS Khanza di server Anda
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sik
   DB_USERNAME=username_database_anda
   DB_PASSWORD=password_database_anda

   # Pastikan timezone Indonesia
   APP_TIMEZONE=Asia/Jakarta

   # Sesi tertutup saat browser ditutup
   SESSION_EXPIRE_ON_CLOSE=true
   ```

### Langkah 7: Menjalankan Composer dan Key Generator
1. Buka Terminal VPS aaPanel Anda (atau gunakan fitur **Terminal** bawaan aaPanel di sebelah kiri).
2. Pindah ke direktori situs Anda:
   ```bash
   cd /www/wwwroot/nama-domain-anda
   ```
3. Pasang semua dependensi PHP dengan Composer:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
4. Generate key enkripsi Laravel:
   ```bash
   php artisan key:generate
   ```

### Langkah 8: Mengatur Izin Folder (Permission)
Web server Nginx (`www`) membutuhkan hak akses baca-tulis ke folder penyimpanan (storage) dan cache Laravel. Jalankan perintah berikut di Terminal VPS Anda:
```bash
# Ubah kepemilikan folder ke user web server (www)
chown -R www:www /www/wwwroot/nama-domain-anda

# Berikan hak akses tulis pada folder storage dan cache
chmod -R 775 /www/wwwroot/nama-domain-anda/storage
chmod -R 775 /www/wwwroot/nama-domain-anda/bootstrap/cache
```

---

## 🔄 3. Cara Upgrade Mengikuti Update GitHub dengan Aman

Apabila ada pembaruan kode (update) di repositori GitHub, ikuti prosedur aman berikut agar konfigurasi lokal `.env` dan database Anda tidak rusak/tertimpa.

### Alur Upgrade Langkah-Demi-Langkah:

#### Langkah 1: Cadangkan Database & File Konfigurasi (Wajib!)
Sebelum melakukan pull kode baru, amankan database `sik` dan file konfigurasi `.env` Anda:
```bash
# 1. Masuk ke folder proyek
cd /www/wwwroot/nama-domain-anda

# 2. Cadangkan database MySQL ke file backup sql
mysqldump -u username_db -p nama_database > backup_db_sebelum_upgrade.sql

# 3. Cadangkan file konfigurasi env Anda
cp .env .env.backup
```

#### Langkah 2: Bersihkan Perubahan Lokal (Clean Workspace)
Untuk mencegah terjadinya konflik penggabungan kode (merge conflict) saat melakukan pull dari GitHub akibat file cache atau perubahan tidak sengaja:
```bash
# Kembalikan semua file yang terubah ke kondisi semula sesuai repositori
git reset --hard HEAD

# Hapus file-file sampah lokal yang tidak terdaftar di git
git clean -fd
```

#### Langkah 3: Ambil Update Kode dari GitHub
Tarik kode terbaru dari cabang utama (`main`):
```bash
git pull origin main
```

#### Langkah 4: Kembalikan Konfigurasi & Perbarui Dependensi PHP
1. Pastikan file `.env` Anda tidak berubah. Jika tertimpa, kembalikan dari file backup:
   ```bash
   # (Lakukan ini HANYA jika file .env Anda terhapus/berubah)
   cp .env.backup .env
   ```
2. Instal pustaka (libraries) baru jika ada penambahan dalam berkas `composer.json`:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

#### Langkah 5: Jalankan Migrasi Database (Jika Ada Perubahan Skema)
Jika update menyertakan perubahan struktur tabel baru atau tambahan kolom migrasi:
```bash
php artisan migrate --force
```

#### Langkah 6: Bersihkan Seluruh Cache Laravel
Setelah update selesai, bersihkan seluruh cache konfigurasi, rute, dan view lama agar Laravel memuat perubahan terbaru secara instan:
```bash
php artisan optimize:clear
```

---

## 🔑 Akun Demo (Uji Coba)

Sistem menggunakan metode enkripsi database dua secret key (username dengan `'nur'` dan password dengan `'windi'`). Berikut akun yang dapat dicoba:

* **Akun Admin Utama (Superuser)**:
  * **ID User / NIK**: `spv`
  * **Password**: `server`
* **Akun Pegawai (Akses Staf Kepegawaian & Cuti)**:
  * **ID User / NIK**: `1001`
  * **Password**: `password123`

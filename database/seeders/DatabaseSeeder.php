<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Bank
        if (DB::table('bank')->count() === 0) {
            DB::table('bank')->insert([
                ['namabank' => 'Bank Mandiri'],
                ['namabank' => 'Bank BNI'],
                ['namabank' => 'Bank BRI'],
                ['namabank' => 'Bank Jateng'],
            ]);
        }

        // 2. Seed Jenjang Jabatan
        if (DB::table('jnj_jabatan')->count() === 0) {
            DB::table('jnj_jabatan')->insert([
                ['kode' => 'DIR', 'nama' => 'Direktur', 'tnj' => 1000000, 'indek' => 1],
                ['kode' => 'DOK', 'nama' => 'Dokter Sp.', 'tnj' => 800000, 'indek' => 2],
                ['kode' => 'PRW', 'nama' => 'Perawat', 'tnj' => 400000, 'indek' => 3],
                ['kode' => 'STF', 'nama' => 'Staf Administrasi', 'tnj' => 200000, 'indek' => 4],
            ]);
        }

        // 3. Seed Kelompok Jabatan
        if (DB::table('kelompok_jabatan')->count() === 0) {
            DB::table('kelompok_jabatan')->insert([
                ['kode_kelompok' => 'MED', 'nama_kelompok' => 'Medis', 'indek' => 1],
                ['kode_kelompok' => 'PAR', 'nama_kelompok' => 'Paramedis', 'indek' => 2],
                ['kode_kelompok' => 'ADM', 'nama_kelompok' => 'Administrasi', 'indek' => 3],
            ]);
        }

        // 4. Seed Resiko Kerja
        if (DB::table('resiko_kerja')->count() === 0) {
            DB::table('resiko_kerja')->insert([
                ['kode_resiko' => 'TNG', 'nama_resiko' => 'Tinggi', 'indek' => 1],
                ['kode_resiko' => 'SED', 'nama_resiko' => 'Sedang', 'indek' => 2],
                ['kode_resiko' => 'RND', 'nama_resiko' => 'Rendah', 'indek' => 3],
            ]);
        }

        // 5. Seed Emergency Index
        if (DB::table('emergency_index')->count() === 0) {
            DB::table('emergency_index')->insert([
                ['kode_emergency' => 'EM1', 'nama_emergency' => 'Gawat Darurat 1', 'indek' => 1],
                ['kode_emergency' => 'EM2', 'nama_emergency' => 'Gawat Darurat 2', 'indek' => 2],
                ['kode_emergency' => 'EM3', 'nama_emergency' => 'Non Gawat Darurat', 'indek' => 3],
            ]);
        }

        // 6. Seed Departemen
        if (DB::table('departemen')->count() === 0) {
            DB::table('departemen')->insert([
                ['dep_id' => 'DIR', 'nama' => 'Direksi'],
                ['dep_id' => 'MED', 'nama' => 'Pelayanan Medis'],
                ['dep_id' => 'KEA', 'nama' => 'Keperawatan'],
                ['dep_id' => 'ADM', 'nama' => 'Keuangan & Adm'],
            ]);
        }

        // 7. Seed Bidang
        if (DB::table('bidang')->count() === 0) {
            DB::table('bidang')->insert([
                ['nama' => 'Medis'],
                ['nama' => 'Keperawatan'],
                ['nama' => 'Administrasi'],
                ['nama' => 'Keuangan'],
            ]);
        }

        // 8. Seed Status WP (Wajib Pajak)
        if (DB::table('stts_wp')->count() === 0) {
            DB::table('stts_wp')->insert([
                ['stts' => 'TK/0', 'ktg' => 'Tidak Kawin'],
                ['stts' => 'K/0', 'ktg' => 'Kawin Tanpa Anak'],
                ['stts' => 'K/1', 'ktg' => 'Kawin Anak 1'],
            ]);
        }

        // 9. Seed Status Kerja
        if (DB::table('stts_kerja')->count() === 0) {
            DB::table('stts_kerja')->insert([
                ['stts' => 'KTY', 'ktg' => 'Tetap Yayasan', 'indek' => 1],
                ['stts' => 'KTT', 'ktg' => 'Tetap Total', 'indek' => 2],
                ['stts' => 'HON', 'ktg' => 'Honorer', 'indek' => 3],
            ]);
        }

        // 10. Seed Pendidikan
        if (DB::table('pendidikan')->count() === 0) {
            DB::table('pendidikan')->insert([
                ['tingkat' => 'Spesialis', 'indek' => 1, 'gapok1' => 5000000, 'kenaikan' => 200000, 'maksimal' => 20],
                ['tingkat' => 'S1 Profesi', 'indek' => 2, 'gapok1' => 3500000, 'kenaikan' => 150000, 'maksimal' => 20],
                ['tingkat' => 'D3 Keperawatan', 'indek' => 3, 'gapok1' => 2800000, 'kenaikan' => 100000, 'maksimal' => 20],
                ['tingkat' => 'SMA/SMK', 'indek' => 4, 'gapok1' => 2000000, 'kenaikan' => 80000, 'maksimal' => 20],
            ]);
        }

        // 11. Seed Admin Account if empty
        if (DB::table('admin')->count() === 0) {
            DB::table('admin')->insert([
                'usere' => DB::raw("AES_ENCRYPT('spv', 'nur')"),
                'passworde' => DB::raw("AES_ENCRYPT('server', 'windi')"),
            ]);
        }

        // 12. Seed Employee for Testing
        if (DB::table('pegawai')->count() === 0) {
            DB::table('pegawai')->insert([
                'nik' => '1001',
                'nama' => 'Ahmad Fauzi',
                'jk' => 'Pria',
                'jbtn' => 'Staf HRD',
                'jnj_jabatan' => 'STF',
                'kode_kelompok' => 'ADM',
                'kode_resiko' => 'RND',
                'kode_emergency' => 'EM3',
                'departemen' => 'ADM',
                'bidang' => 'Administrasi',
                'stts_wp' => 'TK/0',
                'stts_kerja' => 'KTY',
                'npwp' => '123456789',
                'pendidikan' => 'SMA/SMK',
                'gapok' => 2000000,
                'tmp_lahir' => 'Jakarta',
                'tgl_lahir' => '1995-05-15',
                'alamat' => 'Jl. Sudirman No. 10',
                'kota' => 'Jakarta',
                'mulai_kerja' => '2020-01-01',
                'ms_kerja' => 'FT>1',
                'indexins' => 'ADM',
                'bpd' => 'Bank Mandiri',
                'rekening' => '1234567890',
                'stts_aktif' => 'AKTIF',
                'wajibmasuk' => 25,
                'pengurang' => 0,
                'indek' => 5,
                'cuti_diambil' => 0,
                'dankes' => 0,
                'no_ktp' => '3171012345670001',
            ]);

            DB::table('pegawai')->insert([
                'nik' => '1002',
                'nama' => 'Siti Aminah',
                'jk' => 'Wanita',
                'jbtn' => 'Perawat',
                'jnj_jabatan' => 'PRW',
                'kode_kelompok' => 'PAR',
                'kode_resiko' => 'SED',
                'kode_emergency' => 'EM2',
                'departemen' => 'KEA',
                'bidang' => 'Keperawatan',
                'stts_wp' => 'TK/0',
                'stts_kerja' => 'HON',
                'npwp' => '987654321',
                'pendidikan' => 'D3 Keperawatan',
                'gapok' => 2800000,
                'tmp_lahir' => 'Surabaya',
                'tgl_lahir' => '1997-08-20',
                'alamat' => 'Jl. Pemuda No. 45',
                'kota' => 'Surabaya',
                'mulai_kerja' => '2022-03-01',
                'ms_kerja' => 'FT>1',
                'indexins' => 'KEA',
                'bpd' => 'Bank BRI',
                'rekening' => '0987654321',
                'stts_aktif' => 'AKTIF',
                'wajibmasuk' => 25,
                'pengurang' => 0,
                'indek' => 4,
                'cuti_diambil' => 0,
                'dankes' => 0,
                'no_ktp' => '3171012345670002',
            ]);
        }

        // 13. Seed User Account linking to Employee (using NIK)
        if (DB::table('user')->count() === 0) {
            $columns = Schema::getColumnListing('user');
            $userData = [];
            foreach ($columns as $column) {
                if ($column === 'id_user') {
                    $userData[$column] = DB::raw("AES_ENCRYPT('1001', 'nur')"); // NIK 1001 as username
                } elseif ($column === 'password') {
                    $userData[$column] = DB::raw("AES_ENCRYPT('password123', 'windi')");
                } else {
                    // check if the column has a default value, or set to 'false' if enum
                    $userData[$column] = 'false';
                }
            }

            // Set specific permissions
            $userData['pegawai_admin'] = 'true';
            $userData['pegawai_user'] = 'true';
            $userData['pengajuan_cuti'] = 'true';

            DB::table('user')->insert($userData);
        }
    }
}

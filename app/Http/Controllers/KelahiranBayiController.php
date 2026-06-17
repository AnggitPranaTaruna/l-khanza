<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KelahiranBayiController extends Controller
{
    /**
     * Display a listing of registered baby births.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $tgl_mulai = $request->get('tgl_mulai', date('Y-m-d', strtotime('-1 month')));
        $tgl_selesai = $request->get('tgl_selesai', date('Y-m-d'));

        $query = DB::table('pasien')
            ->join('pasien_bayi', 'pasien.no_rkm_medis', '=', 'pasien_bayi.no_rkm_medis')
            ->leftJoin('pegawai', 'pasien_bayi.penolong', '=', 'pegawai.nik')
            ->select(
                'pasien.*',
                'pasien_bayi.*',
                'pegawai.nama as nama_penolong'
            );

        // Filter by date range (based on baby's tgl_lahir)
        $query->whereBetween('pasien.tgl_lahir', [$tgl_mulai, $tgl_selesai]);

        // Filter by keyword search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pasien.no_rkm_medis', 'like', "%{$search}%")
                  ->orWhere('pasien.nm_pasien', 'like', "%{$search}%")
                  ->orWhere('pasien_bayi.no_skl', 'like', "%{$search}%")
                  ->orWhere('pasien_bayi.nama_ayah', 'like', "%{$search}%")
                  ->orWhere('pasien.nm_ibu', 'like', "%{$search}%")
                  ->orWhere('pasien_bayi.diagnosa', 'like', "%{$search}%");
            });
        }

        $bayiList = $query->orderBy('pasien.no_rkm_medis', 'desc')->paginate(15);

        // Fetch active employees/midwives/doctors for lookup
        $employees = DB::table('pegawai')
            ->where('stts_aktif', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();

        // Generate next sequence numbers
        $nextRM = $this->generateNoRkMedis();
        $nextSKL = $this->generateNoSkl();

        return view('surat.kelahiran.index', compact('bayiList', 'search', 'tgl_mulai', 'tgl_selesai', 'employees', 'nextRM', 'nextSKL'));
    }

    /**
     * Store a newly created baby patient & baby birth record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Pasien Fields
            'no_rkm_medis' => 'required|string|max:15',
            'nm_pasien' => 'required|string|max:40',
            'jk' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'nm_ibu' => 'required|string|max:40',
            'alamat' => 'required|string|max:200',
            
            // Pasien Bayi Fields
            'umur_ibu' => 'required|string|max:8',
            'nama_ayah' => 'required|string|max:50',
            'umur_ayah' => 'required|string|max:8',
            'berat_badan' => 'required|string|max:10',
            'panjang_badan' => 'required|string|max:10',
            'lingkar_kepala' => 'required|string|max:10',
            'lingkar_perut' => 'nullable|string|max:10',
            'lingkar_dada' => 'nullable|string|max:10',
            'proses_lahir' => 'required|string|max:60',
            'anakke' => 'required|string|max:2',
            'jam_lahir' => 'required|string', // HH:MM:SS
            'keterangan' => 'nullable|string|max:50',
            'diagnosa' => 'nullable|string|max:60',
            'penyulit_kehamilan' => 'nullable|string|max:60',
            'ketuban' => 'nullable|string|max:60',
            'penolong' => 'required|string|exists:pegawai,nik',
            'no_skl' => 'required|string|max:30',
            'g' => 'nullable|string|max:10',
            'p' => 'nullable|string|max:10',
            'a' => 'nullable|string|max:10',
            
            // APGAR scores
            'f1' => 'required|string|max:1',
            'u1' => 'required|string|max:1',
            't1' => 'required|string|max:1',
            'r1' => 'required|string|max:1',
            'w1' => 'required|string|max:1',
            'n1' => 'required|string|max:20', // Total 1 min
            'f5' => 'required|string|max:1',
            'u5' => 'required|string|max:1',
            't5' => 'required|string|max:1',
            'r5' => 'required|string|max:1',
            'w5' => 'required|string|max:1',
            'n5' => 'required|string|max:2', // Total 5 min
            'f10' => 'required|string|max:1',
            'u10' => 'required|string|max:1',
            't10' => 'required|string|max:1',
            'r10' => 'required|string|max:1',
            'w10' => 'required|string|max:1',
            'n10' => 'required|string|max:2', // Total 10 min
            
            'resusitas' => 'nullable|string|max:100',
            'obat_diberikan' => 'nullable|string|max:300',
            'mikasi' => 'nullable|string|max:100',
            'mikonium' => 'nullable|string|max:100',
        ]);

        // Check duplicates
        if (DB::table('pasien')->where('no_rkm_medis', $validated['no_rkm_medis'])->exists()) {
            return response()->json(['error' => 'Nomor Rekam Medis (RM) sudah terdaftar!'], 422);
        }
        if (DB::table('pasien_bayi')->where('no_skl', $validated['no_skl'])->exists()) {
            return response()->json(['error' => 'Nomor Surat Keterangan Lahir (SKL) sudah terdaftar!'], 422);
        }

        // Initialize dependencies
        $this->ensureDefaultDemographics();

        DB::beginTransaction();
        try {
            // 1. Insert into pasien table
            DB::table('pasien')->insert([
                'no_rkm_medis' => $validated['no_rkm_medis'],
                'nm_pasien' => $validated['nm_pasien'],
                'no_ktp' => '-',
                'jk' => $validated['jk'],
                'tmp_lahir' => '-',
                'tgl_lahir' => $validated['tgl_lahir'],
                'nm_ibu' => $validated['nm_ibu'],
                'alamat' => $validated['alamat'],
                'gol_darah' => '-',
                'pekerjaan' => '-',
                'stts_nikah' => 'BELUM MENIKAH',
                'agama' => '-',
                'tgl_daftar' => date('Y-m-d'),
                'no_tlp' => '0',
                'umur' => '0 Bl', // Baby age is 0 months at birth
                'pnd' => '-',
                'keluarga' => 'AYAH',
                'namakeluarga' => $validated['nama_ayah'],
                'kd_pj' => '-',
                'no_peserta' => '',
                'kd_kel' => 0,
                'kd_kec' => 0,
                'kd_kab' => 0,
                'pekerjaanpj' => '-',
                'alamatpj' => $validated['alamat'],
                'kelurahanpj' => '-',
                'kecamatanpj' => '-',
                'kabupatenpj' => '-',
                'perusahaan_pasien' => '-',
                'suku_bangsa' => 0,
                'bahasa_pasien' => 0,
                'cacat_fisik' => 0,
                'email' => '-',
                'nip' => '-',
                'kd_prop' => 0,
                'propinsipj' => '-',
            ]);

            // 2. Insert into pasien_bayi table
            DB::table('pasien_bayi')->insert([
                'no_rkm_medis' => $validated['no_rkm_medis'],
                'umur_ibu' => $validated['umur_ibu'],
                'nama_ayah' => $validated['nama_ayah'],
                'umur_ayah' => $validated['umur_ayah'],
                'berat_badan' => $validated['berat_badan'],
                'panjang_badan' => $validated['panjang_badan'],
                'lingkar_kepala' => $validated['lingkar_kepala'],
                'proses_lahir' => $validated['proses_lahir'],
                'anakke' => $validated['anakke'],
                'jam_lahir' => $validated['jam_lahir'],
                'keterangan' => $validated['keterangan'] ?? '-',
                'diagnosa' => $validated['diagnosa'] ?? '-',
                'penyulit_kehamilan' => $validated['penyulit_kehamilan'] ?? '-',
                'ketuban' => $validated['ketuban'] ?? '-',
                'lingkar_perut' => $validated['lingkar_perut'] ?? '-',
                'lingkar_dada' => $validated['lingkar_dada'] ?? '-',
                'penolong' => $validated['penolong'],
                'no_skl' => $validated['no_skl'],
                'g' => $validated['g'] ?? '-',
                'p' => $validated['p'] ?? '-',
                'a' => $validated['a'] ?? '-',
                'f1' => $validated['f1'],
                'u1' => $validated['u1'],
                't1' => $validated['t1'],
                'r1' => $validated['r1'],
                'w1' => $validated['w1'],
                'n1' => $validated['n1'],
                'f5' => $validated['f5'],
                'u5' => $validated['u5'],
                't5' => $validated['t5'],
                'r5' => $validated['r5'],
                'w5' => $validated['w5'],
                'n5' => $validated['n5'],
                'f10' => $validated['f10'],
                'u10' => $validated['u10'],
                't10' => $validated['t10'],
                'r10' => $validated['r10'],
                'w10' => $validated['w10'],
                'n10' => $validated['n10'],
                'resusitas' => $validated['resusitas'] ?? '-',
                'obat_diberikan' => $validated['obat_diberikan'] ?? '-',
                'mikasi' => $validated['mikasi'] ?? '-',
                'mikonium' => $validated['mikonium'] ?? '-',
            ]);

            // 3. Keep record of generated medical record number to set_no_rkm_medis
            DB::table('set_no_rkm_medis')->delete();
            DB::table('set_no_rkm_medis')->insert(['no_rkm_medis' => $validated['no_rkm_medis']]);

            DB::commit();
            return response()->json(['success' => 'Pendaftaran Kelahiran Bayi berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified baby birth record.
     */
    public function update(Request $request, $no_rkm_medis)
    {
        $no_rkm_medis = urldecode($no_rkm_medis);

        $validated = $request->validate([
            // Pasien Fields
            'nm_pasien' => 'required|string|max:40',
            'jk' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'nm_ibu' => 'required|string|max:40',
            'alamat' => 'required|string|max:200',
            
            // Pasien Bayi Fields
            'umur_ibu' => 'required|string|max:8',
            'nama_ayah' => 'required|string|max:50',
            'umur_ayah' => 'required|string|max:8',
            'berat_badan' => 'required|string|max:10',
            'panjang_badan' => 'required|string|max:10',
            'lingkar_kepala' => 'required|string|max:10',
            'lingkar_perut' => 'nullable|string|max:10',
            'lingkar_dada' => 'nullable|string|max:10',
            'proses_lahir' => 'required|string|max:60',
            'anakke' => 'required|string|max:2',
            'jam_lahir' => 'required|string',
            'keterangan' => 'nullable|string|max:50',
            'diagnosa' => 'nullable|string|max:60',
            'penyulit_kehamilan' => 'nullable|string|max:60',
            'ketuban' => 'nullable|string|max:60',
            'penolong' => 'required|string|exists:pegawai,nik',
            'no_skl' => 'required|string|max:30',
            'g' => 'nullable|string|max:10',
            'p' => 'nullable|string|max:10',
            'a' => 'nullable|string|max:10',
            
            // APGAR scores
            'f1' => 'required|string|max:1',
            'u1' => 'required|string|max:1',
            't1' => 'required|string|max:1',
            'r1' => 'required|string|max:1',
            'w1' => 'required|string|max:1',
            'n1' => 'required|string|max:20',
            'f5' => 'required|string|max:1',
            'u5' => 'required|string|max:1',
            't5' => 'required|string|max:1',
            'r5' => 'required|string|max:1',
            'w5' => 'required|string|max:1',
            'n5' => 'required|string|max:2',
            'f10' => 'required|string|max:1',
            'u10' => 'required|string|max:1',
            't10' => 'required|string|max:1',
            'r10' => 'required|string|max:1',
            'w10' => 'required|string|max:1',
            'n10' => 'required|string|max:2',
            
            'resusitas' => 'nullable|string|max:100',
            'obat_diberikan' => 'nullable|string|max:300',
            'mikasi' => 'nullable|string|max:100',
            'mikonium' => 'nullable|string|max:100',
        ]);

        // Check if no_skl is duplicate on other babies
        $sklExists = DB::table('pasien_bayi')
            ->where('no_skl', $validated['no_skl'])
            ->where('no_rkm_medis', '!=', $no_rkm_medis)
            ->exists();
        if ($sklExists) {
            return response()->json(['error' => 'Nomor Surat Keterangan Lahir (SKL) sudah terdaftar pada bayi lain!'], 422);
        }

        DB::beginTransaction();
        try {
            // 1. Update pasien table
            DB::table('pasien')
                ->where('no_rkm_medis', $no_rkm_medis)
                ->update([
                    'nm_pasien' => $validated['nm_pasien'],
                    'jk' => $validated['jk'],
                    'tgl_lahir' => $validated['tgl_lahir'],
                    'nm_ibu' => $validated['nm_ibu'],
                    'alamat' => $validated['alamat'],
                    'namakeluarga' => $validated['nama_ayah'],
                    'alamatpj' => $validated['alamat'],
                ]);

            // 2. Update pasien_bayi table
            DB::table('pasien_bayi')
                ->where('no_rkm_medis', $no_rkm_medis)
                ->update([
                    'umur_ibu' => $validated['umur_ibu'],
                    'nama_ayah' => $validated['nama_ayah'],
                    'umur_ayah' => $validated['umur_ayah'],
                    'berat_badan' => $validated['berat_badan'],
                    'panjang_badan' => $validated['panjang_badan'],
                    'lingkar_kepala' => $validated['lingkar_kepala'],
                    'proses_lahir' => $validated['proses_lahir'],
                    'anakke' => $validated['anakke'],
                    'jam_lahir' => $validated['jam_lahir'],
                    'keterangan' => $validated['keterangan'] ?? '-',
                    'diagnosa' => $validated['diagnosa'] ?? '-',
                    'penyulit_kehamilan' => $validated['penyulit_kehamilan'] ?? '-',
                    'ketuban' => $validated['ketuban'] ?? '-',
                    'lingkar_perut' => $validated['lingkar_perut'] ?? '-',
                    'lingkar_dada' => $validated['lingkar_dada'] ?? '-',
                    'penolong' => $validated['penolong'],
                    'no_skl' => $validated['no_skl'],
                    'g' => $validated['g'] ?? '-',
                    'p' => $validated['p'] ?? '-',
                    'a' => $validated['a'] ?? '-',
                    'f1' => $validated['f1'],
                    'u1' => $validated['u1'],
                    't1' => $validated['t1'],
                    'r1' => $validated['r1'],
                    'w1' => $validated['w1'],
                    'n1' => $validated['n1'],
                    'f5' => $validated['f5'],
                    'u5' => $validated['u5'],
                    't5' => $validated['t5'],
                    'r5' => $validated['r5'],
                    'w5' => $validated['w5'],
                    'n5' => $validated['n5'],
                    'f10' => $validated['f10'],
                    'u10' => $validated['u10'],
                    't10' => $validated['t10'],
                    'r10' => $validated['r10'],
                    'w10' => $validated['w10'],
                    'n10' => $validated['n10'],
                    'resusitas' => $validated['resusitas'] ?? '-',
                    'obat_diberikan' => $validated['obat_diberikan'] ?? '-',
                    'mikasi' => $validated['mikasi'] ?? '-',
                    'mikonium' => $validated['mikonium'] ?? '-',
                ]);

            DB::commit();
            return response()->json(['success' => 'Data Kelahiran Bayi berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal mengubah data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete the specified baby birth record.
     */
    public function destroy($no_rkm_medis)
    {
        $no_rkm_medis = urldecode($no_rkm_medis);

        DB::beginTransaction();
        try {
            // Delete from baby table
            DB::table('pasien_bayi')->where('no_rkm_medis', $no_rkm_medis)->delete();
            // Delete from patient table
            DB::table('pasien')->where('no_rkm_medis', $no_rkm_medis)->delete();

            DB::commit();
            return response()->json(['success' => 'Data Kelahiran Bayi berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Print out Surat Keterangan Lahir (SKL).
     */
    public function cetak($no_rkm_medis)
    {
        $no_rkm_medis = urldecode($no_rkm_medis);

        $bayi = DB::table('pasien')
            ->join('pasien_bayi', 'pasien.no_rkm_medis', '=', 'pasien_bayi.no_rkm_medis')
            ->leftJoin('pegawai', 'pasien_bayi.penolong', '=', 'pegawai.nik')
            ->select(
                'pasien.*',
                'pasien_bayi.*',
                'pegawai.nama as nama_penolong',
                'pegawai.jbtn as jabatan_penolong'
            )
            ->where('pasien.no_rkm_medis', $no_rkm_medis)
            ->first();

        if (!$bayi) {
            abort(404, 'Data Kelahiran Bayi tidak ditemukan.');
        }

        // Fetch hospital setting info
        $rs = DB::table('setting')->first() ?: (object)[
            'nama_instansi' => 'RUMAH SAKIT ASY-SYIFA MEDIKA',
            'alamat_instansi' => 'Jl. Jenderal Sudirman No. 12',
            'kabupaten' => 'Tanggamus',
            'propinsi' => 'Lampung',
            'kontak' => '0729-123456',
            'email' => 'contact@asy-syifa.com',
            'logo' => null,
        ];

        // Format dates
        $tgl_surat_formatted = Carbon::now()->translatedFormat('d F Y');
        $tgl_lahir_formatted = Carbon::parse($bayi->tgl_lahir)->translatedFormat('d F Y');

        return view('surat.kelahiran.cetak', compact('bayi', 'rs', 'tgl_surat_formatted', 'tgl_lahir_formatted'));
    }

    /**
     * Get JSON endpoint for newly generated numbers.
     */
    public function getNewNo(Request $request)
    {
        $dateStr = $request->get('date') ?: date('Y-m-d');
        
        $nextRM = $this->generateNoRkMedis();
        $nextSKL = $this->generateNoSkl($dateStr);

        return response()->json([
            'no_rkm_medis' => $nextRM,
            'no_skl' => $nextSKL
        ]);
    }

    /**
     * Generate medical record number.
     */
    private function generateNoRkMedis()
    {
        $config = DB::table('set_urut_no_rkm_medis')->first() ?: (object)[
            'urutan' => 'Straight',
            'tahun' => 'No',
            'bulan' => 'No',
            'posisi_tahun_bulan' => 'Belakang'
        ];

        $date = date('Y-m-d');
        $yearCode = date('y', strtotime($date));
        $monthCode = date('m', strtotime($date));

        $awalanTahun = ($config->tahun === 'Yes') ? $yearCode : '';
        $awalanBulan = ($config->bulan === 'Yes') ? $monthCode : '';

        $maxNoSet = DB::table('set_no_rkm_medis')->max('no_rkm_medis');
        $maxNoPasien = DB::table('pasien')->max('no_rkm_medis');

        $maxNoSetInt = $this->extractNumberOnly($maxNoSet);
        $maxNoPasienInt = $this->extractNumberOnly($maxNoPasien);

        $maxVal = max($maxNoSetInt, $maxNoPasienInt);
        $nextNo = $maxVal + 1;
        $numStr = str_pad($nextNo, 6, '0', STR_PAD_LEFT);

        if ($config->posisi_tahun_bulan === 'Depan') {
            return $awalanTahun . $awalanBulan . $numStr;
        } else {
            if ($awalanTahun || $awalanBulan) {
                return $numStr . '-' . $awalanBulan . $awalanTahun;
            } else {
                return $numStr;
            }
        }
    }

    /**
     * Extract integer from alphanumeric/formatted string.
     */
    private function extractNumberOnly($rm)
    {
        if (!$rm) return 0;
        $clean = preg_replace('/[^0-9]/', '', $rm);
        return intval($clean) ?: 0;
    }

    /**
     * Generate sequential number for SKL letter.
     */
    private function generateNoSkl($dateStr = null)
    {
        $date = $dateStr ?: date('Y-m-d');
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        
        $yearMonthPattern = date('Y-m', strtotime($date));

        $maxSkl = DB::table('pasien')
            ->join('pasien_bayi', 'pasien.no_rkm_medis', '=', 'pasien_bayi.no_rkm_medis')
            ->where('pasien.tgl_lahir', 'like', "%{$yearMonthPattern}%")
            ->select('pasien_bayi.no_skl')
            ->get()
            ->map(function ($row) {
                $parts = explode('/', $row->no_skl);
                return (int)$parts[0];
            })
            ->max();

        $nextSkl = ($maxSkl ?: 0) + 1;
        $formattedSkl = str_pad($nextSkl, 4, '0', STR_PAD_LEFT);

        return "{$formattedSkl}/RM-SKL/{$month}/{$year}";
    }

    /**
     * Ensure default baseline configuration keys exist in demographic tables.
     */
    private function ensureDefaultDemographics()
    {
        DB::table('cacat_fisik')->insertOrIgnore(['id' => 0, 'nama_cacat' => '-']);
        DB::table('penjab')->insertOrIgnore([
            'kd_pj' => '-', 'png_jawab' => '-', 'nama_perusahaan' => '-',
            'alamat_asuransi' => '-', 'no_telp' => '-', 'attn' => '-', 'status' => '1'
        ]);
        DB::table('kelurahan')->insertOrIgnore(['kd_kel' => 0, 'nm_kel' => '-']);
        DB::table('kecamatan')->insertOrIgnore(['kd_kec' => 0, 'nm_kec' => '-']);
        DB::table('kabupaten')->insertOrIgnore(['kd_kab' => 0, 'nm_kab' => '-']);
        DB::table('propinsi')->insertOrIgnore(['kd_prop' => 0, 'nm_prop' => '-']);
        DB::table('bahasa_pasien')->insertOrIgnore(['id' => 0, 'nama_bahasa' => '-']);
        DB::table('suku_bangsa')->insertOrIgnore(['id' => 0, 'nama_suku_bangsa' => '-']);
        DB::table('perusahaan_pasien')->insertOrIgnore([
            'kode_perusahaan' => '-', 'nama_perusahaan' => '-', 'alamat' => '-', 'no_telp' => '-', 'kota' => '-'
        ]);
    }
}

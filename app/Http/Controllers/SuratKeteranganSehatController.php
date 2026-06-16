<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuratKeteranganSehatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $tgl_mulai = $request->get('tgl_mulai', date('Y-m-d', strtotime('-1 month')));
        $tgl_selesai = $request->get('tgl_selesai', date('Y-m-d'));

        $query = DB::table('surat_keterangan_sehat')
            ->join('reg_periksa', 'surat_keterangan_sehat.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->select(
                'surat_keterangan_sehat.*',
                'reg_periksa.no_rkm_medis',
                'pasien.nm_pasien'
            );

        // Filter by date range
        $query->whereBetween('surat_keterangan_sehat.tanggalsurat', [$tgl_mulai, $tgl_selesai]);

        // Filter by keyword search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('surat_keterangan_sehat.no_surat', 'like', "%{$search}%")
                  ->orWhere('surat_keterangan_sehat.no_rawat', 'like', "%{$search}%")
                  ->orWhere('reg_periksa.no_rkm_medis', 'like', "%{$search}%")
                  ->orWhere('pasien.nm_pasien', 'like', "%{$search}%")
                  ->orWhere('surat_keterangan_sehat.keperluan', 'like', "%{$search}%");
            });
        }

        $suratList = $query->orderBy('surat_keterangan_sehat.no_surat', 'asc')->paginate(15);

        return view('surat.sehat.index', compact('suratList', 'search', 'tgl_mulai', 'tgl_selesai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:35',
            'no_rawat' => 'required|string|max:17',
            'tanggalsurat' => 'required|date',
            'berat' => 'required|string|max:3',
            'tinggi' => 'required|string|max:3',
            'tensi' => 'required|string|max:8',
            'suhu' => 'required|string|max:4',
            'butawarna' => 'required|in:Ya,Tidak',
            'keperluan' => 'required|string|max:100',
            'kesimpulan' => 'required|in:Sehat,Tidak Sehat',
        ]);

        // Check if no_surat already exists
        $exists = DB::table('surat_keterangan_sehat')->where('no_surat', $validated['no_surat'])->exists();
        if ($exists) {
            return response()->json(['error' => 'Nomor Surat sudah terdaftar!'], 422);
        }

        // Insert into database
        DB::table('surat_keterangan_sehat')->insert([
            'no_surat' => $validated['no_surat'],
            'no_rawat' => $validated['no_rawat'],
            'tanggalsurat' => $validated['tanggalsurat'],
            'berat' => $validated['berat'],
            'tinggi' => $validated['tinggi'],
            'tensi' => $validated['tensi'],
            'suhu' => $validated['suhu'],
            'butawarna' => $validated['butawarna'],
            'keperluan' => $validated['keperluan'],
            'kesimpulan' => $validated['kesimpulan'],
        ]);

        return response()->json(['success' => 'Data Surat Keterangan Sehat berhasil disimpan!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_surat)
    {
        // Decode URL safe parameter
        $no_surat = urldecode($no_surat);

        $validated = $request->validate([
            'no_rawat' => 'required|string|max:17',
            'tanggalsurat' => 'required|date',
            'berat' => 'required|string|max:3',
            'tinggi' => 'required|string|max:3',
            'tensi' => 'required|string|max:8',
            'suhu' => 'required|string|max:4',
            'butawarna' => 'required|in:Ya,Tidak',
            'keperluan' => 'required|string|max:100',
            'kesimpulan' => 'required|in:Sehat,Tidak Sehat',
        ]);

        DB::table('surat_keterangan_sehat')
            ->where('no_surat', $no_surat)
            ->update([
                'no_rawat' => $validated['no_rawat'],
                'tanggalsurat' => $validated['tanggalsurat'],
                'berat' => $validated['berat'],
                'tinggi' => $validated['tinggi'],
                'tensi' => $validated['tensi'],
                'suhu' => $validated['suhu'],
                'butawarna' => $validated['butawarna'],
                'keperluan' => $validated['keperluan'],
                'kesimpulan' => $validated['kesimpulan'],
            ]);

        return response()->json(['success' => 'Data Surat Keterangan Sehat berhasil diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_surat)
    {
        $no_surat = urldecode($no_surat);

        DB::table('surat_keterangan_sehat')->where('no_surat', $no_surat)->delete();

        return response()->json(['success' => 'Data Surat Keterangan Sehat berhasil dihapus!']);
    }

    /**
     * Generate sequential number for letters.
     */
    public function getNewNoSurat(Request $request)
    {
        $dateStr = $request->get('date') ?: date('Y-m-d');
        $time = strtotime($dateStr);
        $year = date('Y', $time);
        $month = (int)date('m', $time);

        $romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
        $romanMonth = $romawi[$month] ?? '';

        // Fetch max sequence number
        $maxNo = DB::table('surat_keterangan_sehat')
            ->whereYear('tanggalsurat', $year)
            ->select('no_surat')
            ->get()
            ->map(function ($row) {
                $parts = explode('/', $row->no_surat);
                return (int)$parts[0];
            })
            ->max();

        $nextNo = ($maxNo ?: 0) + 1;
        $formattedNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

        $newNoSurat = "{$formattedNo}/D/SS/{$romanMonth}/AM/TBB/{$year}";

        return response()->json(['no_surat' => $newNoSurat]);
    }

    /**
     * Lookup active registrations.
     */
    public function getRegistrasiLookup(Request $request)
    {
        $search = $request->get('search');

        $query = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->select(
                'reg_periksa.no_rawat',
                'reg_periksa.no_rkm_medis',
                'pasien.nm_pasien'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reg_periksa.no_rawat', 'like', "%{$search}%")
                  ->orWhere('reg_periksa.no_rkm_medis', 'like', "%{$search}%")
                  ->orWhere('pasien.nm_pasien', 'like', "%{$search}%");
            });
        }

        $registrations = $query->orderBy('reg_periksa.tgl_registrasi', 'desc')->limit(50)->get();

        return response()->json($registrations);
    }

    /**
     * Print out page.
     */
    public function cetak($no_surat)
    {
        $no_surat = urldecode($no_surat);

        $surat = DB::table('surat_keterangan_sehat')
            ->join('reg_periksa', 'surat_keterangan_sehat.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->select(
                'surat_keterangan_sehat.*',
                'reg_periksa.no_rkm_medis',
                'reg_periksa.kd_dokter',
                'reg_periksa.tgl_registrasi',
                'pasien.nm_pasien',
                'pasien.jk',
                'pasien.tmp_lahir',
                'pasien.tgl_lahir',
                'pasien.pekerjaan',
                'pasien.alamat',
                'dokter.nm_dokter'
            )
            ->where('surat_keterangan_sehat.no_surat', $no_surat)
            ->first();

        if (!$surat) {
            abort(404, 'Surat Keterangan Sehat tidak ditemukan.');
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
        $tgl_surat_formatted = Carbon::parse($surat->tanggalsurat)->translatedFormat('d F Y');
        $tgl_lahir_formatted = Carbon::parse($surat->tgl_lahir)->translatedFormat('d F Y');
        
        // Calculate age
        $umur = Carbon::parse($surat->tgl_lahir)->age;

        return view('surat.sehat.cetak', compact('surat', 'rs', 'tgl_surat_formatted', 'tgl_lahir_formatted', 'umur'));
    }
}

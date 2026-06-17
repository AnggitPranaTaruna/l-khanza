<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TukarJagaController extends Controller
{
    /**
     * Display a listing of shift swap requests.
     */
    public function index(Request $request)
    {
        $user = session('khanza_user');
        $isAdmin = $user['role'] === 'admin';

        $search = $request->input('search');
        $tgl_awal = $request->input('tgl_awal', Carbon::now()->startOfMonth()->toDateString());
        $tgl_akhir = $request->input('tgl_akhir', Carbon::now()->toDateString());
        $use_date_filter = $request->input('use_date_filter', 'false');

        $query = DB::table('pengajuan_tukar_jaga')
            ->join('pegawai as p1', 'pengajuan_tukar_jaga.nik_pemohon', '=', 'p1.nik')
            ->join('pegawai as p2', 'pengajuan_tukar_jaga.nik_tukar', '=', 'p2.nik')
            ->leftJoin('pegawai as p3', 'pengajuan_tukar_jaga.nik_pj', '=', 'p3.nik')
            ->select(
                'pengajuan_tukar_jaga.*',
                'p1.nama as nama_pemohon',
                'p1.bidang as bidang_pemohon',
                'p1.departemen as departemen_pemohon',
                'p2.nama as nama_tukar',
                'p3.nama as nama_pj'
            );

        // If not admin, regular user can only see their own requests (either as applicant or replacement)
        if (!$isAdmin) {
            $query->where(function($q) use ($user) {
                $q->where('pengajuan_tukar_jaga.nik_pemohon', $user['username'])
                  ->orWhere('pengajuan_tukar_jaga.nik_tukar', $user['username'])
                  ->orWhere('pengajuan_tukar_jaga.nik_pj', $user['username']);
            });
        }

        // Apply Date Filter if checked
        if ($use_date_filter === 'true') {
            $query->whereBetween('pengajuan_tukar_jaga.tanggal', [$tgl_awal, $tgl_akhir]);
        }

        // Apply Keyword Filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('pengajuan_tukar_jaga.no_pengajuan', 'like', '%' . $search . '%')
                  ->orWhere('p1.nama', 'like', '%' . $search . '%')
                  ->orWhere('p2.nama', 'like', '%' . $search . '%')
                  ->orWhere('pengajuan_tukar_jaga.alasan', 'like', '%' . $search . '%');
            });
        }

        $tukarList = $query->orderBy('pengajuan_tukar_jaga.tanggal', 'desc')
                           ->orderBy('pengajuan_tukar_jaga.no_pengajuan', 'desc')
                           ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $tukarList
            ]);
        }

        // Load active employees list
        $employees = DB::table('pegawai')
            ->where('stts_aktif', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();

        // Generate next default document number for current date
        $defaultNo = $this->generateNoPengajuan(Carbon::now()->toDateString());

        return view('kepegawaian.tukar_jaga.index', compact('tukarList', 'search', 'tgl_awal', 'tgl_akhir', 'use_date_filter', 'isAdmin', 'employees', 'defaultNo', 'user'));
    }

    /**
     * Store a newly created shift swap request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_pengajuan' => 'required|string|unique:pengajuan_tukar_jaga,no_pengajuan',
            'tanggal' => 'required|date',
            'tanggal_tukar_mulai' => 'required|date',
            'tanggal_tukar_akhir' => 'required|date|after_or_equal:tanggal_tukar_mulai',
            'nik_pemohon' => 'required|string|exists:pegawai,nik',
            'nik_tukar' => 'required|string|different:nik_pemohon|exists:pegawai,nik',
            'alasan' => 'required|string|max:200',
            'nik_pj' => 'required|string|exists:pegawai,nik',
        ]);

        DB::table('pengajuan_tukar_jaga')->insert([
            'no_pengajuan' => $request->no_pengajuan,
            'tanggal' => $request->tanggal,
            'tanggal_tukar_mulai' => $request->tanggal_tukar_mulai,
            'tanggal_tukar_akhir' => $request->tanggal_tukar_akhir,
            'nik_pemohon' => $request->nik_pemohon,
            'nik_tukar' => $request->nik_tukar,
            'alasan' => $request->alasan,
            'nik_pj' => $request->nik_pj,
            'status' => 'Proses Pengajuan',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan tukar jaga ' . $request->no_pengajuan . ' berhasil disimpan.'
            ]);
        }

        return redirect()->route('tukar-jaga.index')->with('success', 'Pengajuan tukar jaga ' . $request->no_pengajuan . ' berhasil disimpan.');
    }

    /**
     * Update the specified shift swap request.
     */
    public function update(Request $request, $no_pengajuan)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tanggal_tukar_mulai' => 'required|date',
            'tanggal_tukar_akhir' => 'required|date|after_or_equal:tanggal_tukar_mulai',
            'nik_pemohon' => 'required|string|exists:pegawai,nik',
            'nik_tukar' => 'required|string|different:nik_pemohon|exists:pegawai,nik',
            'alasan' => 'required|string|max:200',
            'nik_pj' => 'required|string|exists:pegawai,nik',
        ]);

        $exists = DB::table('pengajuan_tukar_jaga')->where('no_pengajuan', $no_pengajuan)->exists();
        if (!$exists) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
            }
            return redirect()->route('tukar-jaga.index')->with('error', 'Pengajuan tukar jaga tidak ditemukan.');
        }

        DB::table('pengajuan_tukar_jaga')
            ->where('no_pengajuan', $no_pengajuan)
            ->update([
                'tanggal' => $request->tanggal,
                'tanggal_tukar_mulai' => $request->tanggal_tukar_mulai,
                'tanggal_tukar_akhir' => $request->tanggal_tukar_akhir,
                'nik_pemohon' => $request->nik_pemohon,
                'nik_tukar' => $request->nik_tukar,
                'alasan' => $request->alasan,
                'nik_pj' => $request->nik_pj,
            ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan tukar jaga ' . $no_pengajuan . ' berhasil diubah.'
            ]);
        }

        return redirect()->route('tukar-jaga.index')->with('success', 'Pengajuan tukar jaga ' . $no_pengajuan . ' berhasil diubah.');
    }

    /**
     * Remove the specified shift swap request.
     */
    public function destroy(Request $request, $no_pengajuan)
    {
        $exists = DB::table('pengajuan_tukar_jaga')->where('no_pengajuan', $no_pengajuan)->exists();
        if (!$exists) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
            }
            return redirect()->route('tukar-jaga.index')->with('error', 'Pengajuan tukar jaga tidak ditemukan.');
        }

        DB::table('pengajuan_tukar_jaga')->where('no_pengajuan', $no_pengajuan)->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan tukar jaga ' . $no_pengajuan . ' berhasil dihapus.'
            ]);
        }

        return redirect()->route('tukar-jaga.index')->with('success', 'Pengajuan tukar jaga ' . $no_pengajuan . ' berhasil dihapus.');
    }

    /**
     * Print shift swap request form.
     */
    public function cetak($no_pengajuan)
    {
        $no_pengajuan = urldecode($no_pengajuan);

        $tukar = DB::table('pengajuan_tukar_jaga')
            ->join('pegawai as p1', 'pengajuan_tukar_jaga.nik_pemohon', '=', 'p1.nik')
            ->join('pegawai as p2', 'pengajuan_tukar_jaga.nik_tukar', '=', 'p2.nik')
            ->leftJoin('pegawai as p3', 'pengajuan_tukar_jaga.nik_pj', '=', 'p3.nik')
            ->select(
                'pengajuan_tukar_jaga.*',
                'p1.nama as nama_pemohon',
                'p1.jbtn as jabatan_pemohon',
                'p1.bidang as bidang_pemohon',
                'p1.departemen as departemen_pemohon',
                'p2.nama as nama_tukar',
                'p2.jbtn as jabatan_tukar',
                'p2.bidang as bidang_tukar',
                'p2.departemen as departemen_tukar',
                'p3.nama as nama_pj',
                'p3.jbtn as jabatan_pj'
            )
            ->where('pengajuan_tukar_jaga.no_pengajuan', $no_pengajuan)
            ->first();

        if (!$tukar) {
            abort(404, 'Pengajuan tukar jaga tidak ditemukan.');
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
        $tgl_pengajuan_formatted = Carbon::parse($tukar->tanggal)->translatedFormat('d F Y');
        
        $tgl_mulai = Carbon::parse($tukar->tanggal_tukar_mulai);
        $tgl_akhir = Carbon::parse($tukar->tanggal_tukar_akhir);
        if ($tgl_mulai->equalTo($tgl_akhir)) {
            $tgl_tukar_formatted = $tgl_mulai->translatedFormat('d F Y');
        } else {
            if ($tgl_mulai->format('Y-m') === $tgl_akhir->format('Y-m')) {
                $tgl_tukar_formatted = $tgl_mulai->translatedFormat('d') . ' s.d. ' . $tgl_akhir->translatedFormat('d F Y');
            } else if ($tgl_mulai->format('Y') === $tgl_akhir->format('Y')) {
                $tgl_tukar_formatted = $tgl_mulai->translatedFormat('d F') . ' s.d. ' . $tgl_akhir->translatedFormat('d F Y');
            } else {
                $tgl_tukar_formatted = $tgl_mulai->translatedFormat('d F Y') . ' s.d. ' . $tgl_akhir->translatedFormat('d F Y');
            }
        }

        return view('kepegawaian.tukar_jaga.cetak', compact('tukar', 'rs', 'tgl_pengajuan_formatted', 'tgl_tukar_formatted'));
    }

    /**
     * JSON API Endpoint: Generate sequential document number in PJYYYYMMDDXXX format.
     */
    public function getNewNoPengajuan(Request $request)
    {
        $date = $request->input('tanggal', Carbon::now()->toDateString());
        $noPengajuan = $this->generateNoPengajuan($date);
        return response()->json(['no_pengajuan' => $noPengajuan]);
    }

    /**
     * JSON API Endpoint: Fetch employees list with optional search filters.
     */
    public function getEmployees(Request $request)
    {
        $search = $request->input('search');
        
        $query = DB::table('pegawai')
            ->where('stts_aktif', 'AKTIF');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nik', 'like', '%' . $search . '%')
                  ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $employees = $query->orderBy('nama', 'asc')->get();

        return response()->json(['status' => 'success', 'data' => $employees]);
    }

    /**
     * Approve the specified shift swap request (Admin/HRD).
     */
    public function approve($no_pengajuan)
    {
        $no_pengajuan = urldecode($no_pengajuan);
        
        $tukar = DB::table('pengajuan_tukar_jaga')->where('no_pengajuan', $no_pengajuan)->first();
        if ($tukar && $tukar->status === 'Disetujui PJ') {
            DB::table('pengajuan_tukar_jaga')
                ->where('no_pengajuan', $no_pengajuan)
                ->update(['status' => 'Disetujui']);
            return back()->with('success', 'Pengajuan tukar jaga ' . $no_pengajuan . ' telah disetujui oleh HRD.');
        }
        
        return back()->with('error', 'Gagal menyetujui. Pengajuan harus disetujui oleh Penanggung Jawab terlebih dahulu.');
    }

    /**
     * Reject the specified shift swap request (Admin/HRD).
     */
    public function reject($no_pengajuan)
    {
        $no_pengajuan = urldecode($no_pengajuan);
        
        DB::table('pengajuan_tukar_jaga')
            ->where('no_pengajuan', $no_pengajuan)
            ->update(['status' => 'Ditolak']);

        return back()->with('success', 'Pengajuan tukar jaga ' . $no_pengajuan . ' telah ditolak oleh HRD.');
    }

    /**
     * Approve a shift swap request (Penanggung Jawab).
     */
    public function approvePj($no_pengajuan)
    {
        $no_pengajuan = urldecode($no_pengajuan);
        $user = session('khanza_user');
        
        $tukar = DB::table('pengajuan_tukar_jaga')->where('no_pengajuan', $no_pengajuan)->first();
        if (!$tukar) {
            abort(404, 'Data tidak ditemukan.');
        }
        
        if ($tukar->nik_pj !== $user['username']) {
            abort(403, 'Anda bukan Penanggung Jawab untuk pengajuan ini.');
        }
        
        if ($tukar->status === 'Proses Pengajuan') {
            DB::table('pengajuan_tukar_jaga')
                ->where('no_pengajuan', $no_pengajuan)
                ->update(['status' => 'Disetujui PJ']);
            return back()->with('success', 'Pengajuan tukar jaga ' . $no_pengajuan . ' telah disetujui oleh Anda sebagai Penanggung Jawab.');
        }
        
        return back()->with('error', 'Status pengajuan tidak valid untuk disetujui.');
    }

    /**
     * Reject a shift swap request (Penanggung Jawab).
     */
    public function rejectPj($no_pengajuan)
    {
        $no_pengajuan = urldecode($no_pengajuan);
        $user = session('khanza_user');
        
        $tukar = DB::table('pengajuan_tukar_jaga')->where('no_pengajuan', $no_pengajuan)->first();
        if (!$tukar) {
            abort(404, 'Data tidak ditemukan.');
        }
        
        if ($tukar->nik_pj !== $user['username']) {
            abort(403, 'Anda bukan Penanggung Jawab untuk pengajuan ini.');
        }
        
        if ($tukar->status === 'Proses Pengajuan') {
            DB::table('pengajuan_tukar_jaga')
                ->where('no_pengajuan', $no_pengajuan)
                ->update(['status' => 'Ditolak']);
            return back()->with('success', 'Pengajuan tukar jaga ' . $no_pengajuan . ' telah ditolak.');
        }
        
        return back()->with('error', 'Status pengajuan tidak valid untuk ditolak.');
    }

    /**
     * Generate sequential document number in PJYYYYMMDDXXX format.
     */
    private function generateNoPengajuan($date)
    {
        $formattedDate = date('Ymd', strtotime($date));
        $prefix = "PJ" . $formattedDate;
        
        $lastRecord = DB::table('pengajuan_tukar_jaga')
            ->where('tanggal', $date)
            ->where('no_pengajuan', 'like', $prefix . '%')
            ->orderBy('no_pengajuan', 'desc')
            ->first();
            
        if ($lastRecord) {
            $lastNumber = intval(substr($lastRecord->no_pengajuan, -3));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}

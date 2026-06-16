<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CutiController extends Controller
{
    /**
     * Display a listing of leave requests.
     */
    public function index(Request $request)
    {
        $user = session('khanza_user');
        $isAdmin = $user['role'] === 'admin';

        $search = $request->input('search');
        $tgl_awal = $request->input('tgl_awal', Carbon::now()->startOfMonth()->toDateString());
        $tgl_akhir = $request->input('tgl_akhir', Carbon::now()->toDateString());
        $use_date_filter = $request->input('use_date_filter', 'false');

        $query = DB::table('pengajuan_cuti')
            ->join('pegawai as p1', 'pengajuan_cuti.nik', '=', 'p1.nik')
            ->leftJoin('pegawai as p2', 'pengajuan_cuti.nik_pj', '=', 'p2.nik')
            ->select(
                'pengajuan_cuti.*',
                'p1.nama as nama_pemohon',
                'p1.bidang as bidang_pemohon',
                'p1.departemen as departemen_pemohon',
                'p2.nama as nama_pj'
            );

        // If not admin, regular user can only see their own leave requests
        if (!$isAdmin) {
            $query->where('pengajuan_cuti.nik', $user['username']);
        }

        // Apply Date Filter if checked ('true')
        if ($use_date_filter === 'true') {
            $query->whereBetween('pengajuan_cuti.tanggal', [$tgl_awal, $tgl_akhir]);
        }

        // Apply Keyword Filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('pengajuan_cuti.no_pengajuan', 'like', '%' . $search . '%')
                  ->orWhere('p1.nama', 'like', '%' . $search . '%')
                  ->orWhere('pengajuan_cuti.urgensi', 'like', '%' . $search . '%');
            });
        }

        $cutiList = $query->orderBy('pengajuan_cuti.tanggal', 'desc')
                          ->orderBy('pengajuan_cuti.no_pengajuan', 'desc')
                          ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $cutiList
            ]);
        }

        // Load active employees list to select as Supervisor/PJ
        $supervisors = DB::table('pegawai')
            ->where('stts_aktif', 'AKTIF')
            ->orderBy('nama', 'asc')
            ->get();

        // Also generate next default document number for current date
        $defaultNo = $this->generateNoPengajuan(Carbon::now()->toDateString());

        return view('cuti.index', compact('cutiList', 'search', 'tgl_awal', 'tgl_akhir', 'use_date_filter', 'isAdmin', 'supervisors', 'defaultNo', 'user'));
    }

    /**
     * Show the form for creating a new leave request (legacy redirection support).
     */
    public function create()
    {
        return redirect()->route('cuti.index');
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_pengajuan' => 'required|string|unique:pengajuan_cuti,no_pengajuan',
            'tanggal' => 'required|date',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'nik' => 'required|string|exists:pegawai,nik',
            'urgensi' => 'required|in:Tahunan,Besar,Sakit,Bersalin,Alasan Penting,Keterangan Lainnya',
            'alamat' => 'required|string|max:100',
            'kepentingan' => 'required|string|max:70',
            'nik_pj' => 'required|string|exists:pegawai,nik',
            'status' => 'required|in:Proses Pengajuan,Disetujui,Ditolak',
        ]);

        // Calculate leave days count (inclusive)
        $start = Carbon::parse($request->tanggal_awal);
        $end = Carbon::parse($request->tanggal_akhir);
        $jumlah = $start->diffInDays($end) + 1;

        DB::transaction(function() use ($request, $jumlah) {
            DB::table('pengajuan_cuti')->insert([
                'no_pengajuan' => $request->no_pengajuan,
                'tanggal' => $request->tanggal,
                'tanggal_awal' => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'nik' => $request->nik,
                'urgensi' => $request->urgensi,
                'alamat' => $request->alamat,
                'jumlah' => $jumlah,
                'kepentingan' => $request->kepentingan,
                'nik_pj' => $request->nik_pj,
                'status' => $request->status,
            ]);

            // If immediately approved, increment cuti_diambil in pegawai table
            if ($request->status === 'Disetujui') {
                DB::table('pegawai')
                    ->where('nik', $request->nik)
                    ->increment('cuti_diambil', $jumlah);
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan cuti ' . $request->no_pengajuan . ' berhasil disimpan.'
            ]);
        }

        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti ' . $request->no_pengajuan . ' berhasil disimpan.');
    }

    /**
     * Update the specified leave request.
     */
    public function update(Request $request, $no_pengajuan)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'nik' => 'required|string|exists:pegawai,nik',
            'urgensi' => 'required|in:Tahunan,Besar,Sakit,Bersalin,Alasan Penting,Keterangan Lainnya',
            'alamat' => 'required|string|max:100',
            'kepentingan' => 'required|string|max:70',
            'nik_pj' => 'required|string|exists:pegawai,nik',
            'status' => 'required|in:Proses Pengajuan,Disetujui,Ditolak',
        ]);

        $cuti = DB::table('pengajuan_cuti')->where('no_pengajuan', $no_pengajuan)->first();
        if (!$cuti) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
            }
            return redirect()->route('cuti.index')->with('error', 'Pengajuan cuti tidak ditemukan.');
        }

        // Calculate leave days count (inclusive)
        $start = Carbon::parse($request->tanggal_awal);
        $end = Carbon::parse($request->tanggal_akhir);
        $jumlah = $start->diffInDays($end) + 1;

        DB::transaction(function() use ($cuti, $request, $jumlah, $no_pengajuan) {
            // Handle taken leave quota updates in pegawai table
            $oldStatus = $cuti->status;
            $newStatus = $request->status;
            $oldNik = $cuti->nik;
            $newNik = $request->nik;
            $oldJumlah = $cuti->jumlah;
            $newJumlah = $jumlah;

            // Scenario 1: Employee changed
            if ($oldNik !== $newNik) {
                // Decrement old employee if old status was approved
                if ($oldStatus === 'Disetujui') {
                    DB::table('pegawai')->where('nik', $oldNik)->decrement('cuti_diambil', $oldJumlah);
                }
                // Increment new employee if new status is approved
                if ($newStatus === 'Disetujui') {
                    DB::table('pegawai')->where('nik', $newNik)->increment('cuti_diambil', $newJumlah);
                }
            } else {
                // Same employee
                if ($oldStatus === 'Disetujui' && $newStatus === 'Disetujui') {
                    // Status was and is Approved, but duration may have changed
                    if ($oldJumlah !== $newJumlah) {
                        $diff = $newJumlah - $oldJumlah;
                        DB::table('pegawai')->where('nik', $newNik)->increment('cuti_diambil', $diff);
                    }
                } elseif ($oldStatus !== 'Disetujui' && $newStatus === 'Disetujui') {
                    // Newly approved
                    DB::table('pegawai')->where('nik', $newNik)->increment('cuti_diambil', $newJumlah);
                } elseif ($oldStatus === 'Disetujui' && $newStatus !== 'Disetujui') {
                    // Revoked approval
                    DB::table('pegawai')->where('nik', $newNik)->decrement('cuti_diambil', $oldJumlah);
                }
            }

            // Update database record
            DB::table('pengajuan_cuti')
                ->where('no_pengajuan', $no_pengajuan)
                ->update([
                    'tanggal' => $request->tanggal,
                    'tanggal_awal' => $request->tanggal_awal,
                    'tanggal_akhir' => $request->tanggal_akhir,
                    'nik' => $request->nik,
                    'urgensi' => $request->urgensi,
                    'alamat' => $request->alamat,
                    'jumlah' => $jumlah,
                    'kepentingan' => $request->kepentingan,
                    'nik_pj' => $request->nik_pj,
                    'status' => $request->status,
                ]);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan cuti ' . $no_pengajuan . ' berhasil diubah.'
            ]);
        }

        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti ' . $no_pengajuan . ' berhasil diubah.');
    }

    /**
     * Remove the specified leave request.
     */
    public function destroy(Request $request, $no_pengajuan)
    {
        $cuti = DB::table('pengajuan_cuti')->where('no_pengajuan', $no_pengajuan)->first();
        if (!$cuti) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
            }
            return redirect()->route('cuti.index')->with('error', 'Pengajuan cuti tidak ditemukan.');
        }

        DB::transaction(function() use ($cuti, $no_pengajuan) {
            // Decrement took quota if approved
            if ($cuti->status === 'Disetujui') {
                DB::table('pegawai')
                    ->where('nik', $cuti->nik)
                    ->decrement('cuti_diambil', $cuti->jumlah);
            }

            DB::table('pengajuan_cuti')->where('no_pengajuan', $no_pengajuan)->delete();
        });

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan cuti ' . $no_pengajuan . ' berhasil dihapus.'
            ]);
        }

        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti ' . $no_pengajuan . ' berhasil dihapus.');
    }

    /**
     * JSON API Endpoint: Generate sequential document number in PCYYYYMMDDXXX format.
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
     * Approve a leave request (legacy endpoint support).
     */
    public function approve($no_pengajuan)
    {
        $cuti = DB::table('pengajuan_cuti')->where('no_pengajuan', $no_pengajuan)->first();
        if ($cuti && $cuti->status === 'Proses Pengajuan') {
            DB::transaction(function() use ($cuti, $no_pengajuan) {
                DB::table('pengajuan_cuti')->where('no_pengajuan', $no_pengajuan)->update(['status' => 'Disetujui']);
                DB::table('pegawai')->where('nik', $cuti->nik)->increment('cuti_diambil', $cuti->jumlah);
            });
        }
        return redirect()->route('cuti.index');
    }

    /**
     * Reject a leave request (legacy endpoint support).
     */
    public function reject($no_pengajuan)
    {
        DB::table('pengajuan_cuti')->where('no_pengajuan', $no_pengajuan)->update(['status' => 'Ditolak']);
        return redirect()->route('cuti.index');
    }

    /**
     * Generate sequential document number in PCYYYYMMDDXXX format.
     */
    private function generateNoPengajuan($date)
    {
        $formattedDate = date('Ymd', strtotime($date));
        $prefix = "PC" . $formattedDate;
        
        $lastRecord = DB::table('pengajuan_cuti')
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the main Module Launcher (Module Selection page).
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Show the Kepegawaian Subsystem Dashboard (Stats & Recent Activity).
     */
    public function kepegawaian()
    {
        $user = session('khanza_user');
        $isAdmin = $user['role'] === 'admin';

        // 1. Calculate Stats
        $stats = [
            'total_surat' => DB::table('pengajuan_cuti')->count() + DB::table('pengajuan_tukar_jaga')->count(),
            'pending_cuti' => DB::table('pengajuan_cuti')->whereIn('status', ['Proses Pengajuan', 'Disetujui PJ'])->count(),
            'approved_cuti' => DB::table('pengajuan_cuti')->where('status', 'Disetujui')->count(),
            'rejected_cuti' => DB::table('pengajuan_cuti')->where('status', 'Ditolak')->count(),
            'pending_tukar' => DB::table('pengajuan_tukar_jaga')->whereIn('status', ['Proses Pengajuan', 'Disetujui PJ'])->count(),
            'approved_tukar' => DB::table('pengajuan_tukar_jaga')->where('status', 'Disetujui')->count(),
            'rejected_tukar' => DB::table('pengajuan_tukar_jaga')->where('status', 'Ditolak')->count(),
        ];

        // 2. Retrieve Recent Leaves
        if ($isAdmin) {
            $recentLeaves = DB::table('pengajuan_cuti')
                ->join('pegawai as p1', 'pengajuan_cuti.nik', '=', 'p1.nik')
                ->leftJoin('pegawai as p2', 'pengajuan_cuti.nik_pj', '=', 'p2.nik')
                ->select(
                    'pengajuan_cuti.*',
                    'p1.nama as nama_pemohon',
                    'p2.nama as nama_pj'
                )
                ->orderBy('pengajuan_cuti.tanggal', 'desc')
                ->limit(5)
                ->get();
        } else {
            $recentLeaves = DB::table('pengajuan_cuti')
                ->where(function($q) use ($user) {
                    $q->where('pengajuan_cuti.nik', $user['username'])
                      ->orWhere('pengajuan_cuti.nik_pj', $user['username']);
                })
                ->join('pegawai as p1', 'pengajuan_cuti.nik', '=', 'p1.nik')
                ->leftJoin('pegawai as p2', 'pengajuan_cuti.nik_pj', '=', 'p2.nik')
                ->select(
                    'pengajuan_cuti.*',
                    'p1.nama as nama_pemohon',
                    'p2.nama as nama_pj'
                )
                ->orderBy('pengajuan_cuti.tanggal', 'desc')
                ->limit(5)
                ->get();
        }

        // 3. Retrieve Recent Shift Swaps (Tukar Jaga)
        $tukarQuery = DB::table('pengajuan_tukar_jaga')
            ->join('pegawai as p1', 'pengajuan_tukar_jaga.nik_pemohon', '=', 'p1.nik')
            ->join('pegawai as p2', 'pengajuan_tukar_jaga.nik_tukar', '=', 'p2.nik')
            ->leftJoin('pegawai as p3', 'pengajuan_tukar_jaga.nik_pj', '=', 'p3.nik')
            ->select(
                'pengajuan_tukar_jaga.*',
                'p1.nama as nama_pemohon',
                'p2.nama as nama_tukar',
                'p3.nama as nama_pj'
            );

        if (!$isAdmin) {
            $tukarQuery->where(function($q) use ($user) {
                $q->where('pengajuan_tukar_jaga.nik_pemohon', $user['username'])
                  ->orWhere('pengajuan_tukar_jaga.nik_tukar', $user['username'])
                  ->orWhere('pengajuan_tukar_jaga.nik_pj', $user['username']);
            });
        }

        $recentTukar = $tukarQuery->orderBy('pengajuan_tukar_jaga.tanggal', 'desc')
                                  ->orderBy('pengajuan_tukar_jaga.no_pengajuan', 'desc')
                                  ->limit(5)
                                  ->get();

        return view('pegawai.dashboard', compact('stats', 'recentLeaves', 'recentTukar'));
    }

    /**
     * Show the Surat Subsystem Dashboard (Stats & Recent Activity).
     */
    public function surat()
    {
        $stats = [
            'total_sehat' => DB::table('surat_keterangan_sehat')->count(),
            'total_kelahiran' => DB::table('pasien_bayi')->count(),
            'total_bebas_narkoba' => 0,
        ];

        $recentSurat = DB::table('surat_keterangan_sehat')
            ->join('reg_periksa', 'surat_keterangan_sehat.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->select(
                'surat_keterangan_sehat.*',
                'pasien.nm_pasien',
                'reg_periksa.no_rkm_medis'
            )
            ->orderBy('surat_keterangan_sehat.tanggalsurat', 'desc')
            ->limit(5)
            ->get();

        return view('surat.dashboard', compact('stats', 'recentSurat'));
    }
}

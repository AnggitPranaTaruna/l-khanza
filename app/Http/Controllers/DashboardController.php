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
            'total_pegawai' => DB::table('pegawai')->count(),
            'pending_cuti' => DB::table('pengajuan_cuti')->where('status', 'Proses Pengajuan')->count(),
            'approved_cuti' => DB::table('pengajuan_cuti')->where('status', 'Disetujui')->count(),
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
                ->where('pengajuan_cuti.nik', $user['username'])
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

        return view('pegawai.dashboard', compact('stats', 'recentLeaves'));
    }
}

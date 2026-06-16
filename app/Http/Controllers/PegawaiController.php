<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('pegawai')
            ->leftJoin('departemen', 'pegawai.departemen', '=', 'departemen.dep_id')
            ->select('pegawai.*', 'departemen.nama as nama_departemen');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('pegawai.nik', 'like', '%' . $search . '%')
                  ->orWhere('pegawai.nama', 'like', '%' . $search . '%')
                  ->orWhere('pegawai.jbtn', 'like', '%' . $search . '%');
            });
        }

        $pegawai = $query->orderBy('pegawai.nama', 'asc')->paginate(10);

        return view('pegawai.index', compact('pegawai', 'search'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $lookups = $this->getLookups();
        return view('pegawai.create', $lookups);
    }

    /**
     * Store a newly created employee in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|unique:pegawai,nik',
            'nama' => 'required|string|max:50',
            'jk' => 'required|in:Pria,Wanita',
            'jbtn' => 'required|string|max:25',
            'jnj_jabatan' => 'required|string',
            'kode_kelompok' => 'required|string',
            'kode_resiko' => 'required|string',
            'kode_emergency' => 'required|string',
            'departemen' => 'required|string',
            'bidang' => 'required|string',
            'stts_wp' => 'required|string',
            'stts_kerja' => 'required|string',
            'npwp' => 'nullable|string|max:15',
            'pendidikan' => 'required|string',
            'gapok' => 'required|numeric',
            'tmp_lahir' => 'required|string|max:20',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string|max:60',
            'kota' => 'required|string|max:20',
            'mulai_kerja' => 'required|date',
            'ms_kerja' => 'required|in:<1,PT,FT>1',
            'indexins' => 'required|string',
            'bpd' => 'required|string',
            'rekening' => 'required|string|max:25',
            'stts_aktif' => 'required|in:AKTIF,CUTI,KELUAR,TENAGA LUAR',
            'wajibmasuk' => 'required|integer',
            'no_ktp' => 'required|string|max:20',
        ]);

        $data = $request->except('_token');
        $data['npwp'] = $data['npwp'] ?? '';
        $data['cuti_diambil'] = 0;
        $data['dankes'] = 0;
        $data['pengurang'] = 0;
        $data['indek'] = 5; // default index

        DB::table('pegawai')->insert($data);

        // Automatically create a user account for this employee in 'user' table
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('user');
        $userData = [];
        foreach ($columns as $column) {
            if ($column === 'id_user') {
                $userData[$column] = DB::raw("AES_ENCRYPT('".$request->nik."', 'nur')");
            } elseif ($column === 'password') {
                $userData[$column] = DB::raw("AES_ENCRYPT('password123', 'windi')"); // Default password
            } else {
                $userData[$column] = 'false';
            }
        }
        $userData['pegawai_user'] = 'true';
        $userData['pengajuan_cuti'] = 'true';
        
        DB::table('user')->insert($userData);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan dan Akun User default (password: password123) dibuat.');
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit($nik)
    {
        $pegawai = DB::table('pegawai')->where('nik', $nik)->first();
        if (!$pegawai) {
            return redirect()->route('pegawai.index')->with('error', 'Pegawai tidak ditemukan.');
        }

        $lookups = $this->getLookups();
        $lookups['pegawai'] = $pegawai;

        return view('pegawai.edit', $lookups);
    }

    /**
     * Update the specified employee in the database.
     */
    public function update(Request $request, $nik)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'jk' => 'required|in:Pria,Wanita',
            'jbtn' => 'required|string|max:25',
            'jnj_jabatan' => 'required|string',
            'kode_kelompok' => 'required|string',
            'kode_resiko' => 'required|string',
            'kode_emergency' => 'required|string',
            'departemen' => 'required|string',
            'bidang' => 'required|string',
            'stts_wp' => 'required|string',
            'stts_kerja' => 'required|string',
            'npwp' => 'nullable|string|max:15',
            'pendidikan' => 'required|string',
            'gapok' => 'required|numeric',
            'tmp_lahir' => 'required|string|max:20',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string|max:60',
            'kota' => 'required|string|max:20',
            'mulai_kerja' => 'required|date',
            'ms_kerja' => 'required|in:<1,PT,FT>1',
            'indexins' => 'required|string',
            'bpd' => 'required|string',
            'rekening' => 'required|string|max:25',
            'stts_aktif' => 'required|in:AKTIF,CUTI,KELUAR,TENAGA LUAR',
            'wajibmasuk' => 'required|integer',
            'no_ktp' => 'required|string|max:20',
        ]);

        $data = $request->except(['_token', '_method']);
        $data['npwp'] = $data['npwp'] ?? '';

        DB::table('pegawai')->where('nik', $nik)->update($data);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified employee from the database.
     */
    public function destroy($nik)
    {
        // Delete user account first
        DB::table('user')->where('id_user', DB::raw("AES_ENCRYPT('".$nik."', 'nur')"))->delete();
        
        // Delete employee
        DB::table('pegawai')->where('nik', $nik)->delete();

        return redirect()->route('pegawai.index')->with('success', 'Pegawai dan Akun User terkait berhasil dihapus.');
    }

    /**
     * Helper to load all lookups needed for forms.
     */
    private function getLookups()
    {
        return [
            'departments' => DB::table('departemen')->orderBy('nama', 'asc')->get(),
            'bidangList' => DB::table('bidang')->orderBy('nama', 'asc')->get(),
            'positions' => DB::table('jnj_jabatan')->orderBy('nama', 'asc')->get(),
            'taxStatuses' => DB::table('stts_wp')->orderBy('stts', 'asc')->get(),
            'workStatuses' => DB::table('stts_kerja')->orderBy('stts', 'asc')->get(),
            'educations' => DB::table('pendidikan')->orderBy('indek', 'asc')->get(),
            'banks' => DB::table('bank')->orderBy('namabank', 'asc')->get(),
            'emergencyIndexes' => DB::table('emergency_index')->orderBy('indek', 'asc')->get(),
            'groups' => DB::table('kelompok_jabatan')->orderBy('nama_kelompok', 'asc')->get(),
            'risks' => DB::table('resiko_kerja')->orderBy('nama_resiko', 'asc')->get(),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Session::has('khanza_user')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle authentication request.
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'ID User wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $username = $request->username;
        $password = $request->password;

        // 1. Check in admin table
        $admin = DB::select(
            "SELECT * FROM admin WHERE usere = AES_ENCRYPT(?, 'nur') AND passworde = AES_ENCRYPT(?, 'windi')",
            [$username, $password]
        );

        if (!empty($admin)) {
            // Logged in as Admin Utama
            Session::put('khanza_user', [
                'username' => $username,
                'name' => 'Admin Utama',
                'role' => 'admin',
                'nik' => null,
                'permissions' => [] // admin has all access by default
            ]);

            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, Admin Utama!');
        }

        // 2. Check in user table
        $user = DB::select(
            "SELECT * FROM user WHERE id_user = AES_ENCRYPT(?, 'nur') AND password = AES_ENCRYPT(?, 'windi')",
            [$username, $password]
        );

        if (!empty($user)) {
            $userRow = (array) $user[0];
            
            // Look up employee name in pegawai table
            $pegawai = DB::select("SELECT nama FROM pegawai WHERE nik = ?", [$username]);
            $name = !empty($pegawai) ? $pegawai[0]->nama : 'User ' . $username;

            // Extract all permission columns
            $permissions = [];
            foreach ($userRow as $colName => $value) {
                if ($colName !== 'id_user' && $colName !== 'password') {
                    $permissions[$colName] = $value;
                }
            }

            Session::put('khanza_user', [
                'username' => $username,
                'name' => $name,
                'role' => 'user',
                'nik' => $username,
                'permissions' => $permissions
            ]);

            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, ' . $name . '!');
        }

        // Authentication failed
        return back()->withErrors([
            'login_error' => 'ID User atau Password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        Session::forget('khanza_user');
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}

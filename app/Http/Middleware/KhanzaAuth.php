<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KhanzaAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $permission
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        if (!session()->has('khanza_user')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = session('khanza_user');

        // If user is Admin Utama (role is admin), they have access to everything
        if ($user['role'] === 'admin') {
            return $next($request);
        }

        // If a specific permission is required, check it
        if ($permission) {
            $hasPermission = isset($user['permissions'][$permission]) && $user['permissions'][$permission] === 'true';
            if (!$hasPermission) {
                return response()->view('errors.403', ['message' => 'Anda tidak memiliki hak akses untuk modul ini.'], 403);
            }
        }

        return $next($request);
    }
}

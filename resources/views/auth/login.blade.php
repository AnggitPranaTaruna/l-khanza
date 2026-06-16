<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - L-Khanza SIMRS</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 6px;">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>L-Khanza</span>
            </div>
            <p class="login-subtitle">Sistem Informasi Manajemen Rumah Sakit</p>
        </div>

        @if($errors->has('login_error'))
            <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                {{ $errors->first('login_error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username" class="form-label">ID User (NIK/Username)</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan ID User" value="{{ old('username') }}" required autocomplete="username" autofocus>
                @if($errors->has('username'))
                    <span style="color: var(--danger); font-size: 0.8rem;">{{ $errors->first('username') }}</span>
                @endif
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password" required autocomplete="current-password">
                @if($errors->has('password'))
                    <span style="color: var(--danger); font-size: 0.8rem;">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 14px;">
                Masuk ke Sistem
            </button>
        </form>

        <div class="login-info-box" style="margin-top: 24px; margin-bottom: 0;">
            <p style="font-weight: 600; color: var(--text-primary); margin-bottom: 6px;">Akun Demo/Uji Coba:</p>
            <p style="margin-bottom: 4px;">• <strong>Admin Utama</strong>:<br>ID User: <code>spv</code> | Pass: <code>server</code></p>
            <p>• <strong>Pegawai (Staff HRD)</strong>:<br>ID User: <code>1001</code> | Pass: <code>password123</code></p>
        </div>
    </div>
</body>
</html>

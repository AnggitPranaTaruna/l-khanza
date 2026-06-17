<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak (403) - SIMRS KHANZA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
            text-align: center;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 800;
            color: var(--danger);
            line-height: 1;
            letter-spacing: -0.05em;
            margin-bottom: 16px;
            text-shadow: 0 0 30px rgba(239, 68, 68, 0.2);
        }
        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .error-message {
            color: var(--text-secondary);
            max-width: 460px;
            margin-bottom: 32px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <h1 class="error-title">Akses Ditolak</h1>
        <p class="error-message">{{ $message ?? 'Maaf, Anda tidak memiliki izin untuk mengakses modul ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.' }}</p>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIMRS KHANZA</title>
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/live-bg.js') }}" defer></script>
</head>
<body class="login-container">
    <div class="bg-glow-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <canvas id="bg-canvas"></canvas>
    </div>
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 6px;">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>SIMRS KHANZA</span>
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

        <div class="login-time-box" style="margin-top: 24px; padding: 14px 18px; background-color: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: var(--radius-sm); text-align: center; backdrop-filter: blur(8px);">
            <div id="realtime-clock" style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 800; color: var(--primary); letter-spacing: 0.05em; margin-bottom: 4px; line-height: 1;">00:00:00</div>
            <div id="realtime-date" style="font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">Hari, DD MMMM YYYY</div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            
            // Format time
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('realtime-clock').textContent = `${hours}:${minutes}:${seconds}`;
            
            // Format date in Indonesian
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const day = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            document.getElementById('realtime-date').textContent = `${dayName}, ${day} ${monthName} ${year}`;
        }
        
        // Update immediately and then every second
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>

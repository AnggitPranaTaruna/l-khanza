<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - L-Khanza SIMRS</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 17L12 22L22 17" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12L12 17L22 12" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>L-Khanza</span>
                </div>
            </div>

            @php
                $user = session('khanza_user');
                $isAdmin = $user['role'] === 'admin';
                $hasPegawaiAccess = $isAdmin || (isset($user['permissions']['pegawai_admin']) && $user['permissions']['pegawai_admin'] === 'true') || (isset($user['permissions']['pegawai_user']) && $user['permissions']['pegawai_user'] === 'true');
                $hasCutiAccess = $isAdmin || (isset($user['permissions']['pengajuan_cuti']) && $user['permissions']['pengajuan_cuti'] === 'true');
                $isKepegawaianModule = request()->routeIs('pegawai.*') || request()->routeIs('cuti.*') || request()->is('kepegawaian*');
            @endphp

            <ul class="sidebar-menu">
                @if($isKepegawaianModule)
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link" style="color: var(--primary);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="11 17 6 12 11 7"></polyline>
                                <polyline points="18 17 13 12 18 7"></polyline>
                            </svg>
                            Menu Utama
                        </a>
                    </li>
                    <li style="padding: 10px 16px 5px 16px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary);">
                        Subsystem Kepegawaian
                    </li>
                    <li>
                        <a href="{{ route('pegawai.dashboard') }}" class="sidebar-link {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="9"></rect>
                                <rect x="14" y="3" width="7" height="5"></rect>
                                <rect x="14" y="12" width="7" height="9"></rect>
                                <rect x="3" y="16" width="7" height="5"></rect>
                            </svg>
                            Dashboard Modul
                        </a>
                    </li>
                    @if($hasPegawaiAccess)
                    <li>
                        <a href="{{ route('pegawai.index') }}" class="sidebar-link {{ request()->routeIs('pegawai.index') || request()->routeIs('pegawai.create') || request()->routeIs('pegawai.edit') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            Data Pegawai
                        </a>
                    </li>
                    @endif
                    @if($hasCutiAccess)
                    <li>
                        <a href="{{ route('cuti.index') }}" class="sidebar-link {{ request()->routeIs('cuti.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            Cuti Pegawai
                        </a>
                    </li>
                    @endif
                @else
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="9"></rect>
                                <rect x="14" y="3" width="7" height="5"></rect>
                                <rect x="14" y="12" width="7" height="9"></rect>
                                <rect x="3" y="16" width="7" height="5"></rect>
                            </svg>
                            Pilih Modul
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer">
                <div class="user-badge">
                    <div class="user-avatar">
                        {{ strtoupper(substr($user['name'], 0, 2)) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name" title="{{ $user['name'] }}">{{ Str::limit($user['name'], 18) }}</span>
                        <span class="user-role">{{ $isAdmin ? 'Admin Utama' : 'Pegawai' }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: block; width: 100%;">
                    @csrf
                    <button type="submit" class="btn-logout" style="width: 100%; border: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1 class="page-title">@yield('header_title')</h1>
                <div class="header-right">
                    <span style="font-size: 0.85rem; color: var(--text-secondary);">
                        Tanggal: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong>
                    </span>
                </div>
            </header>

            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @yield('scripts')
</body>
</html>

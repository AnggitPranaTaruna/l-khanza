<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIMRS KHANZA</title>
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 17L12 22L22 17" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12L12 17L22 12" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>SIMRS KHANZA</span>
                </div>
            </div>

            @php
                $user = session('khanza_user');
                $isAdmin = $user['role'] === 'admin';
                $hasPegawaiAccess = $isAdmin || (isset($user['permissions']['pegawai_admin']) && $user['permissions']['pegawai_admin'] === 'true') || (isset($user['permissions']['pegawai_user']) && $user['permissions']['pegawai_user'] === 'true');
                $hasCutiAccess = $isAdmin || (isset($user['permissions']['pengajuan_cuti']) && $user['permissions']['pengajuan_cuti'] === 'true');
                $hasSuratSehatAccess = $isAdmin || (isset($user['permissions']['surat_keterangan_sehat']) && $user['permissions']['surat_keterangan_sehat'] === 'true');
                $hasKelahiranBayiAccess = $isAdmin || (isset($user['permissions']['kelahiran_bayi']) && $user['permissions']['kelahiran_bayi'] === 'true');
                $isKepegawaianModule = request()->routeIs('pegawai.*') || request()->routeIs('cuti.*') || request()->routeIs('tukar-jaga.*') || request()->is('kepegawaian*') || request()->is('tukar-jaga*');
                $isSuratModule = request()->routeIs('surat.*') || request()->is('surat*');
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
                    <li>
                        <a href="{{ route('tukar-jaga.index') }}" class="sidebar-link {{ request()->routeIs('tukar-jaga.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 2.1l4 2-4 2"></path>
                                <path d="M3 12h18"></path>
                                <path d="M21 12l-4-4m4 4l-4 4"></path>
                                <path d="M7 21.9l-4-2 4-2"></path>
                                <path d="M3 12V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z"></path>
                            </svg>
                            Tukar Jaga
                        </a>
                    </li>
                    @endif
                @elseif($isSuratModule)
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
                        Subsystem Surat
                    </li>
                    <li>
                        <a href="{{ route('surat.dashboard') }}" class="sidebar-link {{ request()->routeIs('surat.dashboard') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="9"></rect>
                                <rect x="14" y="3" width="7" height="5"></rect>
                                <rect x="14" y="12" width="7" height="9"></rect>
                                <rect x="3" y="16" width="7" height="5"></rect>
                            </svg>
                            Dashboard Modul
                        </a>
                    </li>
                    @if($hasSuratSehatAccess)
                    <li>
                        <a href="{{ route('surat.sehat.index') }}" class="sidebar-link {{ request()->routeIs('surat.sehat.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            Surat Ket. Sehat
                        </a>
                    </li>
                    @endif
                    @if($hasKelahiranBayiAccess)
                    <li>
                        <a href="{{ route('surat.kelahiran.index') }}" class="sidebar-link {{ request()->routeIs('surat.kelahiran.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                <line x1="15" y1="9" x2="15.01" y2="9"></line>
                            </svg>
                            Surat Ket. Kelahiran Bayi
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
                <a href="{{ route('logout') }}" class="btn-logout" style="width: 100%; text-decoration: none; box-sizing: border-box;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Keluar
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div class="header-left">
                    <button type="button" id="sidebar-toggle" class="btn-toggle-sidebar" aria-label="Toggle Sidebar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <h1 class="page-title">@yield('header_title')</h1>
                </div>
                <div class="header-right">
                    <button type="button" id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle Theme">
                        <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const appContainer = document.querySelector('.app-container');
            const overlay = document.getElementById('sidebar-overlay');
            const themeToggle = document.getElementById('theme-toggle');

            // Load sidebar collapsed state on desktop
            if (window.innerWidth > 768) {
                const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                if (isCollapsed) {
                    appContainer.classList.add('sidebar-collapsed');
                }
            }

            // Sidebar Toggle
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    appContainer.classList.toggle('sidebar-open');
                } else {
                    appContainer.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebar-collapsed', appContainer.classList.contains('sidebar-collapsed'));
                }
            });

            // Overlay Click (to close sidebar on mobile)
            if (overlay) {
                overlay.addEventListener('click', function() {
                    appContainer.classList.remove('sidebar-open');
                });
            }

            // Theme Toggle
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    let currentTheme = document.documentElement.getAttribute('data-theme');
                    let newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                });
            }
        });
    </script>
</body>
</html>

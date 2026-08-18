<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - SIMPEG Dispanperta Sidoarjo')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
        }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 4px; }

        @media (min-width: 768px) {
            .admin-sidebar {
                display: flex !important;
                position: fixed !important;
                top: 0 !important;
                bottom: 0 !important;
                left: 0 !important;
                width: 260px !important;
                height: 100vh !important;
                z-index: 40 !important;
            }
            .admin-main-wrapper {
                margin-left: 260px !important;
                width: calc(100% - 260px) !important;
                min-height: 100vh !important;
            }
        }
        @media (max-width: 767px) {
            .admin-sidebar {
                display: none !important;
            }
            .admin-main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
                min-height: 100vh !important;
            }
        }
            @keyframes loading {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(0%); }
            100% { transform: translateX(100%); }
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen" x-data="{ mobileMenuOpen: false }">
    <script>
        // Check if splash screen was already shown in this browser session
        if (sessionStorage.getItem('simpeg_splash_shown')) {
            document.write('<style>#simpeg-preloader { display: none !important; }</style>');
        }
    </script>

    <!-- Preloader Splash Screen: Sequential Cinematic Logo Transition (Once per Session) -->
    <div id="simpeg-preloader" class="fixed inset-0 z-[9999] bg-slate-950 flex flex-col items-center justify-center transition-opacity duration-500 ease-out select-none">
        
        <!-- Ambient Background Glow -->
        <div class="absolute w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl animate-pulse pointer-events-none"></div>

        <!-- Stage 1: Logo Kabupaten Sidoarjo (Besar & Resmi) -->
        <div id="splash-stage-1" class="flex flex-col items-center justify-center p-6 text-center transition-all duration-300 transform opacity-100 scale-100">
            <div class="relative bg-white p-5 rounded-3xl shadow-2xl border-2 border-emerald-500/40 mb-4 transform transition duration-500 hover:scale-105">
                <img src="{{ secure_asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kabupaten Sidoarjo" class="h-28 sm:h-32 w-auto object-contain">
            </div>
            <h2 class="text-white font-extrabold text-base sm:text-xl uppercase tracking-wider mb-1">
                Pemerintah Kabupaten Sidoarjo
            </h2>
            <p class="text-amber-400 font-bold text-xs uppercase tracking-widest">
                Jawa Timur
            </p>
        </div>

        <!-- Stage 2: Logo Dispanperta Sidoarjo (Besar & Transisi Bergantian) -->
        <div id="splash-stage-2" class="flex flex-col items-center justify-center p-6 text-center transition-all duration-300 transform opacity-0 scale-95 hidden">
            <div class="relative bg-white p-5 rounded-3xl shadow-2xl border-2 border-amber-500/40 mb-4 transform transition duration-500 hover:scale-105">
                <img src="{{ secure_asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta Sidoarjo" class="h-28 sm:h-32 w-auto object-contain">
            </div>
            <h2 class="text-white font-extrabold text-base sm:text-xl uppercase tracking-wider mb-1">
                Dinas Pangan dan Pertanian
            </h2>
            <p class="text-emerald-400 font-bold text-xs uppercase tracking-widest mb-5">
                Sistem Informasi Kepegawaian (SIMPEG)
            </p>

            <!-- Progress Indicator -->
            <div class="w-48 h-1.5 bg-slate-800 rounded-full overflow-hidden border border-slate-700/60 relative">
                <div class="h-full bg-gradient-to-r from-emerald-500 via-amber-400 to-emerald-400 rounded-full w-full animate-[loading_1s_ease-in-out_infinite]"></div>
            </div>
            <span class="text-[10px] text-slate-500 font-mono mt-2 tracking-widest uppercase">Membuka SIMPEG...</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const preloader = document.getElementById('simpeg-preloader');
            
            // If already shown previously in this session, remove immediately
            if (sessionStorage.getItem('simpeg_splash_shown')) {
                if (preloader) preloader.remove();
                return;
            }

            // Mark as shown for the rest of the session
            sessionStorage.setItem('simpeg_splash_shown', 'true');

            const stage1 = document.getElementById('splash-stage-1');
            const stage2 = document.getElementById('splash-stage-2');

            if (preloader && stage1 && stage2) {
                setTimeout(() => {
                    stage1.classList.remove('opacity-100', 'scale-100');
                    stage1.classList.add('opacity-0', 'scale-90');
                    
                    setTimeout(() => {
                        stage1.classList.add('hidden');
                        stage2.classList.remove('hidden');
                        
                        void stage2.offsetWidth; // Trigger reflow
                        
                        stage2.classList.remove('opacity-0', 'scale-95');
                        stage2.classList.add('opacity-100', 'scale-100');
                    }, 200);
                }, 850);

                const finishLoading = () => {
                    setTimeout(() => {
                        preloader.style.opacity = '0';
                        preloader.style.pointerEvents = 'none';
                        setTimeout(() => {
                            preloader.style.display = 'none';
                        }, 500);
                    }, 1900);
                };

                if (document.readyState === 'complete') {
                    finishLoading();
                } else {
                    window.addEventListener('load', finishLoading);
                    setTimeout(finishLoading, 3000); // Safety fallback
                }
            }
        });
    </script>
        

    <!-- Desktop Fixed Sidebar Navigation -->
    <aside class="admin-sidebar bg-slate-900 text-slate-300 flex-col border-r border-slate-800 select-none">
        <!-- Brand Header with Crisp White Logo Card Wrapper -->
        <div class="px-4 py-3 bg-emerald-950 border-b border-emerald-900 flex items-center space-x-3 flex-shrink-0" style="height: 64px; box-sizing: border-box;">
            <div class="flex items-center space-x-1.5 flex-shrink-0">
                <div class="bg-white p-1 rounded-md shadow-sm flex items-center justify-center">
                    <img src="{{ secure_asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kab Sidoarjo" class="h-7 w-auto object-contain">
                </div>
                <div class="bg-white p-1 rounded-md shadow-sm flex items-center justify-center">
                    <img src="{{ secure_asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta" class="h-7 w-auto object-contain">
                </div>
            </div>
            <div class="overflow-hidden">
                <h1 class="text-xs font-bold text-white uppercase tracking-wider truncate">SIMPEG ADMIN</h1>
                <p class="text-[10px] text-amber-400 font-semibold truncate">Dispanperta Kab. Sidoarjo</p>
            </div>
        </div>

        <!-- Sidebar Links (Smooth Non-shifting Scroll) -->
        <nav class="flex-grow p-3 space-y-1 text-xs font-medium overflow-y-auto sidebar-scroll">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-3 py-2 rounded transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-800 text-white font-semibold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
               Dashboard
            </a>

            <a href="{{ route('admin.pegawai.index') }}" 
               class="flex items-center px-3 py-2 rounded transition {{ request()->routeIs('admin.pegawai.*') ? 'bg-emerald-800 text-white font-semibold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
               Kelola Pegawai
            </a>

            <a href="{{ route('admin.formasi-jabatan.index') }}" 
               class="flex items-center px-3 py-2 rounded transition {{ request()->routeIs('admin.formasi-jabatan.*') ? 'bg-emerald-800 text-white font-semibold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
               Formasi Jabatan
            </a>

            <a href="{{ route('admin.master-data.index') }}" 
               class="flex items-center px-3 py-2 rounded transition {{ request()->routeIs('admin.master-data.*') ? 'bg-emerald-800 text-white font-semibold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
               Master Data
            </a>

            <div class="pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3">Administrasi</div>

            <a href="{{ route('admin.pensiun.index') }}" 
               class="flex items-center px-3 py-2 rounded transition {{ request()->routeIs('admin.pensiun.*') ? 'bg-emerald-800 text-white font-semibold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
               Pengajuan Pensiun
            </a>

            <a href="{{ route('admin.kenaikan-pangkat.index') }}" 
               class="flex items-center px-3 py-2 rounded transition {{ request()->routeIs('admin.kenaikan-pangkat.*') ? 'bg-emerald-800 text-white font-semibold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
               Kenaikan Pangkat
            </a>

            <a href="{{ route('admin.activity-logs.index') }}" 
               class="flex items-center px-3 py-2 rounded transition {{ request()->routeIs('admin.activity-logs.*') ? 'bg-emerald-800 text-white font-semibold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
               Audit Log Aktivitas
            </a>

            <a href="{{ route('admin.import.form') }}" 
               class="flex items-center px-3 py-2 rounded transition {{ request()->routeIs('admin.import.*') ? 'bg-emerald-800 text-white font-semibold' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
               Import Excel
            </a>

            <a href="{{ route('admin.export.excel') }}" 
               class="flex items-center px-3 py-2 rounded transition hover:bg-slate-800 text-slate-400 hover:text-white">
               Export Excel
            </a>
        </nav>

        <!-- Public Switch & Footer -->
        <div class="p-3 border-t border-slate-800 bg-slate-950 text-xs flex-shrink-0">
            <a href="{{ route('public.dashboard') }}" target="_blank" class="block w-full py-2 px-3 text-center bg-slate-800 hover:bg-slate-700 text-emerald-300 font-semibold rounded transition">
                Lihat Web Publik &rarr;
            </a>
        </div>
    </aside>

    <!-- Main Section (Reliably Offset on Desktop via pure CSS) -->
    <div class="admin-main-wrapper flex flex-col min-w-0" style="box-sizing: border-box;">
        <!-- Topbar Header (Fixed Top) -->
        <header class="bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between shadow-xs sticky top-0 z-30" style="height: 64px; box-sizing: border-box;">
            <div class="flex items-center space-x-3">
                <!-- Mobile Hamburger Toggle Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none border border-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="bg-white p-1 rounded shadow-sm border border-slate-200 flex items-center space-x-1 md:hidden">
                    <img src="{{ secure_asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kab Sidoarjo" class="h-6 w-auto">
                    <img src="{{ secure_asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta" class="h-6 w-auto">
                </div>
                <div class="font-bold text-slate-800 text-xs sm:text-sm uppercase tracking-wider">
                    Panel Admin <span class="hidden sm:inline">- SIMPEG Dispanperta Sidoarjo</span>
                </div>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-3">
                <a href="{{ route('public.dashboard') }}" target="_blank" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold text-[11px] rounded border border-emerald-300 transition">
                    Web Publik
                </a>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs rounded border border-rose-300 transition">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Touch-Friendly Mobile Drawer Navigation Bar -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="bg-slate-900 text-slate-200 border-b border-slate-800 md:hidden p-3 space-y-1 shadow-lg">
            <div class="px-2 py-1 text-[11px] font-bold text-amber-400 uppercase tracking-wider">Menu Administrator</div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded text-xs {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">Dashboard</a>
            <a href="{{ route('admin.pegawai.index') }}" class="flex items-center px-3 py-2 rounded text-xs {{ request()->routeIs('admin.pegawai.*') ? 'bg-emerald-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">Kelola Pegawai</a>
            <a href="{{ route('admin.formasi-jabatan.index') }}" class="flex items-center px-3 py-2 rounded text-xs {{ request()->routeIs('admin.formasi-jabatan.*') ? 'bg-emerald-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">Formasi Jabatan</a>
            <a href="{{ route('admin.master-data.index') }}" class="flex items-center px-3 py-2 rounded text-xs {{ request()->routeIs('admin.master-data.*') ? 'bg-emerald-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">Master Data</a>
            <a href="{{ route('admin.pensiun.index') }}" class="flex items-center px-3 py-2 rounded text-xs {{ request()->routeIs('admin.pensiun.*') ? 'bg-emerald-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">Pengajuan Pensiun</a>
            <a href="{{ route('admin.kenaikan-pangkat.index') }}" class="flex items-center px-3 py-2 rounded text-xs {{ request()->routeIs('admin.kenaikan-pangkat.*') ? 'bg-emerald-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">Kenaikan Pangkat</a>
            <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center px-3 py-2 rounded text-xs {{ request()->routeIs('admin.activity-logs.*') ? 'bg-emerald-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">Audit Log Aktivitas</a>
            <a href="{{ route('admin.import.form') }}" class="flex items-center px-3 py-2 rounded text-xs {{ request()->routeIs('admin.import.*') ? 'bg-emerald-800 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800' }}">Import Excel</a>
            <a href="{{ route('admin.export.excel') }}" class="flex items-center px-3 py-2 rounded text-xs text-slate-300 hover:bg-slate-800">Export Excel</a>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-xs font-semibold px-4 sm:px-6 py-2.5 flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Content Area -->
        <main class="flex-grow p-4 sm:p-6 w-full min-w-0" style="box-sizing: border-box;">
            @yield('content')
        </main>
    </div>

</body>
</html>
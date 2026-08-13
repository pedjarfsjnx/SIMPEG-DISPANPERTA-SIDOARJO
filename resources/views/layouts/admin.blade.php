<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - SIMPEG Dispanperta Sidoarjo')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased flex flex-col md:flex-row md:h-screen md:overflow-hidden" x-data="{ mobileMenuOpen: false }">

    <!-- Desktop Sticky Fixed Sidebar Navigation -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 hidden md:flex flex-col border-r border-slate-800 h-screen sticky top-0">
        <!-- Brand Header with Crisp White Logo Card Wrapper -->
        <div class="p-4 bg-emerald-950 border-b border-emerald-900 flex items-center space-x-3 flex-shrink-0">
            <div class="flex items-center space-x-1.5 flex-shrink-0">
                <div class="bg-white p-1 rounded-md shadow-sm flex items-center justify-center">
                    <img src="{{ asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kab Sidoarjo" class="h-7 w-auto object-contain">
                </div>
                <div class="bg-white p-1 rounded-md shadow-sm flex items-center justify-center">
                    <img src="{{ asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta" class="h-7 w-auto object-contain">
                </div>
            </div>
            <div>
                <h1 class="text-xs font-bold text-white uppercase tracking-wider">SIMPEG ADMIN</h1>
                <p class="text-[10px] text-amber-400 font-semibold">Dispanperta Kab. Sidoarjo</p>
            </div>
        </div>

        <!-- Sidebar Links (Internal Scrollable) -->
        <nav class="flex-grow p-3 space-y-1 text-xs font-medium overflow-y-auto">
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

    <!-- Main Section (Scrolls Independently) -->
    <div class="flex-grow flex flex-col min-w-0 md:h-screen overflow-hidden w-full">
        <!-- Topbar -->
        <header class="bg-white border-b border-slate-200 py-3 px-4 sm:px-6 flex items-center justify-between shadow-xs flex-shrink-0 sticky top-0 z-20">
            <div class="flex items-center space-x-3">
                <!-- Mobile Hamburger Toggle Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none border border-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="bg-white p-1 rounded shadow-sm border border-slate-200 flex items-center space-x-1">
                    <img src="{{ asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kab Sidoarjo" class="h-6 w-auto">
                    <img src="{{ asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta" class="h-6 w-auto md:hidden">
                </div>
                <div class="font-bold text-slate-800 text-xs sm:text-sm uppercase tracking-wider">
                    Panel Admin <span class="hidden sm:inline">- SIMPEG Dispanperta Sidoarjo</span>
                </div>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-3">
                <a href="{{ route('public.dashboard') }}" target="_blank" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold text-[11px] rounded border border-emerald-300 transition">
                    Web Publik
                </a>
                <form method="POST" action="{{ route('logout') }}">
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
            <span>✅ {{ session('success') }}</span>
        </div>
        @endif

        <!-- Content Area (Scrolls Independently) -->
        <main class="flex-grow p-4 sm:p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

</body>
</html>

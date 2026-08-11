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
<body class="bg-slate-100 text-slate-800 antialiased flex min-h-screen">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 hidden md:flex flex-col border-r border-slate-800">
        <!-- Brand Header -->
        <div class="p-4 bg-emerald-950 border-b border-emerald-900 flex items-center space-x-3">
            <div class="w-8 h-8 bg-emerald-800 rounded flex items-center justify-center font-bold text-amber-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V9a2 2 0 012-2h2a2 2 0 012 2v12m-6 0h6"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xs font-bold text-white uppercase tracking-wider">SIMPEG ADMIN</h1>
                <p class="text-[10px] text-emerald-400">Dispanperta Kab. Sidoarjo</p>
            </div>
        </div>

        <!-- Sidebar Links -->
        <nav class="flex-grow p-3 space-y-1 text-xs font-medium">
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
        <div class="p-3 border-t border-slate-800 bg-slate-950 text-xs">
            <a href="{{ route('public.dashboard') }}" target="_blank" class="block w-full py-2 px-3 text-center bg-slate-800 hover:bg-slate-700 text-emerald-300 font-semibold rounded transition mb-2">
                Lihat Web Publik &rarr;
            </a>
        </div>
    </aside>

    <!-- Main Section -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white border-b border-slate-200 py-3 px-6 flex items-center justify-between">
            <div class="font-bold text-slate-800 text-xs uppercase tracking-wider">
                Panel Pengelolaan Kepegawaian
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-xs text-slate-600 font-medium hidden sm:inline">
                    {{ Auth::user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded border border-slate-300 transition">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-xs font-semibold px-6 py-2.5">
            {{ session('success') }}
        </div>
        @endif

        <!-- Content Area -->
        <main class="flex-grow p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

</body>
</html>

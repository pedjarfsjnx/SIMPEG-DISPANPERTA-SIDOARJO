<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMPEG - Dinas Pangan dan Pertanian Kabupaten Sidoarjo')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 flex flex-col min-h-screen">

    <!-- Header Kop Instansi -->
    <header class="bg-emerald-900 text-white border-b-4 border-amber-500 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-emerald-800 rounded flex items-center justify-center font-bold border border-emerald-700 text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V9a2 2 0 012-2h2a2 2 0 012 2v12m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs uppercase font-semibold text-emerald-300 tracking-wider">Pemerintah Kabupaten Sidoarjo</div>
                    <h1 class="text-lg sm:text-xl font-bold tracking-tight uppercase text-white">Dinas Pangan dan Pertanian</h1>
                    <p class="text-[11px] text-emerald-200">Sistem Informasi Kepegawaian Internal (SIMPEG)</p>
                </div>
            </div>

            <div class="flex items-center">
                <a href="{{ route('login') }}" class="inline-flex items-center px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-semibold rounded shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Portal Admin
                </a>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav class="bg-emerald-950 border-t border-emerald-800/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-2 py-2 text-xs font-medium overflow-x-auto">
                    <a href="{{ route('public.dashboard') }}" 
                       class="px-3 py-1.5 rounded transition {{ request()->routeIs('public.dashboard') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-200 hover:bg-emerald-900 hover:text-white' }}">
                       Dashboard
                    </a>
                    <a href="{{ route('public.pegawai.index') }}" 
                       class="px-3 py-1.5 rounded transition {{ request()->routeIs('public.pegawai.*') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-200 hover:bg-emerald-900 hover:text-white' }}">
                       Direktori Pegawai
                    </a>
                    <a href="{{ route('public.struktur-organisasi') }}" 
                       class="px-3 py-1.5 rounded transition {{ request()->routeIs('public.struktur-organisasi') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-200 hover:bg-emerald-900 hover:text-white' }}">
                       Struktur Organisasi
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-6 border-t border-slate-800 text-xs mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; {{ date('Y') }} Dinas Pangan dan Pertanian Kabupaten Sidoarjo.</p>
            <p class="text-slate-500">Sistem Informasi Kepegawaian (Read-Only Mode)</p>
        </div>
    </footer>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Admin - SIMPEG Dinas Pangan dan Pertanian Kabupaten Sidoarjo</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Ribbon -->
    <div class="bg-amber-500 text-slate-950 text-[11px] font-bold py-1 px-4 text-center tracking-wide uppercase shadow-sm">
        Pemerintah Kabupaten Sidoarjo &bull; Dinas Pangan dan Pertanian
    </div>

    <!-- Center Card Section -->
    <div class="flex-grow flex flex-col justify-center items-center px-4 py-8">
        
        <!-- Header Branding & Logos -->
        <div class="text-center space-y-3 mb-6 max-w-sm">
            <div class="flex items-center justify-center space-x-2">
                <div class="bg-white p-2 rounded-lg shadow-md border border-emerald-800/40 flex items-center justify-center h-14">
                    <img src="{{ secure_asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kab Sidoarjo" class="h-10 w-auto object-contain">
                </div>
                <div class="bg-white p-2 rounded-lg shadow-md border border-emerald-800/40 flex items-center justify-center h-14">
                    <img src="{{ secure_asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta" class="h-10 w-auto object-contain">
                </div>
            </div>

            <div>
                <h1 class="text-base font-bold text-white uppercase tracking-wider">Dinas Pangan dan Pertanian</h1>
                <p class="text-xs text-amber-400 font-medium">Kabupaten Sidoarjo &bull; Jawa Timur</p>
                <p class="text-[11px] text-slate-400 mt-1">Sistem Informasi Kepegawaian Internal (SIMPEG)</p>
            </div>
        </div>

        <!-- Form Card Container -->
        <div class="w-full sm:max-w-md bg-white text-slate-800 rounded-xl shadow-2xl border border-slate-200 p-6 sm:p-8 space-y-5">
            {{ $slot }}
        </div>

        <!-- Back Link to Public Portal -->
        <div class="mt-6 text-center">
            <a href="{{ route('public.dashboard') }}" class="inline-flex items-center text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition">
                &larr; Kembali ke Portal Publik SIMPEG Sidoarjo
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 text-center text-[11px] text-slate-500 border-t border-slate-900 bg-slate-950">
        &copy; {{ date('Y') }} Dinas Pangan dan Pertanian Kabupaten Sidoarjo. All rights reserved.
    </footer>

</body>
</html>

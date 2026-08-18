<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMPEG - Dinas Pangan dan Pertanian Kabupaten Sidoarjo')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 20s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 flex flex-col min-h-screen">

    <!-- Top Notice & Real-time Live Clock Bar Sidoarjo with Running Marquee Text -->
    <div class="bg-amber-500 text-slate-950 text-[11px] font-bold py-1 px-4 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 shadow-sm overflow-hidden">
        <div class="flex-1 overflow-hidden relative w-full sm:w-auto">
            <div class="animate-marquee font-extrabold uppercase tracking-wider">
                PEMERINTAH KABUPATEN SIDOARJO &bull; DINAS PANGAN DAN PERTANIAN &bull; SISTEM INFORMASI KEPEGAWAIAN (SIMPEG) &bull; PEMERINTAH KABUPATEN SIDOARJO &bull; DINAS PANGAN DAN PERTANIAN &bull; SISTEM INFORMASI KEPEGAWAIAN (SIMPEG)
            </div>
        </div>
        <!-- Live Real-Time Clock Widget -->
        <div id="liveClock" class="font-mono bg-slate-950 text-amber-400 px-3 py-0.5 rounded-full text-[11px] font-bold shadow-xs flex-shrink-0 z-10">
            🕒 Memuat waktu real-time...
        </div>
    </div>

    <!-- Header Kop Instansi Sidoarjo -->
    <header class="bg-emerald-950 text-white border-b-4 border-amber-500 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <div class="flex items-center space-x-4">
                <!-- Logos Wrapper in Crisp White Cards for High Contrast -->
                <div class="flex items-center space-x-2 flex-shrink-0">
                    <div class="bg-white p-1.5 rounded-lg shadow-sm border border-emerald-800/40 flex items-center justify-center h-14">
                        <img src="{{ asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kab Sidoarjo" class="h-11 w-auto object-contain">
                    </div>
                    <div class="bg-white p-1.5 rounded-lg shadow-sm border border-emerald-800/40 flex items-center justify-center h-14">
                        <img src="{{ asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta Sidoarjo" class="h-11 w-auto object-contain">
                    </div>
                </div>

                <div>
                    <div class="text-[11px] uppercase font-bold text-amber-400 tracking-wider flex items-center gap-1.5">
                        <span>Pemerintah Kabupaten Sidoarjo</span>
                        <span class="text-emerald-400">&bull;</span>
                        <span class="text-emerald-200 text-[10px] font-normal normal-case">Jawa Timur</span>
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold tracking-tight uppercase text-white leading-tight">Dinas Pangan dan Pertanian</h1>
                    <p class="text-[11px] text-emerald-200 mt-0.5">Sistem Informasi Kepegawaian Internal (SIMPEG)</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden lg:block text-[11px] text-emerald-200">
                    <div class="font-semibold text-white">Jl. Pahlawan No.KM.2, Jetis, Lemahputro</div>
                    <div>Kec. Sidoarjo, Kab. Sidoarjo 61213</div>
                </div>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav class="bg-emerald-900 border-t border-emerald-800/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-2 py-2 text-xs font-medium overflow-x-auto">
                    <a href="{{ route('public.dashboard') }}" 
                       class="px-3.5 py-1.5 rounded transition whitespace-nowrap {{ request()->routeIs('public.dashboard') ? 'bg-emerald-950 text-amber-400 font-bold border border-emerald-700 shadow-sm' : 'text-emerald-100 hover:bg-emerald-800 hover:text-white' }}">
                       Dashboard
                    </a>
                    <a href="{{ route('public.pegawai.index') }}" 
                       class="px-3.5 py-1.5 rounded transition whitespace-nowrap {{ request()->routeIs('public.pegawai.*') ? 'bg-emerald-950 text-amber-400 font-bold border border-emerald-700 shadow-sm' : 'text-emerald-100 hover:bg-emerald-800 hover:text-white' }}">
                       Direktori Pegawai
                    </a>
                    <a href="{{ route('public.struktur-organisasi') }}" 
                       class="px-3.5 py-1.5 rounded transition whitespace-nowrap {{ request()->routeIs('public.struktur-organisasi') ? 'bg-emerald-950 text-amber-400 font-bold border border-emerald-700 shadow-sm' : 'text-emerald-100 hover:bg-emerald-800 hover:text-white' }}">
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

    <!-- Footer Khas Sidoarjo -->
    <footer class="bg-slate-950 text-slate-400 py-6 border-t-2 border-amber-500 text-xs mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div>
                <p class="font-bold text-slate-200">&copy; {{ date('Y') }} Dinas Pangan dan Pertanian Kabupaten Sidoarjo.</p>
                <p class="text-slate-500 text-[11px]">Jl. Pahlawan No.KM.2, Jetis, Lemahputro, Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur 61213</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-2.5 py-1 bg-emerald-950 text-emerald-300 font-semibold rounded border border-emerald-800 text-[11px]">
                    SIMPEG SIDOARJO
                </span>
            </div>
        </div>
    </footer>

    <!-- Real-time Live Clock Script -->
    <script>
        function updateLiveClock() {
            const clockEl = document.getElementById('liveClock');
            if (!clockEl) return;
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const dayNum = String(now.getDate()).padStart(2, '0');
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            clockEl.innerHTML = `🕒 ${dayName}, ${dayNum} ${monthName} ${year} &bull; ${hours}:${minutes}:${seconds} WIB`;
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();
    </script>
</body>
</html>

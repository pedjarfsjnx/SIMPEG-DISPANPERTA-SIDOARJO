<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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
</head>
<body class="bg-slate-100 text-slate-800 flex flex-col min-h-screen">

    <!-- VIP Ultra-Smooth Preloader / Splash Screen Sidoarjo -->
    <div id="appPreloader" class="fixed inset-0 z-[9999] bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 flex flex-col items-center justify-center text-white transition-all duration-700 ease-[cubic-bezier(0.16,1,0.3,1)]">
        <!-- Ambient Background Glow -->
        <div class="absolute w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
        <div class="absolute w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none animate-pulse" style="animation-delay: 1s;"></div>

        <div id="preloaderCard" class="relative flex flex-col items-center transition-all duration-700 ease-out transform scale-100 opacity-100">
            <div class="relative flex items-center justify-center mb-6">
                <!-- Smooth Pulsing Glow Rings -->
                <div class="absolute w-32 h-32 rounded-full border border-amber-500/30 animate-[ping_2s_cubic-bezier(0,0,0.2,1)_infinite]"></div>
                <div class="absolute w-24 h-24 rounded-full border-2 border-emerald-400/40 border-t-amber-400 animate-spin" style="animation-duration: 1.5s;"></div>
                
                <!-- Logo Box with Glass & Floating Effect -->
                <div class="relative bg-white/95 backdrop-blur-md p-3.5 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] flex items-center space-x-3 border border-amber-400/60">
                    <img src="{{ asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kab Sidoarjo" class="h-12 w-auto object-contain drop-shadow">
                    <img src="{{ asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta Sidoarjo" class="h-12 w-auto object-contain drop-shadow">
                </div>
            </div>
            
            <h2 class="text-xl font-extrabold uppercase tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-400 to-amber-200 text-center px-4 drop-shadow">
                Dinas Pangan dan Pertanian
            </h2>
            <p class="text-xs font-medium text-emerald-200/90 mt-1 uppercase tracking-[0.25em] text-center">
                Pemerintah Kabupaten Sidoarjo
            </p>
            
            <!-- Smooth Progress Bar Indicator -->
            <div class="mt-6 w-48 bg-emerald-950/80 h-1.5 rounded-full overflow-hidden border border-emerald-800/60 p-0.5 shadow-inner">
                <div id="preloaderBar" class="bg-gradient-to-r from-amber-500 to-emerald-400 h-full rounded-full w-0 transition-all duration-700 ease-out"></div>
            </div>
            <span class="text-[10px] font-mono text-emerald-300/80 mt-2 tracking-wider uppercase">Memuat Sistem...</span>
        </div>
    </div>

    <script>
        (function() {
            const bar = document.getElementById('preloaderBar');
            const preloader = document.getElementById('appPreloader');
            const card = document.getElementById('preloaderCard');
            
            setTimeout(function() { if(bar) bar.style.width = '60%'; }, 100);
            setTimeout(function() { if(bar) bar.style.width = '100%'; }, 400);

            function dismissPreloader() {
                if (!preloader) return;
                if (card) {
                    card.style.transform = 'scale(0.92)';
                    card.style.opacity = '0';
                }
                setTimeout(function() {
                    preloader.style.opacity = '0';
                    preloader.style.filter = 'blur(12px)';
                    setTimeout(function() { preloader.remove(); }, 700);
                }, 200);
            }

            if (document.readyState === 'complete') {
                setTimeout(dismissPreloader, 650);
            } else {
                window.addEventListener('load', function() {
                    setTimeout(dismissPreloader, 650);
                });
            }
        })();
    </script>

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
                <div class="text-right hidden lg:block text-[11px] text-emerald-200 border-r border-emerald-800 pr-3">
                    <div class="font-semibold text-white">Jl. Pahlawan No.KM.2, Jetis, Lemahputro</div>
                    <div>Kec. Sidoarjo, Kab. Sidoarjo 61213</div>
                </div>

                <a href="{{ route('login') }}" class="inline-flex items-center px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded shadow transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Portal Admin
                </a>
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

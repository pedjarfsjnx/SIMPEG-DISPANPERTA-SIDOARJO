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
            @keyframes loading {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(0%); }
            100% { transform: translateX(100%); }
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex flex-col justify-between">
    <!-- Preloader Splash Screen: Logo Kabupaten Sidoarjo & Dispanperta -->
    <div id="simpeg-preloader" class="fixed inset-0 z-[9999] bg-slate-950 flex flex-col items-center justify-center transition-opacity duration-500 ease-out select-none">
        <div class="relative flex flex-col items-center justify-center p-6 text-center max-w-sm">
            
            <!-- Animated Dual Logos Wrapper with Pulsing Ambient Glow -->
            <div class="relative flex items-center justify-center space-x-3 mb-5">
                <div class="absolute -inset-4 bg-emerald-500/20 rounded-full blur-xl animate-pulse"></div>
                
                <!-- Logo 1: Kab Sidoarjo -->
                <div class="relative bg-white p-2.5 rounded-2xl shadow-xl border border-emerald-500/30 transform transition duration-500 hover:scale-105 animate-bounce" style="animation-duration: 2s;">
                    <img src="{{ secure_asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kab Sidoarjo" class="h-14 w-auto object-contain">
                </div>

                <!-- Gold Separator Dot -->
                <div class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></div>

                <!-- Logo 2: Dispanperta -->
                <div class="relative bg-white p-2.5 rounded-2xl shadow-xl border border-amber-500/30 transform transition duration-500 hover:scale-105 animate-bounce" style="animation-duration: 2s; animation-delay: 0.3s;">
                    <img src="{{ secure_asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta Sidoarjo" class="h-14 w-auto object-contain">
                </div>
            </div>

            <!-- Typography Branding -->
            <h2 class="text-white font-extrabold text-sm uppercase tracking-wider mb-1">
                Pemerintah Kabupaten Sidoarjo
            </h2>
            <p class="text-amber-400 font-bold text-xs uppercase tracking-wide mb-1">
                Dinas Pangan dan Pertanian
            </p>
            <p class="text-slate-400 text-[11px] font-medium tracking-normal mb-5">
                Sistem Informasi Kepegawaian (SIMPEG)
            </p>

            <!-- Sleek Gradient Loading Progress Bar -->
            <div class="w-44 h-1.5 bg-slate-800/80 rounded-full overflow-hidden border border-slate-700/50 relative">
                <div class="h-full bg-gradient-to-r from-emerald-500 via-amber-400 to-emerald-400 rounded-full w-full animate-[loading_1.2s_ease-in-out_infinite]"></div>
            </div>
            <span class="text-[10px] text-slate-500 font-mono mt-2 tracking-widest uppercase">Memuat Sistem...</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const preloader = document.getElementById('simpeg-preloader');
            if (preloader) {
                const minLoadTime = 600; // 600ms display time for smooth visual aesthetic
                const startTime = Date.now();
                
                const hidePreloader = () => {
                    const elapsedTime = Date.now() - startTime;
                    const remainingTime = Math.max(0, minLoadTime - elapsedTime);
                    
                    setTimeout(() => {
                        preloader.style.opacity = '0';
                        preloader.style.pointerEvents = 'none';
                        setTimeout(() => {
                            preloader.style.display = 'none';
                        }, 500);
                    }, remainingTime);
                };

                if (document.readyState === 'complete') {
                    hidePreloader();
                } else {
                    window.addEventListener('load', hidePreloader);
                    setTimeout(hidePreloader, 2500); // Safety fallback
                }
            }
        });
    </script>

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

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

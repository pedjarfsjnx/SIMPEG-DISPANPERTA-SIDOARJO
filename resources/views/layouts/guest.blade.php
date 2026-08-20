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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        html, body, input, select, textarea, button {
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex flex-col justify-between">
    <script>
        // Bulletproof Session Check with try-catch (Protects Incognito & iOS Safari)
        try {
            if (sessionStorage.getItem('simpeg_splash_shown')) {
                document.write('<style>#simpeg-preloader { display: none !important; }</style>');
            }
        } catch (e) {}
    </script>

    <!-- Preloader Splash Screen: Bulletproof Sequential Cinematic Logo (Auto-Dismiss Guaranteed) -->
    <div id="simpeg-preloader" class="fixed inset-0 z-[9999] bg-slate-950 flex flex-col items-center justify-center transition-opacity duration-400 ease-out select-none cursor-pointer" onclick="this.remove()">
        
        <!-- Ambient Background Glow -->
        <div class="absolute w-72 h-72 bg-emerald-500/15 rounded-full blur-3xl animate-pulse pointer-events-none"></div>

        <!-- Stage 1: Logo Kabupaten Sidoarjo -->
        <div id="splash-stage-1" class="flex flex-col items-center justify-center p-6 text-center transition-all duration-300 transform opacity-100 scale-100">
            <div class="relative bg-white p-4 sm:p-5 rounded-3xl shadow-2xl border-2 border-emerald-500/40 mb-3 transform transition duration-500 hover:scale-105">
                <img src="{{ asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kabupaten Sidoarjo" class="h-24 sm:h-32 w-auto object-contain">
            </div>
            <h2 class="text-white font-extrabold text-sm sm:text-lg uppercase tracking-wider mb-0.5">
                Pemerintah Kabupaten Sidoarjo
            </h2>
            <p class="text-amber-400 font-bold text-[11px] sm:text-xs uppercase tracking-widest">
                Jawa Timur
            </p>
        </div>

        <!-- Stage 2: Logo Dispanperta Sidoarjo -->
        <div id="splash-stage-2" class="flex flex-col items-center justify-center p-6 text-center transition-all duration-300 transform opacity-0 scale-95 hidden">
            <div class="relative bg-white p-4 sm:p-5 rounded-3xl shadow-2xl border-2 border-amber-500/40 mb-3 transform transition duration-500 hover:scale-105">
                <img src="{{ asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta Sidoarjo" class="h-24 sm:h-32 w-auto object-contain">
            </div>
            <h2 class="text-white font-extrabold text-sm sm:text-lg uppercase tracking-wider mb-0.5">
                Dinas Pangan dan Pertanian
            </h2>
            <p class="text-emerald-400 font-bold text-[11px] sm:text-xs uppercase tracking-widest mb-4">
                Sistem Informasi Kepegawaian (SIMPEG)
            </p>

            <!-- Progress Bar -->
            <div class="w-40 sm:w-48 h-1.5 bg-slate-800 rounded-full overflow-hidden border border-slate-700/60 relative">
                <div class="h-full bg-gradient-to-r from-emerald-500 via-amber-400 to-emerald-400 rounded-full w-full animate-[loading_1s_ease-in-out_infinite]"></div>
            </div>
            <span class="text-[9px] sm:text-[10px] text-slate-500 font-mono mt-1.5 tracking-widest uppercase">Membuka SIMPEG...</span>
        </div>
    </div>

    <script>
        (function() {
            var preloader = document.getElementById('simpeg-preloader');
            if (!preloader) return;

            // Safe session check
            try {
                if (sessionStorage.getItem('simpeg_splash_shown')) {
                    preloader.remove();
                    return;
                }
                sessionStorage.setItem('simpeg_splash_shown', 'true');
            } catch(e) {}

            var stage1 = document.getElementById('splash-stage-1');
            var stage2 = document.getElementById('splash-stage-2');

            // 1. Stage 1 to Stage 2 transition after 750ms
            setTimeout(function() {
                if (stage1 && stage2) {
                    stage1.classList.remove('opacity-100', 'scale-100');
                    stage1.classList.add('opacity-0', 'scale-90');
                    
                    setTimeout(function() {
                        stage1.classList.add('hidden');
                        stage2.classList.remove('hidden');
                        void stage2.offsetWidth; // force reflow
                        stage2.classList.remove('opacity-0', 'scale-95');
                        stage2.classList.add('opacity-100', 'scale-100');
                    }, 150);
                }
            }, 750);

            // 2. Guaranteed Dismiss Function
            function closePreloader() {
                if (!preloader) return;
                preloader.style.opacity = '0';
                preloader.style.pointerEvents = 'none';
                setTimeout(function() {
                    if (preloader && preloader.parentNode) {
                        preloader.parentNode.removeChild(preloader);
                    }
                }, 400);
            }

            // Always dismiss gracefully after 1.7s regardless of network status
            setTimeout(closePreloader, 1700);

            // Safety fail-safe timeout at 2.5s
            setTimeout(function() {
                var p = document.getElementById('simpeg-preloader');
                if (p) p.remove();
            }, 2500);
        })();
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
                    <img src="{{ asset('logo/logo kabupaten sidoarjo.png') }}" alt="Logo Kab Sidoarjo" class="h-10 w-auto object-contain">
                </div>
                <div class="bg-white p-2 rounded-lg shadow-md border border-emerald-800/40 flex items-center justify-center h-14">
                    <img src="{{ asset('logo/logo dispanperta sidoarjo.png') }}" alt="Logo Dispanperta" class="h-10 w-auto object-contain">
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

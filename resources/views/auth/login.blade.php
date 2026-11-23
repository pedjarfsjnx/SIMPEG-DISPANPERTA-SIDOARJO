<x-guest-layout>
    <div class="border-b border-slate-100 pb-4 text-center">
        <h2 class="text-lg font-bold text-slate-900">Login Administrator</h2>
        <p class="text-xs text-slate-500 mt-0.5">Masukkan alamat email & password akun pengelola kepegawaian Anda.</p>
    </div>

    <!-- Informasi Akses Akun Internal -->
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3.5 text-xs text-slate-800 space-y-2">
        <div class="flex items-center justify-between border-b border-amber-200/70 pb-1.5">
            <span class="font-bold text-amber-900 uppercase tracking-wider text-[11px]">Akses Internal Kepegawaian</span>
            <span class="text-[10px] bg-amber-200 text-amber-900 font-semibold px-2 py-0.5 rounded">Resmi Dispanperta</span>
        </div>
        <div class="space-y-1.5 text-[11px]">
            <div class="flex justify-between items-center bg-white p-2 rounded border border-amber-100">
                <div>
                    <span class="text-slate-500 block text-[10px]">Email Utama:</span>
                    <span class="font-mono font-semibold text-slate-900 select-all">admin@dispanperta.sidoarjo.go.id</span>
                </div>
                <button type="button" onclick="isiKredensial('admin@dispanperta.sidoarjo.go.id', 'password')" class="text-[10px] font-bold bg-amber-700 hover:bg-amber-800 text-white px-2.5 py-1 rounded transition">
                    Gunakan
                </button>
            </div>
            <div class="flex justify-between items-center bg-white p-2 rounded border border-amber-100">
                <div>
                    <span class="text-slate-500 block text-[10px]">Email Alternatif:</span>
                    <span class="font-mono font-semibold text-slate-900 select-all">admin@admin.com</span>
                </div>
                <button type="button" onclick="isiKredensial('admin@admin.com', 'password')" class="text-[10px] font-bold bg-slate-700 hover:bg-slate-800 text-white px-2.5 py-1 rounded transition">
                    Gunakan
                </button>
            </div>
            <div class="flex justify-between items-center bg-white p-2 rounded border border-amber-100">
                <div>
                    <span class="text-slate-500 block text-[10px]">Password:</span>
                    <span class="font-mono font-bold text-slate-900 select-all">password</span>
                </div>
                <span class="text-[10px] text-slate-400 italic">Otomatis terisi saat klik "Gunakan"</span>
            </div>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="/login" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email', 'admin@dispanperta.sidoarjo.go.id') }}" 
                   required 
                   autofocus 
                   autocomplete="username" 
                   class="w-full text-xs rounded border-slate-300 focus:border-emerald-700 focus:ring-emerald-700 py-2 px-3">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   value="password"
                   required 
                   autocomplete="current-password" 
                   class="w-full text-xs rounded border-slate-300 focus:border-emerald-700 focus:ring-emerald-700 py-2 px-3">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" checked class="rounded border-slate-300 text-emerald-800 focus:ring-emerald-700" name="remember">
                <span class="ms-2 text-xs text-slate-600 font-medium">Ingat Sesi Login</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full py-2.5 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded shadow transition flex items-center justify-center space-x-2">
                <span>Masuk ke Panel Admin &rarr;</span>
            </button>
        </div>
    </form>

    <script>
        function isiKredensial(email, pass) {
            var emailInput = document.getElementById('email');
            var passInput = document.getElementById('password');
            if (emailInput) emailInput.value = email;
            if (passInput) passInput.value = pass;
        }
    </script>
</x-guest-layout>

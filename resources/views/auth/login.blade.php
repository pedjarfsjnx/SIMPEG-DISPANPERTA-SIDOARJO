<x-guest-layout>
    <div class="border-b border-slate-100 pb-4 text-center">
        <h2 class="text-lg font-bold text-slate-900">Login Administrator</h2>
        <p class="text-xs text-slate-500 mt-0.5">Masukkan alamat email & password akun pengelola kepegawaian Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
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
                   required 
                   autocomplete="current-password" 
                   class="w-full text-xs rounded border-slate-300 focus:border-emerald-700 focus:ring-emerald-700 py-2 px-3">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-800 focus:ring-emerald-700" name="remember">
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
</x-guest-layout>

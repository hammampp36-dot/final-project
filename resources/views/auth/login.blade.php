<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Selamat Datang Kembali</h2>
        <p class="text-xs text-gray-500 mt-1">Masuk untuk mengelola lahan, jadwal tanam, dan buku kas Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 mb-1">Alamat Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', 'petani@agrismart.id') }}"
                required
                autofocus
                autocomplete="username"
                class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                placeholder="nama@email.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-gray-700 mb-1">Kata Sandi</label>
            <input
                id="password"
                type="password"
                name="password"
                value="password"
                required
                autocomplete="current-password"
                class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-gray-600">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-emerald-700 hover:text-emerald-800 font-semibold" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button
                type="submit"
                class="w-full py-2.5 px-4 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition duration-150"
            >
                Masuk ke Akun
            </button>
        </div>

        <div class="text-center pt-3 border-t border-gray-100 text-xs text-gray-600">
            Belum punya akun mitra?
            <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-800">
                Daftar sekarang
            </a>
        </div>
    </form>
</x-guest-layout>

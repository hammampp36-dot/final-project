<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agrismart - Sistem Manajemen Pertanian Pintar & QR Lahan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-b from-emerald-50/50 via-white to-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Navigation Bar -->
    <header class="w-full border-b border-emerald-100 bg-white/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                <div class="h-9 w-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-emerald-950">Agri<span class="text-emerald-600">smart</span></span>
            </a>

            <nav class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-xs transition">
                            Masuk ke Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-emerald-700 transition">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-xs transition">
                                Daftar Gratis
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="grow">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 text-center">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-600 animate-ping"></span>
                Inovasi Pertanian Digital Terintegrasi
            </div>

            <h1 class="text-4xl sm:text-6xl font-black text-emerald-950 tracking-tight max-w-4xl mx-auto leading-tight">
                Kelola Lahan, Otomatisasi Jadwal Tanam & Pantau Keuntungan Tani
            </h1>

            <p class="mt-6 text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Agrismart membantu petani modern mendigitalkan plot lahan dengan QR Code unik, mendapatkan timeline perawatan otomatis berbasis komoditas tanaman, serta merekap arus kas secara transparan.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base rounded-2xl shadow-md transition">
                        Buka Dashboard Pertanian
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base rounded-2xl shadow-md transition">
                        Mulai Registrasi Petani
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3.5 bg-white hover:bg-gray-50 border border-gray-300 text-gray-800 font-semibold text-base rounded-2xl transition">
                        Masuk ke Akun
                    </a>
                @endauth
            </div>

            <!-- Feature 3-Grid -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8 text-left">

                <!-- 1. Lahan & QR -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:border-emerald-200 transition">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">QR Code Lahan Unik</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Setiap petak lahan terdaftar langsung memiliki QR Code SVG siap cetak untuk identifikasi lapangan, pelacakan kepemilikan, dan riwayat aktivitas.
                    </p>
                </div>

                <!-- 2. Smart Jadwal -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:border-emerald-200 transition">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Smart Jadwal Budidaya</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Pilih tanaman (Cabai Merah, Tomat, Jagung, Padi, dll) dan sistem akan otomatis menyusun timeline penyiraman, pemupukan berkala, hingga panen raya.
                    </p>
                </div>

                <!-- 3. Keuangan & Profit -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:border-emerald-200 transition">
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pencatatan Keuangan & Kas</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Catat biaya modal benih, pupuk, upah buruh tani, dan hasil penjualan hasil panen dengan kalkulasi otomatis saldo bersih (net profit) serta ekspor CSV.
                    </p>
                </div>

            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-100 bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-emerald-950">Agrismart Indonesia</span>
                <span>&bull; Solusi Pertanian Cerdas Masa Depan</span>
            </div>
            <div>
                &copy; {{ date('Y') }} Agrismart. Dikembangkan dengan Laravel, Tailwind CSS, & Simple QR Code.
            </div>
        </div>
    </footer>

</body>
</html>

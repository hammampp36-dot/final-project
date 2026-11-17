<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Agrismart') }} &mdash; Masuk / Daftar</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-emerald-50 via-slate-50 to-teal-100 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- App Branding Header -->
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-2.5">
                    <span class="text-3xl">🌾</span>
                    <span class="text-2xl font-black tracking-tight text-emerald-900">Agrismart</span>
                </a>
                <p class="text-xs text-emerald-700 mt-1 font-medium">Sistem Manajemen Lahan & Budidaya Presisi</p>
            </div>

            <!-- Card Content -->
            <div class="bg-white rounded-3xl shadow-xl shadow-emerald-950/5 border border-emerald-100 p-8">
                {{ $slot }}
            </div>

            <!-- Quick Demo Credential Info Pill -->
            <div class="mt-6 text-center text-xs text-gray-500">
                <p class="mb-1">Akun Percobaan Tersedia:</p>
                <div class="inline-flex flex-wrap justify-center gap-2 text-[11px]">
                    <span class="bg-white/80 border border-emerald-200 text-emerald-800 px-2.5 py-1 rounded-full font-mono">
                        Petani: petani@agrismart.id
                    </span>
                    <span class="bg-white/80 border border-indigo-200 text-indigo-800 px-2.5 py-1 rounded-full font-mono">
                        Admin: admin@agrismart.id
                    </span>
                </div>
            </div>
        </div>
    </body>
</html>

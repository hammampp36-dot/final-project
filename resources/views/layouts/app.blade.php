<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Agrismart') }} &mdash; Smart Agricultural Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation Bar -->
            @include('layouts.navigation')

            <!-- Global Alert Banners (if any) -->
            @if (session('status'))
                <div class="bg-blue-600 text-white text-xs font-semibold py-2.5 px-4 text-center">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-600 text-white text-xs font-semibold py-2.5 px-4 text-center">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-gray-200 shadow-2xs">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Compact Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 text-center text-xs text-gray-500">
                <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-emerald-700">Agrismart</span>
                        <span>&bull;</span>
                        <span>Platform Pertanian Terpadu Berbasis Presisi</span>
                    </div>
                    <div class="text-gray-400">
                        Hak Akses: <strong class="text-gray-600 uppercase">{{ Auth::user()?->role ?? 'Tamu' }}</strong>
                    </div>
                </div>
            </footer>
        </div>

        @livewireScripts
    </body>
</html>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                    🌾 {{ __('Kelola Lahan & QR Code') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Daftar semua petak lahan pertanian Anda, penerbitan QR Code otomatis, dan unduhan SVG.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('lands.scan') }}" class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 border border-emerald-200 text-emerald-800 font-semibold text-xs rounded-xl shadow-2xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    Scan / Cari Kode QR
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:land-manager />
        </div>
    </div>
</x-app-layout>

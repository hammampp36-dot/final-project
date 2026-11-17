<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                    🧮 {{ __('Kalkulator Kebutuhan Pupuk, Benih & Panen (Real-Time)') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Hitung estimasi populasi tanaman, dosis pupuk terpadu, dan potensi keuntungan panen secara instan tanpa reload halaman.</p>
            </div>
            @if ($selectedCrop)
                <a href="{{ route('schedules.create', ['crop_id' => $selectedCrop->id]) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Jadwalkan Tanam Ini
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:farm-calculator :crop-id="$selectedCrop?->id" :area="$areaM2" />
        </div>
    </div>
</x-app-layout>

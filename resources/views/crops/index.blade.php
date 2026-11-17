<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                    🌱 {{ __('Katalog Tanaman & Panduan Budidaya') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Eksplorasi komoditas unggul, estimasi masa panen, serta panduan praktis penanaman dan pemupukan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:crop-manager />
        </div>
    </div>
</x-app-layout>

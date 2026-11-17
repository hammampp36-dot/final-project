<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-indigo-950 leading-tight">
                    🗺️ {{ __('Supervisi Lahan Seluruh Petani') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Monitoring dan inspeksi terpadu seluruh petak lahan pertanian mitra terdaftar.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:land-manager :is-admin="true" />
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-indigo-950 leading-tight">
                    🏛️ {{ __('Audit & Master Kas Keuangan Global') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Audit arus kas masuk/keluar seluruh petani, otorisasi koreksi transaksi, dan ekspor CSV global.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.transactions.export') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor CSV Global
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:transaction-manager :is-admin="true" />
        </div>
    </div>
</x-app-layout>

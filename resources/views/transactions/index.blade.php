<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                    💵 {{ __('Buku Kas Keuangan Pertanian') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Pencatatan real-time arus kas masuk/keluar, otomatisasi laba/rugi bersih, dan perlindungan audit integritas kas.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('transactions.export') }}" class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 border border-emerald-200 text-emerald-800 font-semibold text-xs rounded-xl shadow-2xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Unduh Rekap CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:transaction-manager />
        </div>
    </div>
</x-app-layout>

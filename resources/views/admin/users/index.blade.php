<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                        👥 Manajemen Pengguna
                    </span>
                    <span class="text-xs text-gray-400">&bull; Administrator</span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                    Data Petani Mitra Agrismart (Live Search)
                </h2>
                <p class="text-sm text-gray-600 mt-0.5">Kelola akun, status keaktifan, dan monitoring lahan yang dikelola masing-masing petani.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-900 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-indigo-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Livewire Instant Real-Time Search & Table -->
            <livewire:farmer-search />

        </div>
    </div>
</x-app-layout>

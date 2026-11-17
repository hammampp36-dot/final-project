<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                    🗓️ {{ __('Jadwal & Tracker Budidaya Tani') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Generator otomatis timeline tanam cerdas, peringatan keterlambatan (overdue), dan checklist tugas harian.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:schedule-manager />
        </div>
    </div>
</x-app-layout>

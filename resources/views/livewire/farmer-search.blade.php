<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="pb-2 border-b border-gray-200">
            <h2 class="font-bold text-2xl text-indigo-950 leading-tight">
                👨‍🌾 {{ __('Manajemen Data Mitra Petani') }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">Pencarian real-time, pantauan jumlah lahan aktif, jadwal tanam, dan buku kas petani.</p>
        </div>

        <!-- Live Search Input Box -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Ketik nama petani atau email untuk pencarian instan (Livewire)..."
                           class="w-full rounded-2xl border-gray-300 pl-11 focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs text-sm">
                </div>

                <!-- Loading indicator -->
                <div wire:loading class="text-xs font-bold text-indigo-600 animate-pulse flex items-center gap-1.5 shrink-0">
                    <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memfilter data real-time...
                </div>
            </div>

            @if (!empty($search))
                <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                    <span>Mencari: <strong class="text-indigo-800">"{{ $search }}"</strong></span>
                    <button type="button" wire:click="$set('search', '')" class="text-rose-600 hover:underline font-bold">
                        [Reset Pencarian]
                    </button>
                </div>
            @endif
        </div>

        <!-- Tabel Data Petani Real-Time -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
            @if ($farmers->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-800">Tidak Ada Data Petani Ditemukan</h4>
                    <p class="text-xs text-gray-500 mt-1">Coba kata kunci pencarian yang lain.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Nama & Email Petani</th>
                                <th class="px-6 py-4 text-center">Total Lahan</th>
                                <th class="px-6 py-4 text-center">Jadwal Tanam</th>
                                <th class="px-6 py-4 text-center">Riwayat Kas</th>
                                <th class="px-6 py-4">Terdaftar Sejak</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($farmers as $farmer)
                                <tr class="hover:bg-indigo-50/20 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $farmer->name }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $farmer->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            {{ $farmer->lands_count }} Petak
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                            {{ $farmer->schedules_count }} Agenda
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $farmer->transactions_count }} Data
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                                        {{ $farmer->created_at->isoFormat('D MMMM Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('admin.users.show', $farmer) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-lg transition duration-150">
                                            Detail Profil &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Livewire Pagination Links -->
                @if ($farmers->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $farmers->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

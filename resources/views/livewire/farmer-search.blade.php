<div class="space-y-6">

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
            <div class="p-12 text-center text-gray-400">
                <p class="text-base font-semibold">Tidak ada petani yang cocok dengan pencarian "{{ $search }}".</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Nama Petani</th>
                            <th class="px-6 py-4">Email Kontak</th>
                            <th class="px-6 py-4 text-center">Jumlah Lahan</th>
                            <th class="px-6 py-4 text-center">Agenda Tanam</th>
                            <th class="px-6 py-4 text-center">Transaksi Kas</th>
                            <th class="px-6 py-4">Bergabung Sejak</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($farmers as $farmer)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ $farmer->name }}</div>
                                    <span class="text-2xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full font-semibold">
                                        ID #{{ $farmer->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                    {{ $farmer->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800">
                                        {{ $farmer->lands_count }} Petak
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800">
                                        {{ $farmer->schedules_count }} Agenda
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-800">
                                        {{ $farmer->transactions_count }} Data
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400">
                                    {{ $farmer->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.show', $farmer) }}" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-lg transition">
                                            Lihat Detail
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $farmer) }}" method="POST" onsubmit="return confirm('Hapus seluruh akun petani ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t border-gray-100">
                {{ $farmers->links() }}
            </div>
        @endif
    </div>

</div>

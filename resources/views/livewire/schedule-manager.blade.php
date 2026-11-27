<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="pb-2 border-b border-gray-200">
            <h2 class="font-bold text-2xl {{ $isAdmin ? 'text-indigo-950' : 'text-emerald-900' }} leading-tight">
                {{ $isAdmin ? '📅 Supervisi & Monitoring Agenda Global' : '🗓️ Jadwal & Tracker Budidaya Tani' }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                {{ $isAdmin ? 'Supervisi jadwal tanam dan aktivitas tani seluruh mitra serta deteksi tugas terlambat (overdue).' : 'Generator otomatis timeline tanam cerdas, peringatan keterlambatan (overdue), dan checklist tugas harian.' }}
            </p>
        </div>

        <!-- Flash Notification -->
        @if (session()->has('success'))
            <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200 flex items-center justify-between text-emerald-800 shadow-xs">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-sm">✕</button>
            </div>
        @endif

        @if (!$isAdmin)
            <!-- Form Generator Rekomendasi Jadwal Tanam Cerdas -->
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span>🗓️</span> Buat Rencana & Timeline Budidaya
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Sistem cerdas akan otomatis memetakan tanggal pemupukan, penyiraman rutin, hingga estimasi panen.
                        </p>
                    </div>
                    <div class="inline-flex p-1 rounded-xl bg-gray-100 text-xs font-semibold">
                        <button
                            type="button"
                            wire:click="$set('isAutoGenerator', true)"
                            class="px-3 py-1.5 rounded-lg transition {{ $isAutoGenerator ? 'bg-white text-emerald-700 shadow-2xs' : 'text-gray-500' }}"
                        >
                            Auto Generator
                        </button>
                        <button
                            type="button"
                            wire:click="$set('isAutoGenerator', false)"
                            class="px-3 py-1.5 rounded-lg transition {{ !$isAutoGenerator ? 'bg-white text-emerald-700 shadow-2xs' : 'text-gray-500' }}"
                        >
                            Input Manual
                        </button>
                    </div>
                </div>

                @if ($isAutoGenerator)
                    <form wire:submit="generateSchedules" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Pilih Komoditas -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Komoditas Tanaman</label>
                                <select
                                    wire:model.live="crop_id"
                                    class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                >
                                    @foreach ($crops as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->estimated_harvest_days }} Hari Masa Panen)</option>
                                    @endforeach
                                </select>
                                @error('crop_id') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Pilih Petak Lahan -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tautkan ke Petak Lahan (Opsional)</label>
                                <select
                                    wire:model="land_id"
                                    class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                >
                                    <option value="">-- Tanpa Tautan Lahan Spesifik --</option>
                                    @foreach ($lands as $l)
                                        <option value="{{ $l->id }}">{{ $l->name }} ({{ number_format($l->area_size, 0) }} m²)</option>
                                    @endforeach
                                </select>
                                @error('land_id') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tanggal Mulai Tanam -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Mulai Tanam / Semai</label>
                                <input
                                    type="date"
                                    wire:model.live="planting_date"
                                    class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                />
                                @error('planting_date') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Live Dynamic Preview Info Box -->
                        @if ($selectedCrop && $estimatedHarvest)
                            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-emerald-900">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">✨</span>
                                    <div>
                                        Estimasi Panen Raya: <strong>{{ $estimatedHarvest->isoFormat('D MMMM Y') }}</strong>
                                        <span class="text-emerald-700">({{ $selectedCrop->estimated_harvest_days }} hari dari tanggal tanam)</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-200 text-emerald-800">
                                    Komoditas: {{ $selectedCrop->category }}
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-end pt-2">
                            <button
                                type="submit"
                                class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition"
                            >
                                <span wire:loading.remove wire:target="generateSchedules">⚡ Buat Rangkaian Jadwal Otomatis</span>
                                <span wire:loading wire:target="generateSchedules" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    Mengenerate Jadwal...
                                </span>
                            </button>
                        </div>
                    </form>
                @else
                    <!-- Form Input Manual -->
                    <form wire:submit="addManualSchedule" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Aktivitas</label>
                                <input
                                    type="text"
                                    wire:model="activity_name"
                                    placeholder="Contoh: Penyemprotan Fungisida Nabati"
                                    class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                />
                                @error('activity_name') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Pelaksanaan</label>
                                <input
                                    type="date"
                                    wire:model="custom_date"
                                    class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                />
                                @error('custom_date') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tanaman Terkait</label>
                                <select
                                    wire:model="crop_id"
                                    class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                >
                                    <option value="">-- Umum --</option>
                                    @foreach ($crops as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button
                                type="submit"
                                class="inline-flex items-center px-5 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition"
                            >
                                + Tambah Aktivitas Manual
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        @endif

        <!-- Tab Filter Bar -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-semibold">
            <button
                wire:click="$set('activeTab', 'all')"
                class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $activeTab === 'all' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}"
            >
                <span>Semua Agenda</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'all' ? 'bg-emerald-800 text-emerald-100' : 'bg-gray-100 text-gray-700' }}">{{ $counts['all'] }}</span>
            </button>

            <button
                wire:click="$set('activeTab', 'pending')"
                class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $activeTab === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}"
            >
                <span>Belum Selesai</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'pending' ? 'bg-amber-700 text-amber-100' : 'bg-gray-100 text-gray-700' }}">{{ $counts['pending'] }}</span>
            </button>

            <button
                wire:click="$set('activeTab', 'today')"
                class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $activeTab === 'today' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}"
            >
                <span>Jadwal Hari Ini</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'today' ? 'bg-blue-700 text-blue-100' : 'bg-gray-100 text-gray-700' }}">{{ $counts['today'] }}</span>
            </button>

            <button
                wire:click="$set('activeTab', 'overdue')"
                class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $activeTab === 'overdue' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}"
            >
                <span>⚠️ Terlambat (Overdue)</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'overdue' ? 'bg-rose-700 text-rose-100' : 'bg-gray-100 text-gray-700' }}">{{ $counts['overdue'] }}</span>
            </button>

            <button
                wire:click="$set('activeTab', 'completed')"
                class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $activeTab === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}"
            >
                <span>Selesai</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'completed' ? 'bg-emerald-700 text-emerald-100' : 'bg-gray-100 text-gray-700' }}">{{ $counts['completed'] }}</span>
            </button>
        </div>

        <!-- Schedules List -->
        @if ($schedules->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
                <span class="text-4xl block mb-3">📅</span>
                <h4 class="text-base font-bold text-gray-800">Tidak ada jadwal pada kategori ini</h4>
                <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                    Gunakan tombol auto generator di atas untuk membuat jadwal budidaya tanaman Anda secara otomatis.
                </p>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-xs border border-gray-200 overflow-hidden divide-y divide-gray-100">
                @foreach ($schedules as $item)
                    @php
                        $isOverdue = $item->status === 'pending' && $item->scheduled_date->format('Y-m-d') < now()->format('Y-m-d');
                        $isToday = $item->scheduled_date->isToday();
                    @endphp
                    <div class="p-4 sm:p-5 flex items-center justify-between gap-4 transition hover:bg-gray-50 {{ $item->status === 'completed' ? 'bg-gray-50/60 opacity-75' : '' }}">
                        <div class="flex items-start gap-3.5">
                            <!-- Instant Toggle Checkbox via Livewire -->
                            <button
                                type="button"
                                wire:click="toggleStatus({{ $item->id }})"
                                class="mt-0.5 shrink-0 w-6 h-6 rounded-lg border flex items-center justify-center transition {{ $item->status === 'completed' ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-gray-300 hover:border-emerald-500 bg-white' }}"
                                title="Klik untuk ubah status tugas"
                            >
                                @if ($item->status === 'completed')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </button>

                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="text-sm font-bold text-gray-900 {{ $item->status === 'completed' ? 'line-through text-gray-400' : '' }}">
                                        {{ $item->activity_name }}
                                    </h4>

                                    @if ($isOverdue)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">
                                            ⚠️ Terlambat
                                        </span>
                                    @elseif ($isToday)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">
                                            Hari Ini
                                        </span>
                                    @endif

                                    @if ($item->crop)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-100">
                                            {{ $item->crop->name }}
                                        </span>
                                    @endif

                                    @if ($item->land)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-100">
                                            📍 {{ $item->land->name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-3">
                                    <span>🗓️ {{ $item->scheduled_date->isoFormat('dddd, D MMMM Y') }}</span>
                                    @if ($isAdmin && $item->user)
                                        <span>&bull; Petani: <strong>{{ $item->user->name }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button
                                wire:click="deleteSchedule({{ $item->id }})"
                                wire:confirm="Hapus aktivitas '{{ $item->activity_name }}' dari jadwal?"
                                class="p-2 text-gray-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition"
                                title="Hapus Agenda"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

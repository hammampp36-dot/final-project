<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="pb-2 border-b border-gray-200">
            <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                🧮 {{ __('Kalkulator Kebutuhan Tani Presisi') }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">Hitung estimasi kebutuhan bibit, formula pupuk, dan potensi tonase hasil panen secara instan.</p>
        </div>

        <!-- Form Parameter Real-Time -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 sm:p-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                        Livewire v4 Real-Time Calculator
                    </span>
                </div>
                <!-- Loading indicator -->
                <div wire:loading class="text-xs font-semibold text-emerald-600 animate-pulse flex items-center gap-1">
                    <svg class="animate-spin h-3.5 w-3.5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mengkalkulasi instan...
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pilih Komoditas Tanaman (Real-Time) -->
                <div>
                    <label for="selectedCropId" class="block text-sm font-semibold text-gray-900 mb-1">
                        Pilih Komoditas Tanaman
                    </label>
                    <select id="selectedCropId"
                            wire:model.live="selectedCropId"
                            class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm">
                        @foreach ($crops as $crop)
                            <option value="{{ $crop->id }}">
                                {{ $crop->name }} (Estimasi {{ $crop->estimated_harvest_days }} Hari) - [{{ $crop->category }}]
                            </option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-400 mt-1 block">Otomatis mengubah formula pemupukan & populasi.</span>
                </div>

                <!-- Input Luas Lahan (m2) Real-Time -->
                <div>
                    <label for="areaM2" class="block text-sm font-semibold text-gray-900 mb-1">
                        Luas Lahan (Meter Persegi / m²)
                    </label>
                    <div class="relative rounded-xl shadow-2xs">
                        <input type="number"
                               id="areaM2"
                               step="50"
                               min="10"
                               wire:model.live.debounce.300ms="areaM2"
                               class="w-full rounded-xl border-gray-300 pr-12 focus:border-emerald-500 focus:ring-emerald-500 text-sm"
                               placeholder="Contoh: 1000">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <span class="text-gray-400 text-xs font-bold">m²</span>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 mt-1 block">Ketik angka luas, kalkulasi langsung berubah secara otomatis.</span>
                </div>
            </div>

            <!-- Pintasan: Pilih dari Lahan Saya -->
            @if ($userLands->isNotEmpty())
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 mb-2">Pintasan Cepat: Isi dari Petak Lahan Saya:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($userLands as $land)
                            <button type="button"
                                    wire:click="setAreaFromLand({{ $land->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $land->name }} ({{ number_format($land->area_size, 0) }} m²)
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Hasil Kalkulasi Real-Time -->
        @if ($calculation)
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Hasil Kalkulasi Standar Agronomi</h3>
                    <span class="text-xs text-gray-500 font-medium">Berdasarkan rasio balai penelitian pertanian</span>
                </div>
                <!-- Rangkuman Lahan & Panen -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Kebutuhan Bibit</span>
                        <div class="text-xl font-black text-emerald-700 mt-1">
                            {{ $calculation['seeds'] }}
                        </div>
                        <span class="text-[11px] text-gray-500 mt-0.5 block">Kerapatan tanam ideal</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Potensi Panen</span>
                        <div class="text-xl font-black text-amber-600 mt-1">
                            {{ $calculation['harvest_yield'] }}
                        </div>
                        <span class="text-[11px] text-gray-500 mt-0.5 block">Estimasi {{ $calculation['harvest_days'] }} Hari</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Pupuk Kimia</span>
                        <div class="text-xl font-black text-blue-600 mt-1">
                            {{ $calculation['urea_kg'] + $calculation['npk_kg'] }} Kg
                        </div>
                        <span class="text-[11px] text-gray-500 mt-0.5 block">Urea + NPK Phonska</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Pupuk Kandang/Organik</span>
                        <div class="text-xl font-black text-lime-700 mt-1">
                            {{ $calculation['organik_kg'] }} Kg
                        </div>
                        <span class="text-[11px] text-gray-500 mt-0.5 block">Sebagai pupuk dasar</span>
                    </div>
                </div>

                <!-- Rincian Formula Pemupukan -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6">
                    <h3 class="font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                        <span>🧪</span> Rincian Rekomendasi Pemupukan untuk {{ number_format($calculation['area'], 0) }} m²
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100">
                            <div class="font-bold text-blue-900 text-sm">Pupuk Urea (Nitrogen)</div>
                            <div class="text-2xl font-black text-blue-700 my-1">{{ $calculation['urea_kg'] }} <span class="text-xs font-normal">Kg</span></div>
                            <p class="text-[11px] text-blue-700 leading-relaxed">
                                Diberikan terbagi pada fase vegetatif (7 & 21 HST) untuk memicu pertumbuhan tunas dan daun hijau.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-100">
                            <div class="font-bold text-amber-900 text-sm">Pupuk NPK 15-15-15</div>
                            <div class="text-2xl font-black text-amber-700 my-1">{{ $calculation['npk_kg'] }} <span class="text-xs font-normal">Kg</span></div>
                            <p class="text-[11px] text-amber-700 leading-relaxed">
                                Diberikan saat pemupukan susulan dan pembungaan untuk memperkuat perakaran dan pengisian buah.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100">
                            <div class="font-bold text-emerald-900 text-sm">Kompos / Pupuk Organik</div>
                            <div class="text-2xl font-black text-emerald-700 my-1">{{ $calculation['organik_kg'] }} <span class="text-xs font-normal">Kg</span></div>
                            <p class="text-[11px] text-emerald-700 leading-relaxed">
                                Diaduk merata pada bedengan 1-2 minggu sebelum tanam untuk memperbaiki struktur tanah dan mikroba hayati.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

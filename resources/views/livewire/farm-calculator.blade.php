<div class="space-y-8">

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

            <!-- Masukkan Luas Lahan (Real-Time) -->
            <div>
                <label for="areaM2" class="block text-sm font-semibold text-gray-900 mb-1">
                    Luas Lahan (m²)
                </label>
                <div class="relative">
                    <input type="number"
                           id="areaM2"
                           wire:model.live.debounce.250ms="areaM2"
                           min="10"
                           step="10"
                           placeholder="Contoh: 1000"
                           class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm font-bold text-gray-800 pr-16">
                    <span class="absolute right-4 top-2.5 text-xs text-gray-400 font-semibold pointer-events-none">m²</span>
                </div>
                <span class="text-xs text-gray-400 mt-1 block">Ketik angka luas lahan Anda, hasil di bawah langsung berubah seketika.</span>
            </div>

        </div>

        <!-- Tombol Cepat Lahan Terdaftar Pengguna -->
        @if ($lands->isNotEmpty())
            <div class="mt-6 pt-4 border-t border-gray-100 flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold text-gray-500">Pilih dari Lahan Anda:</span>
                @foreach ($lands as $land)
                    <button type="button"
                            wire:click="setAreaFromLand({{ $land->id }})"
                            class="px-3 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold border border-emerald-200 transition">
                        📍 {{ $land->name }} ({{ $land->area_size }})
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Hasil Kalkulasi Cerdas Real-Time -->
    @if ($calculation)
        <div class="space-y-6 transition-all duration-300">

            <!-- Banner Ringkasan Parameter -->
            <div class="p-5 rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-200">Hasil Kalkulasi Standar Agronomi</span>
                    <h3 class="text-2xl font-black mt-0.5">Komoditas: {{ $calculation['crop'] }}</h3>
                    <p class="text-sm text-emerald-100 mt-1">
                        Plot: <strong>{{ number_format($areaM2, 0, ',', '.') }} m²</strong> ({{ round($areaM2 / 10000, 3) }} Hektar)
                    </p>
                </div>
                <div class="text-left sm:text-right shrink-0">
                    <span class="text-xs text-emerald-200 block">Jarak Tanam Rekomendasi:</span>
                    <span class="font-bold text-sm bg-white/10 px-3.5 py-1.5 rounded-xl inline-block mt-1">
                        {{ $calculation['spacing'] }}
                    </span>
                </div>
            </div>

            <!-- 3 Blok Hasil: Populasi & Benih, Pemupukan, dan Proyeksi Hasil -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- 1. Kebutuhan Bibit & Populasi -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 text-lg">Populasi & Kebutuhan Benih</h4>
                        <p class="text-xs text-gray-500 mt-1">Kerapatan tanam optimal untuk mencegah kompetisi hara.</p>

                        <div class="mt-6 space-y-4">
                            <div class="p-3.5 bg-gray-50 rounded-2xl">
                                <span class="text-xs text-gray-400 block font-semibold uppercase">Estimasi Populasi</span>
                                <span class="text-xl font-black text-gray-900">{{ number_format((float) $calculation['population'], 0, ',', '.') }} Batang / Rumpun</span>
                            </div>

                            <div class="p-3.5 bg-gray-50 rounded-2xl">
                                <span class="text-xs text-gray-400 block font-semibold uppercase">Kebutuhan Bibit / Benih</span>
                                <span class="text-sm font-bold text-emerald-800">{{ $calculation['seed_amount'] }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-2xs text-gray-400 mt-4 italic">*Disarankan menyemai 10% bibit cadangan untuk penyulaman.</p>
                </div>

                <!-- 2. Dosis Pemupukan Terpadu -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 text-lg">Dosis Pupuk Rekomendasi</h4>
                        <p class="text-xs text-gray-500 mt-1">Kombinasi nutrisi organik dasar dan kimia susulan.</p>

                        <div class="mt-4 space-y-2.5 text-xs text-gray-700">
                            <div class="p-2.5 bg-gray-50 rounded-xl flex items-center justify-between">
                                <span class="font-medium text-gray-600">Pupuk Kompos / Kandang:</span>
                                <strong class="text-gray-900">{{ $calculation['manure'] }}</strong>
                            </div>
                            <div class="p-2.5 bg-gray-50 rounded-xl flex items-center justify-between">
                                <span class="font-medium text-gray-600">Pupuk NPK 16-16-16:</span>
                                <strong class="text-emerald-700">{{ $calculation['npk'] }}</strong>
                            </div>
                            <div class="p-2.5 bg-gray-50 rounded-xl flex items-center justify-between">
                                <span class="font-medium text-gray-600">Pupuk Urea:</span>
                                <strong class="text-gray-900">{{ $calculation['urea'] }}</strong>
                            </div>
                            <div class="p-2.5 bg-gray-50 rounded-xl flex items-center justify-between">
                                <span class="font-medium text-gray-600">Pupuk SP-36 / KCl:</span>
                                <strong class="text-gray-900">{{ $calculation['kcl_sp36'] }}</strong>
                            </div>
                        </div>
                    </div>

                    <p class="text-2xs text-gray-400 mt-4 italic">*Berikan pupuk kandang & SP-36 sebagai pupuk dasar sebelum tanam.</p>
                </div>

                <!-- 3. Potensi Panen & Nilai Ekonomi -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 text-lg">Proyeksi Hasil & Omset</h4>
                        <p class="text-xs text-gray-500 mt-1">Estimasi panen raya dengan Good Agricultural Practices (GAP).</p>

                        <div class="mt-6 space-y-4">
                            <div class="p-3.5 bg-emerald-50 rounded-2xl border border-emerald-100">
                                <span class="text-xs text-emerald-800 block font-semibold uppercase">Potensi Hasil Panen</span>
                                <span class="text-xl font-black text-emerald-900">{{ $calculation['estimated_yield'] }}</span>
                            </div>

                            <div class="p-3.5 bg-teal-50 rounded-2xl border border-teal-100">
                                <span class="text-xs text-teal-800 block font-semibold uppercase">Estimasi Omset Kotor</span>
                                <span class="text-xl font-black text-teal-900">
                                    Rp {{ number_format($calculation['estimated_revenue'], 0, ',', '.') }}
                                </span>
                                <span class="text-2xs text-gray-500 block mt-0.5">Asumsi harga: Rp {{ number_format($calculation['price_per_kg'], 0, ',', '.') }}/kg</span>
                            </div>
                        </div>
                    </div>

                    @if ($selectedCrop)
                        <a href="{{ route('schedules.create', ['crop_id' => $selectedCrop->id]) }}" class="mt-4 w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-xs text-center transition">
                            Buat Jadwal Tanam Komoditas Ini &rarr;
                        </a>
                    @endif
                </div>

            </div>

        </div>
    @endif

</div>

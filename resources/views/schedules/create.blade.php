<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('schedules.index') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                    Generate Smart Jadwal & Rekomendasi Tanam
                </h2>
                <p class="text-sm text-gray-600 mt-0.5">Pilih komoditas dari katalog tanaman dan tentukan tanggal tanam untuk mendapatkan timeline perawatan otomatis.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
                <div class="p-6 sm:p-8">

                    <!-- How it works banner -->
                    <div class="mb-8 p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 flex items-start gap-4">
                        <div class="p-3 bg-emerald-600 text-white rounded-xl shrink-0 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-emerald-950 text-base">Rekomendasi Cerdas Otomatis</h4>
                            <p class="text-sm text-emerald-800 mt-1 leading-relaxed">
                                Sistem membaca estimasi masa panen tanaman terpilih, lalu secara otomatis merangkai agenda penyiraman harian, pemupukan susulan berkala, penyiangan, hingga estimasi tanggal panen raya.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('schedules.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Pilih Tanaman dari Katalog -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                1. Pilih Komoditas Tanaman dari Katalog <span class="text-rose-500">*</span>
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($crops as $crop)
                                    <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition hover:border-emerald-500 has-checked:border-emerald-600 has-checked:bg-emerald-50/40">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <span class="text-xs font-semibold uppercase text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">
                                                    {{ $crop->category }}
                                                </span>
                                                <h4 class="font-bold text-gray-900 text-base mt-2">{{ $crop->name }}</h4>
                                            </div>
                                            <input type="radio"
                                                   name="crop_id"
                                                   value="{{ $crop->id }}"
                                                   {{ old('crop_id', $crops->first()->id) == $crop->id ? 'checked' : '' }}
                                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                        </div>

                                        <div class="mt-3 flex items-center text-xs font-semibold text-gray-700">
                                            <svg class="h-4 w-4 text-amber-500 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Estimasi Panen: ~{{ $crop->estimated_harvest_days }} Hari
                                        </div>

                                        @if ($crop->planting_guide)
                                            <p class="mt-2 text-xs text-gray-500 line-clamp-2">
                                                {{ $crop->planting_guide }}
                                            </p>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                            @error('crop_id')
                                <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pilih Lahan (Opsional) -->
                        <div>
                            <label for="land_id" class="block text-sm font-semibold text-gray-900 mb-1">
                                2. Alokasikan ke Lahan (Opsional)
                            </label>
                            <select id="land_id"
                                    name="land_id"
                                    class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm">
                                <option value="">-- Tanpa Spesifikasi Lahan Tertentu --</option>
                                @foreach ($lands as $land)
                                    <option value="{{ $land->id }}" {{ old('land_id') == $land->id ? 'selected' : '' }}>
                                        {{ $land->name }} (Luas: {{ $land->area_size }} - Lokasi: {{ $land->location }})
                                    </option>
                                @endforeach
                            </select>
                            @error('land_id')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                            @if ($lands->isEmpty())
                                <p class="mt-1 text-xs text-amber-600">
                                    💡 Anda belum memiliki lahan terdaftar. Anda tetap bisa membuat jadwal, atau <a href="{{ route('lands.create') }}" class="underline font-semibold">daftarkan lahan terlebih dahulu</a>.
                                </p>
                            @endif
                        </div>

                        <!-- Tanggal Mulai Tanam -->
                        <div>
                            <label for="planting_date" class="block text-sm font-semibold text-gray-900 mb-1">
                                3. Tanggal Mulai Tanam <span class="text-rose-500">*</span>
                            </label>
                            <input type="date"
                                   id="planting_date"
                                   name="planting_date"
                                   value="{{ old('planting_date', date('Y-m-d')) }}"
                                   class="w-full sm:w-72 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm @error('planting_date') border-rose-500 @enderror"
                                   required>
                            @error('planting_date')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Jadwal penyiraman, pemupukan bertahap, dan panen akan dihitung otomatis mulai dari tanggal ini.</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold transition">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-xs transition flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Generate Jadwal Otomatis
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


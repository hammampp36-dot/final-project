<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('lands.index') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                    Tambah Lahan Pertanian Baru
                </h2>
                <p class="text-sm text-gray-600 mt-0.5">Sistem akan otomatis menghasilkan QR Code digital yang dapat di-scan setelah disimpan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
                <div class="p-6 sm:p-8">

                    <!-- Info Box -->
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-start gap-3">
                        <div class="p-2 bg-emerald-100 text-emerald-700 rounded-lg shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-sm text-emerald-900 leading-relaxed">
                            <strong>Otomatisasi QR Code:</strong> Setiap lahan yang Anda daftarkan akan mendapatkan kode QR unik untuk memudahkan identifikasi plot di lapangan dan pelacakan jadwal pemeliharaan.
                        </div>
                    </div>

                    <form action="{{ route('lands.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Nama Lahan -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">
                                Nama Lahan / Blok Kebun <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Lahan Blok A - Cabai Merah"
                                   class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm @error('name') border-rose-500 @enderror"
                                   required autofocus>
                            @error('name')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Luas Lahan -->
                        <div>
                            <label for="area_size" class="block text-sm font-semibold text-gray-800 mb-1">
                                Luas Lahan (m² / Hektar) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   id="area_size"
                                   name="area_size"
                                   value="{{ old('area_size') }}"
                                   placeholder="Contoh: 1.500 m² atau 0.5 Hektar"
                                   class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm @error('area_size') border-rose-500 @enderror"
                                   required>
                            @error('area_size')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Tuliskan besaran luas beserta satuannya.</p>
                        </div>

                        <!-- Lokasi Lahan -->
                        <div>
                            <label for="location" class="block text-sm font-semibold text-gray-800 mb-1">
                                Lokasi / Alamat Lahan <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="location"
                                      name="location"
                                      rows="3"
                                      placeholder="Contoh: Desa Sukamaju RT 03/RW 02, Lembang, Kab. Bandung Barat"
                                      class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm @error('location') border-rose-500 @enderror"
                                      required>{{ old('location') }}</textarea>
                            @error('location')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('lands.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold transition">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-xs transition">
                                Simpan & Generate QR Code
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


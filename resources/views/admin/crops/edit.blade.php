<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.crops.index') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                    Edit Master Komoditas: {{ $crop->name }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi estimasi panen atau SOP budidaya.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 sm:p-8">
                <form action="{{ route('admin.crops.update', $crop) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">
                            Nama Komoditas Tanaman <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $crop->name) }}"
                               class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('name') border-rose-500 @enderror"
                               required autofocus>
                        @error('name')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-800 mb-1">
                            Kategori Komoditas <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="category"
                               name="category"
                               value="{{ old('category', $crop->category) }}"
                               class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('category') border-rose-500 @enderror"
                               required>
                        @error('category')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estimated_harvest_days" class="block text-sm font-semibold text-gray-800 mb-1">
                            Estimasi Waktu Panen (Hari) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number"
                               id="estimated_harvest_days"
                               name="estimated_harvest_days"
                               min="1"
                               value="{{ old('estimated_harvest_days', $crop->estimated_harvest_days) }}"
                               class="w-full sm:w-48 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('estimated_harvest_days') border-rose-500 @enderror"
                               required>
                        @error('estimated_harvest_days')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="planting_guide" class="block text-sm font-semibold text-gray-800 mb-1">
                            SOP Panduan Budidaya & Pemeliharaan
                        </label>
                        <textarea id="planting_guide"
                                  name="planting_guide"
                                  rows="4"
                                  class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('planting_guide', $crop->planting_guide) }}</textarea>
                        @error('planting_guide')
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.crops.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-xs transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


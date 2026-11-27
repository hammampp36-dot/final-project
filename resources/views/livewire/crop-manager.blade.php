<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="pb-2 border-b border-gray-200">
            <h2 class="font-bold text-2xl {{ $isAdmin ? 'text-indigo-950' : 'text-emerald-900' }} leading-tight">
                {{ $isAdmin ? '🌾 Master Katalog Komoditas Tanaman' : '🌱 Katalog Tanaman & Panduan Budidaya' }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                {{ $isAdmin ? 'Kelola master data komoditas pertanian, durasi masa panen, dan panduan budidaya edukatif.' : 'Eksplorasi komoditas unggul, estimasi masa panen, serta panduan praktis penanaman dan pemupukan.' }}
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

        <!-- Controls & Search Bar -->
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari tanaman berdasarkan nama atau panduan..."
                    class="w-full rounded-xl border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500 shadow-xs"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Category Dropdown Filter -->
            <div class="flex items-center gap-3">
                <select
                    wire:model.live="selectedCategory"
                    class="rounded-xl border-gray-300 py-2 text-xs font-semibold focus:border-emerald-500 focus:ring-emerald-500 shadow-xs"
                >
                    <option value="all">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                @if ($isAdmin)
                    <button
                        wire:click="toggleForm"
                        class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition"
                    >
                        <svg class="w-3.5 h-3.5 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ $showForm ? 'Tutup Formulir' : '+ Tambah Komoditas' }}
                    </button>
                @endif
            </div>
        </div>

        <!-- Admin Form (Add / Edit Crop) -->
        @if ($isAdmin && $showForm)
            <div class="bg-gradient-to-br from-indigo-50/60 to-white border border-indigo-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span>🌾</span> {{ $editingCropId ? 'Edit Komoditas Tanaman' : 'Tambah Master Tanaman Baru' }}
                </h3>
                <p class="text-xs text-gray-500 mb-5">
                    Data komoditas ini akan langsung tampil di kalkulator tani dan rekomendasi jadwal otomatis seluruh petani.
                </p>

                <form wire:submit="saveCrop" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Tanaman</label>
                            <input
                                type="text"
                                wire:model="name"
                                placeholder="Contoh: Cabai Rawit Merah"
                                class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            @error('name') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kategori</label>
                            <input
                                type="text"
                                wire:model="category"
                                placeholder="Contoh: Sayuran / Palawija / Pangan"
                                class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            @error('category') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Estimasi Hari Panen (Hari)</label>
                            <input
                                type="number"
                                wire:model="estimated_harvest_days"
                                placeholder="Contoh: 85"
                                class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            @error('estimated_harvest_days') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Panduan Budidaya Lengkap</label>
                        <textarea
                            wire:model="planting_guide"
                            rows="4"
                            placeholder="Tuliskan petunjuk penyemaian, pemupukan dasar, kebutuhan air, dan pengendalian hama..."
                            class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        ></textarea>
                        @error('planting_guide') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button
                            type="button"
                            wire:click="toggleForm"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-white border border-gray-300 rounded-xl"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="inline-flex items-center px-6 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition"
                        >
                            {{ $editingCropId ? 'Perbarui Data Komoditas' : 'Simpan ke Master Katalog' }}
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Crops Grid -->
        @if ($crops->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
                <span class="text-4xl block mb-3">🌱</span>
                <h4 class="text-base font-bold text-gray-800">Tidak ada komoditas ditemukan</h4>
                <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                    {{ $search ? 'Tidak ada tanaman yang cocok dengan pencarian "' . $search . '".' : 'Belum ada data tanaman yang terdaftar.' }}
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($crops as $crop)
                    <div class="bg-white rounded-2xl shadow-xs border border-gray-200 overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ $crop->category }}
                                </span>
                                <span class="inline-flex items-center text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
                                    ⏳ {{ $crop->estimated_harvest_days }} Hari
                                </span>
                            </div>

                            <h4 class="text-lg font-bold text-gray-900 mt-2">{{ $crop->name }}</h4>
                            <p class="text-xs text-gray-600 mt-2 line-clamp-3 leading-relaxed">
                                {{ $crop->planting_guide }}
                            </p>
                        </div>

                        <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs">
                            <button
                                wire:click="viewGuide({{ $crop->id }})"
                                class="text-emerald-700 hover:text-emerald-800 font-bold flex items-center gap-1"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Baca Panduan Lengkap
                            </button>

                            @if ($isAdmin)
                                <div class="flex items-center gap-3">
                                    <button
                                        wire:click="editCrop({{ $crop->id }})"
                                        class="text-indigo-600 hover:text-indigo-800 font-semibold"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        wire:click="deleteCrop({{ $crop->id }})"
                                        wire:confirm="Yakin ingin menghapus komoditas '{{ $crop->name }}' dari katalog?"
                                        class="text-rose-600 hover:text-rose-800 font-semibold"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Panduan Modal -->
        @if ($viewingCrop)
            <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-xs flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">{{ $viewingCrop->category }}</span>
                            <h3 class="text-xl font-bold text-gray-900 mt-1">{{ $viewingCrop->name }}</h3>
                        </div>
                        <button wire:click="closeGuide" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
                    </div>

                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold mb-4 flex items-center gap-2">
                        <span>⏱️</span>
                        <span>Estimasi Masa Panen Raya: <strong>{{ $viewingCrop->estimated_harvest_days }} Hari</strong> sejak tanam</span>
                    </div>

                    <div class="space-y-2 mb-6">
                        <h5 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Panduan & Petunjuk Budidaya:</h5>
                        <div class="bg-gray-50 rounded-xl p-4 text-xs text-gray-700 leading-relaxed max-h-60 overflow-y-auto whitespace-pre-line border border-gray-100">
                            {{ $viewingCrop->planting_guide }}
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button
                            wire:click="closeGuide"
                            class="px-5 py-2 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition"
                        >
                            Tutup Panduan
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

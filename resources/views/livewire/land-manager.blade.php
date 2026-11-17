<div class="space-y-6">
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

    <!-- Action Bar & Controls -->
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex-1 relative">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari lahan berdasarkan nama, lokasi, atau kode QR..."
                class="w-full rounded-xl border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500 shadow-xs"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if (!$isAdmin)
                <button
                    wire:click="toggleCreateForm"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition"
                >
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ $showCreateForm ? 'Tutup Formulir' : '+ Tambah Petak Lahan' }}
                </button>
            @endif
        </div>
    </div>

    <!-- Livewire Form: Tambah Petak Lahan -->
    @if ($showCreateForm && !$isAdmin)
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-emerald-900 mb-1 flex items-center gap-2">
                <span>🌱</span> Pendaftaran Petak Lahan & Generate QR Otomatis
            </h3>
            <p class="text-xs text-emerald-700 mb-5">
                Isi rincian lahan di bawah. Kode QR digital unik akan langsung digenerate secara otomatis oleh sistem saat data disimpan.
            </p>

            <form wire:submit="saveLand" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Nama Lahan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Petak Lahan</label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="Contoh: Petak Barat Blok A"
                            class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                        />
                        @error('name') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Luas Lahan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Luas Lahan (m²)</label>
                        <input
                            type="number"
                            step="0.01"
                            wire:model="area_size"
                            placeholder="Contoh: 1500"
                            class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                        />
                        @error('area_size') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lokasi Lahan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Lokasi / Desa / Koordinat</label>
                        <input
                            type="text"
                            wire:model="location"
                            placeholder="Contoh: Desa Sukamaju, Sektor 3"
                            class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                        />
                        @error('location') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button
                        type="button"
                        wire:click="toggleCreateForm"
                        class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-white border border-gray-300 rounded-xl"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center px-5 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition"
                    >
                        <span wire:loading.remove wire:target="saveLand">Simpan & Terbitkan QR Code</span>
                        <span wire:loading wire:target="saveLand" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Lands Grid -->
    @if ($lands->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
            <span class="text-4xl block mb-3">🌾</span>
            <h4 class="text-base font-bold text-gray-800">Belum ada data petak lahan</h4>
            <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                {{ $search ? 'Tidak ditemukan lahan yang cocok dengan kata kunci "' . $search . '".' : 'Daftarkan petak lahan pertanian Anda untuk mendapatkan kode identitas QR unik dan mengaktifkan jadwal tanam cerdas.' }}
            </p>
            @if (!$isAdmin && !$showCreateForm)
                <button
                    wire:click="toggleCreateForm"
                    class="mt-4 inline-flex items-center px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition"
                >
                    + Daftarkan Lahan Pertama
                </button>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($lands as $land)
                <div class="bg-white rounded-2xl shadow-xs border border-gray-200 overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ number_format($land->area_size, 0, ',', '.') }} m²
                                </span>
                                <h4 class="text-lg font-bold text-gray-900 mt-1">{{ $land->name }}</h4>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-md bg-gray-100 text-gray-600 font-mono">
                                #{{ $land->id }}
                            </span>
                        </div>

                        <p class="text-xs text-gray-500 flex items-center gap-1.5 mb-4">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $land->location }}
                        </p>

                        @if ($isAdmin && $land->user)
                            <div class="mb-4 p-2.5 rounded-xl bg-gray-50 border border-gray-100 text-xs text-gray-600">
                                Pemilik: <strong class="text-gray-900">{{ $land->user->name }}</strong> ({{ $land->user->email }})
                            </div>
                        @endif

                        <!-- QR Thumbnail Box -->
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between gap-3">
                            <div class="text-left">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Identitas QR</div>
                                <div class="font-mono text-xs font-bold text-emerald-700 truncate max-w-[140px]">
                                    {{ $land->qr_code_text }}
                                </div>
                            </div>
                            <button
                                wire:click="showQr({{ $land->id }})"
                                class="p-2 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg shadow-2xs transition text-xs font-semibold flex items-center gap-1"
                                title="Lihat & Scan QR Code"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                Buka QR
                            </button>
                        </div>
                    </div>

                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs">
                        <a
                            href="{{ route('lands.qr-download', $land) }}"
                            class="text-emerald-700 hover:text-emerald-800 font-semibold flex items-center gap-1"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh SVG
                        </a>

                        <button
                            wire:click="deleteLand({{ $land->id }})"
                            wire:confirm="Yakin ingin menghapus petak lahan '{{ $land->name }}'? Seluruh riwayat jadwal yang tertaut akan ikut terhapus."
                            class="text-rose-600 hover:text-rose-800 font-medium"
                        >
                            Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- QR Code Preview Modal -->
    @if ($selectedLand)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-xl border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Kartu Lahan Digital</span>
                    <button wire:click="closeQr" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
                </div>

                <div class="p-4 bg-white rounded-xl inline-block shadow-inner border border-gray-200 mb-4">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate($selectedLand->qr_code_text) !!}
                </div>

                <h4 class="text-base font-bold text-gray-900">{{ $selectedLand->name }}</h4>
                <p class="font-mono text-xs text-gray-500 mt-1 mb-2">{{ $selectedLand->qr_code_text }}</p>
                <p class="text-xs text-gray-600 mb-5">
                    📍 {{ $selectedLand->location }} &bull; 📐 {{ number_format($selectedLand->area_size, 0, ',', '.') }} m²
                </p>

                <div class="flex gap-2">
                    <a
                        href="{{ route('lands.qr-download', $selectedLand) }}"
                        class="flex-1 py-2 px-3 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition shadow-xs text-center"
                    >
                        Unduh File QR SVG
                    </a>
                    <button
                        wire:click="closeQr"
                        class="py-2 px-4 text-xs font-semibold text-gray-600 hover:text-gray-800 bg-gray-100 rounded-xl"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>


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
                    🔍 Scan / Cari Lahan via QR Code
                </h2>
                <p class="text-sm text-gray-600 mt-0.5">Identifikasi plot lahan pertanian dengan memindai QR code fisik atau memasukkan kode lahan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Scanner Box / Search Box -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 sm:p-8">
                <form action="{{ route('lands.scan') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="code" class="block text-sm font-semibold text-gray-800 mb-1">
                            Kode QR Lahan atau ID
                        </label>
                        <div class="flex gap-2">
                            <input type="text"
                                   id="code"
                                   name="code"
                                   value="{{ $searchCode }}"
                                   placeholder="Contoh: AGRI-LAND-1 atau nama lahan"
                                   class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm"
                                   required autofocus>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl transition shrink-0 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari Lahan
                            </button>
                        </div>
                    </div>
                </form>

                @if ($searchCode)
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        @if ($landFound)
                            <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-200 text-emerald-900 mb-2">
                                            Lahan Ditemukan!
                                        </span>
                                        <h3 class="text-xl font-bold text-gray-900">{{ $landFound->name }}</h3>
                                        <div class="text-sm text-gray-600 mt-1">
                                            Luas: <strong>{{ $landFound->area_size }}</strong> &bull; Lokasi: {{ $landFound->location }}
                                        </div>
                                    </div>
                                    <div class="p-1 bg-white rounded-lg border border-gray-200 shrink-0">
                                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate($landFound->qr_code_text) !!}
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-emerald-200 flex items-center gap-3">
                                    <a href="{{ route('lands.show', $landFound) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl transition">
                                        Buka Detail Lengkap &rarr;
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-6 rounded-2xl bg-rose-50 border border-rose-200 text-center">
                                <p class="text-sm font-semibold text-rose-800">Lahan dengan kode "{{ $searchCode }}" tidak ditemukan pada akun Anda.</p>
                                <p class="text-xs text-rose-600 mt-1">Pastikan kode yang dimasukkan sesuai dengan QR Code lahan yang terdaftar.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Panduan Penggunaan QR di Lahan -->
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100 p-6 flex items-start gap-4">
                <div class="p-3 bg-emerald-600 text-white rounded-xl shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
                <div class="text-sm text-emerald-950">
                    <h4 class="font-bold text-base mb-1">Tips Pemasangan QR Code di Kebun</h4>
                    <p class="leading-relaxed text-emerald-800">
                        Cetak QR Code menggunakan kertas laminasi atau plat akrilik tahan cuaca, pasang pada tiang penanda di ujung bedengan/blok lahan Anda. Scanner kamera ponsel apapun akan membaca data lahan dan mengarahkan langsung ke dashboard Agrismart.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


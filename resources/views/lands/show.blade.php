<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('lands.index') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                        Detail Lahan: {{ $land->name }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-0.5">Identifikasi Digital & Kartu QR Code Lahan</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('lands.qr-download', $land) }}" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-xl transition inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download SVG
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-semibold rounded-xl transition inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak / Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Success Alert -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-emerald-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kartu QR Code Cetak -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 flex flex-col items-center text-center">
                    <div class="w-full bg-emerald-700 text-white p-3 rounded-xl mb-4 font-semibold text-sm tracking-wide uppercase">
                        Kartu Identitas Lahan
                    </div>

                    <div class="p-4 bg-white border-2 border-emerald-500 rounded-2xl shadow-md my-2">
                        {!! $qrCodeSvg !!}
                    </div>

                    <p class="text-xs text-gray-500 mt-3">Scan QR Code ini menggunakan kamera ponsel atau scanner untuk membaca metadata lahan pertanian.</p>

                    <div class="w-full mt-6 pt-4 border-t border-gray-100 text-left">
                        <span class="text-xs font-semibold uppercase text-gray-400 block mb-1">Payload Isi QR Code:</span>
                        <pre class="bg-gray-50 p-2.5 rounded-lg text-xs text-gray-700 font-mono break-all whitespace-pre-wrap border border-gray-100">{{ $land->qr_code_text }}</pre>
                    </div>
                </div>

                <!-- Informasi Lahan -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            Informasi Spesifikasi Lahan
                        </h3>

                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-xs font-semibold uppercase text-gray-400">Nama Lahan</dt>
                                <dd class="text-lg font-bold text-gray-900 mt-1">{{ $land->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase text-gray-400">Luas Lahan</dt>
                                <dd class="text-lg font-bold text-emerald-700 mt-1">{{ $land->area_size }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-semibold uppercase text-gray-400">Lokasi / Alamat</dt>
                                <dd class="text-base font-medium text-gray-800 mt-1">{{ $land->location }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase text-gray-400">Pemilik Lahan</dt>
                                <dd class="text-base font-medium text-gray-800 mt-1">{{ $land->user->name }} ({{ $land->user->email }})</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase text-gray-400">Terdaftar Sejak</dt>
                                <dd class="text-base font-medium text-gray-800 mt-1">{{ $land->created_at->translatedFormat('d F Y, H:i') }} WIB</dd>
                            </div>
                        </dl>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap gap-3">
                            <a href="{{ route('schedules.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl transition inline-flex items-center">
                                + Buat Jadwal Tanam di Lahan Ini
                            </a>
                            <form action="{{ route('lands.destroy', $land) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lahan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 font-medium text-sm rounded-xl transition">
                                    Hapus Lahan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Jadwal Terkait Lahan Ini -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">Agenda Tanam di Lahan Ini ({{ $land->schedules->count() }})</h4>

                        @if ($land->schedules->isEmpty())
                            <p class="text-sm text-gray-500 py-4 text-center">Belum ada agenda jadwal tanam yang dialokasikan khusus untuk lahan ini.</p>
                        @else
                            <div class="divide-y divide-gray-100">
                                @foreach ($land->schedules as $sched)
                                    <div class="py-3 flex items-center justify-between">
                                        <div>
                                            <div class="font-semibold text-gray-900 text-sm">{{ $sched->activity_name }}</div>
                                            <div class="text-xs text-gray-500">
                                                Komoditas: {{ $sched->crop->name }} &bull; Tanggal: {{ $sched->scheduled_date->translatedFormat('d M Y') }}
                                            </div>
                                        </div>
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $sched->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $sched->status === 'completed' ? 'Selesai' : 'Pending' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>


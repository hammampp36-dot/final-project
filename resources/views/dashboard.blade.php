<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-emerald-950 leading-tight">
                    🌾 Beranda Agrismart
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Selamat datang kembali, <strong class="text-emerald-800">{{ Auth::user()->name }}</strong>! Kelola lahan, otomatisasi jadwal, dan pantau keuangan pertanian Anda.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('schedules.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-xs transition inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Jadwal Cerdas Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Flash Alert -->
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

            <!-- 4 Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Total Lahan -->
                <a href="{{ route('lands.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:border-emerald-300 transition flex items-center justify-between group">
                    <div>
                        <span class="text-xs font-semibold uppercase text-gray-400">Lahan Terdaftar</span>
                        <div class="text-2xl font-black text-gray-900 mt-1">{{ $totalLands }} Plot</div>
                        <span class="text-xs text-emerald-600 font-medium group-hover:underline inline-flex items-center mt-1">
                            Kelola QR Lahan &rarr;
                        </span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </a>

                <!-- Jadwal Aktif -->
                <a href="{{ route('schedules.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:border-amber-300 transition flex items-center justify-between group">
                    <div>
                        <span class="text-xs font-semibold uppercase text-gray-400">Jadwal Menunggu</span>
                        <div class="text-2xl font-black text-amber-600 mt-1">{{ $pendingSchedulesCount }} Agenda</div>
                        <span class="text-xs text-amber-600 font-medium group-hover:underline inline-flex items-center mt-1">
                            Buka Timeline &rarr;
                        </span>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </a>

                <!-- Total Pemasukan -->
                <a href="{{ route('transactions.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:border-emerald-300 transition flex items-center justify-between group">
                    <div>
                        <span class="text-xs font-semibold uppercase text-gray-400">Total Pemasukan</span>
                        <div class="text-xl font-black text-emerald-700 mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                        <span class="text-xs text-emerald-600 font-medium group-hover:underline inline-flex items-center mt-1">
                            Lihat Rincian &rarr;
                        </span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                    </div>
                </a>

                <!-- Saldo Bersih / Profit -->
                <a href="{{ route('transactions.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:border-teal-300 transition flex items-center justify-between group">
                    <div>
                        <span class="text-xs font-semibold uppercase text-gray-400">Saldo Kas Bersih</span>
                        <div class="text-xl font-black {{ $netProfit >= 0 ? 'text-teal-700' : 'text-rose-600' }} mt-1">
                            Rp {{ number_format($netProfit, 0, ',', '.') }}
                        </div>
                        <span class="text-xs text-teal-600 font-medium group-hover:underline inline-flex items-center mt-1">
                            Laporan Kas &rarr;
                        </span>
                    </div>
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </a>

            </div>

            <!-- Modul Cepat (3 Card Navigasi Alur Utama) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <!-- 1. Form Input Lahan & QR -->
                <div class="bg-gradient-to-br from-white to-emerald-50/50 p-6 rounded-2xl border border-emerald-100 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold mb-4 shadow-xs">
                            1
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">Pendaftaran Lahan & QR</h3>
                        <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                            Input nama, luas, dan koordinat lahan Anda. Sistem langsung menghasilkan QR Code digital siap cetak untuk monitoring lapangan.
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-2">
                        <a href="{{ route('lands.create') }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                            + Tambah Lahan
                        </a>
                        <a href="{{ route('lands.index') }}" class="px-3.5 py-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                            Lihat Lahan
                        </a>
                    </div>
                </div>

                <!-- 2. Katalog & Jadwal Smart -->
                <div class="bg-gradient-to-br from-white to-blue-50/50 p-6 rounded-2xl border border-blue-100 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold mb-4 shadow-xs">
                            2
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">Katalog & Rekomendasi Jadwal</h3>
                        <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                            Pilih komoditas (Cabai, Tomat, Jagung, dll). Sistem otomatis menyusun timeline penyiraman, pemupukan berkala, dan panen raya.
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-2">
                        <a href="{{ route('schedules.create') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                            + Generate Jadwal
                        </a>
                        <a href="{{ route('schedules.index') }}" class="px-3.5 py-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                            Timeline
                        </a>
                    </div>
                </div>

                <!-- 3. Pencatatan Keuangan -->
                <div class="bg-gradient-to-br from-white to-teal-50/50 p-6 rounded-2xl border border-teal-100 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold mb-4 shadow-xs">
                            3
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">Arus Kas & Net Profit</h3>
                        <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                            Catat pembelian bibit, pupuk, pestisida, upah buruh, serta hasil penjualan panen dengan kalkulasi otomatis saldo laba bersih.
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-2">
                        <a href="{{ route('transactions.create') }}" class="px-3.5 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                            + Catat Kas
                        </a>
                        <a href="{{ route('transactions.index') }}" class="px-3.5 py-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                            Laporan Kas
                        </a>
                    </div>
                </div>

            </div>

            <!-- 2-Kolom Detail (Agenda Terdekat & Ringkasan Lahan/Transaksi) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Agenda Terdekat (2 Kolom) -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-xs p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            Agenda Pertanian Terdekat
                        </h3>
                        <a href="{{ route('schedules.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                            Lihat Semua Agenda &rarr;
                        </a>
                    </div>

                    @if ($upcomingSchedules->isEmpty())
                        <div class="py-8 text-center text-gray-500">
                            <p class="text-sm">Tidak ada agenda perawatan yang menunggu saat ini.</p>
                            <a href="{{ route('schedules.create') }}" class="mt-3 inline-flex items-center text-xs font-semibold text-emerald-600 hover:underline">
                                + Buat Jadwal Tanam Baru
                            </a>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($upcomingSchedules as $schedule)
                                <div class="py-3.5 flex items-center justify-between hover:bg-gray-50/60 rounded-xl px-2 transition">
                                    <div class="flex items-center gap-3">
                                        <form action="{{ route('schedules.toggle', $schedule) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-5 h-5 rounded-md border border-gray-300 hover:border-emerald-500 flex items-center justify-center transition" title="Tandai Selesai"></button>
                                        </form>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm">{{ $schedule->activity_name }}</h4>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                Komoditas: <strong class="text-emerald-700">{{ $schedule->crop->name }}</strong>
                                                @if ($schedule->land)
                                                    &bull; Lahan: {{ $schedule->land->name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-semibold text-gray-600 bg-gray-100 px-2.5 py-1 rounded-lg shrink-0">
                                        {{ $schedule->scheduled_date->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Lahan & Transaksi Terbaru (1 Kolom) -->
                <div class="space-y-6">

                    <!-- Lahan Terdaftar Preview -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-gray-900 text-sm uppercase tracking-wider">Lahan Anda</h4>
                            <a href="{{ route('lands.index') }}" class="text-xs text-emerald-600 font-semibold hover:underline">Kelola</a>
                        </div>

                        @if ($lands->isEmpty())
                            <p class="text-xs text-gray-400 py-3">Belum ada lahan terdaftar.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($lands as $land)
                                    <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between">
                                        <div>
                                            <div class="font-bold text-gray-800 text-xs">{{ $land->name }}</div>
                                            <div class="text-2xs text-gray-500 mt-0.5">{{ $land->area_size }} &bull; {{ Str::limit($land->location, 20) }}</div>
                                        </div>
                                        <div class="p-1 bg-white border border-gray-200 rounded shrink-0">
                                            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(36)->generate($land->qr_code_text) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Transaksi Terbaru Preview -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-gray-900 text-sm uppercase tracking-wider">Transaksi Terakhir</h4>
                            <a href="{{ route('transactions.index') }}" class="text-xs text-emerald-600 font-semibold hover:underline">Semua</a>
                        </div>

                        @if ($recentTransactions->isEmpty())
                            <p class="text-xs text-gray-400 py-3">Belum ada transaksi kas.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($recentTransactions as $trx)
                                    <div class="flex items-center justify-between text-xs py-1.5 border-b border-gray-50 last:border-none">
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $trx->category }}</div>
                                            <div class="text-2xs text-gray-400">{{ $trx->transaction_date->translatedFormat('d M') }}</div>
                                        </div>
                                        <div class="font-bold {{ $trx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                        </div>
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

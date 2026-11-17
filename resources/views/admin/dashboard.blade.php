<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                        🛡️ Panel Pusat Kontrol
                    </span>
                    <span class="text-xs text-gray-400">&bull; Administrator Eksekutif</span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                    Dashboard Eksekutif Pertanian Agrismart
                </h2>
                <p class="text-sm text-gray-600 mt-0.5">
                    Monitoring terpadu multi-petani, petak lahan, master komoditas, dan arus kas global.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.crops.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    + Tambah Master Tanaman
                </a>
                <a href="{{ route('admin.transactions.export') }}" class="px-4 py-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-bold rounded-xl shadow-2xs transition inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Ekspor Kas Global
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Flash Alert -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-900 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-indigo-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- 4 Kartu Metrik Global -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Total Petani -->
                <a href="{{ route('admin.users.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:border-indigo-300 transition flex items-center justify-between group">
                    <div>
                        <span class="text-xs font-semibold uppercase text-gray-400">Total Petani Mitra</span>
                        <div class="text-2xl font-black text-gray-900 mt-1">{{ $totalFarmers }} Petani</div>
                        <span class="text-xs text-indigo-600 font-medium group-hover:underline inline-flex items-center mt-1">
                            Kelola Pengguna &rarr;
                        </span>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </a>

                <!-- Total Lahan Nasional -->
                <a href="{{ route('admin.lands.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:border-emerald-300 transition flex items-center justify-between group">
                    <div>
                        <span class="text-xs font-semibold uppercase text-gray-400">Total Petak Lahan</span>
                        <div class="text-2xl font-black text-emerald-800 mt-1">{{ $totalLands }} Plot</div>
                        <span class="text-xs text-emerald-600 font-medium group-hover:underline inline-flex items-center mt-1">
                            Supervisi Lahan &rarr;
                        </span>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </a>

                <!-- Saldo Kas Global -->
                <a href="{{ route('admin.transactions.index') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:border-teal-300 transition flex items-center justify-between group">
                    <div>
                        <span class="text-xs font-semibold uppercase text-gray-400">Kas Bersih Global</span>
                        <div class="text-xl font-black {{ $netProfit >= 0 ? 'text-teal-700' : 'text-rose-600' }} mt-1">
                            Rp {{ number_format($netProfit, 0, ',', '.') }}
                        </div>
                        <span class="text-xs text-teal-600 font-medium group-hover:underline inline-flex items-center mt-1">
                            Audit Kas Master &rarr;
                        </span>
                    </div>
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </a>

                <!-- Agenda Kritis / Overdue Global -->
                <a href="{{ route('admin.schedules.index', ['timeframe' => 'overdue']) }}" class="bg-white p-5 rounded-2xl border {{ $totalOverdueSchedules > 0 ? 'border-rose-200 bg-rose-50/20' : 'border-gray-100' }} shadow-xs hover:border-rose-300 transition flex items-center justify-between group">
                    <div>
                        <span class="text-xs font-semibold uppercase {{ $totalOverdueSchedules > 0 ? 'text-rose-600' : 'text-gray-400' }}">Jadwal Overdue</span>
                        <div class="text-2xl font-black {{ $totalOverdueSchedules > 0 ? 'text-rose-600' : 'text-gray-900' }} mt-1">
                            {{ $totalOverdueSchedules }} Agenda
                        </div>
                        <span class="text-xs text-rose-600 font-medium group-hover:underline inline-flex items-center mt-1">
                            Tinjau Keterlambatan &rarr;
                        </span>
                    </div>
                    <div class="w-12 h-12 {{ $totalOverdueSchedules > 0 ? 'bg-rose-100 text-rose-600' : 'bg-gray-100 text-gray-500' }} rounded-2xl flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </a>

            </div>

            <!-- 10 Modul Akses Cepat Admin -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-extrabold text-xl text-gray-900">10 Fitur Otoritas Administrator</h3>
                        <p class="text-xs text-gray-500 mt-1">Hak akses penuh tata kelola komoditas, audit finansial, dan pengawasan multi-petani.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        Level Otoritas: SuperAdmin
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">📊</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">1. Executive Dashboard</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">Analisis agregat nasional</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">👥</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">2. Manajemen Petani</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">Kontrol akun user petani</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.crops.index') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">🌱</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">3. Master Tanaman</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">CRUD katalog komoditas</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.lands.index') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">🗺️</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">4. Supervisi Lahan</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">Inspeksi seluruh plot QR</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.schedules.index') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">📅</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">5. Supervisi Jadwal</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">Deteksi timeline & overdue</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">💰</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">6. Master Kas Global</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">Pantau seluruh transaksi</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">🔐</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">7. Hak Koreksi & Hapus</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">Audit integrity pembukuan</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.transactions.export') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">📑</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">8. Ekspor Kas CSV</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">Laporan pembukuan master</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.calculator') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">🧮</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">9. Kalkulator Makro</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">Simulasi kebutuhan skala besar</p>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="p-4 rounded-2xl border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition flex flex-col justify-between">
                        <span class="text-2xl mb-2">⚙️</span>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">10. Profil & Keamanan</h4>
                            <p class="text-2xs text-gray-500 mt-0.5">Pengaturan kredensial admin</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Tabel Transaksi & Petani Terbaru -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- 5 Petani Terdaftar Terbaru -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-5">
                        <h4 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                            <span>👨‍🌾</span> Petani Mitra Terdaftar Terbaru
                        </h4>
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if ($recentFarmers->isEmpty())
                        <p class="text-sm text-gray-400 py-6 text-center">Belum ada akun petani terdaftar.</p>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($recentFarmers as $farmer)
                                <div class="py-3.5 flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">{{ $farmer->name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $farmer->email }}</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xs bg-emerald-50 text-emerald-800 font-semibold px-2 py-1 rounded-lg">
                                            {{ $farmer->lands_count }} Lahan
                                        </span>
                                        <a href="{{ route('admin.users.show', $farmer) }}" class="text-xs text-indigo-600 font-bold hover:underline">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 5 Transaksi Keuangan Terkini -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-5">
                        <h4 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                            <span>💰</span> Transaksi Kas Terkini (Lintas Petani)
                        </h4>
                        <a href="{{ route('admin.transactions.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">
                            Audit Kas &rarr;
                        </a>
                    </div>

                    @if ($recentTransactions->isEmpty())
                        <p class="text-sm text-gray-400 py-6 text-center">Belum ada transaksi kas tercatat.</p>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($recentTransactions as $trx)
                                <div class="py-3.5 flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">{{ $trx->category }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">
                                            Oleh: <strong class="text-gray-700">{{ $trx->user?->name ?? 'Petani' }}</strong> &bull; {{ $trx->transaction_date->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-sm {{ $trx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>


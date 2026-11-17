<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">
                        Detail Petani: {{ $user->name }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">Monitoring komprehensif data lahan, agenda, dan keuangan petani mitra.</p>
                </div>
            </div>

            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus seluruh akun petani ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl transition">
                    Hapus Akun Petani
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 3 Kartu Keuangan Petani -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs">
                    <span class="text-xs uppercase font-bold text-gray-400">Total Pemasukan Panen</span>
                    <div class="text-xl font-black text-emerald-700 mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs">
                    <span class="text-xs uppercase font-bold text-gray-400">Total Biaya Operasional</span>
                    <div class="text-xl font-black text-rose-600 mt-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs">
                    <span class="text-xs uppercase font-bold text-gray-400">Saldo Kas Petani</span>
                    <div class="text-xl font-black {{ $netProfit >= 0 ? 'text-teal-700' : 'text-rose-600' }} mt-1">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Lahan Petani Ini -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6">
                    <h3 class="font-bold text-gray-900 text-lg mb-4">Lahan Pertanian ({{ $user->lands->count() }} Petak)</h3>
                    @if ($user->lands->isEmpty())
                        <p class="text-sm text-gray-400 py-6 text-center">Petani ini belum mendaftarkan lahan.</p>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($user->lands as $land)
                                <div class="py-3 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm">{{ $land->name }}</h4>
                                        <div class="text-xs text-gray-500 mt-0.5">Luas: {{ $land->area_size }} &bull; {{ $land->location }}</div>
                                    </div>
                                    <div class="p-1 bg-white border border-gray-200 rounded shrink-0">
                                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(40)->generate($land->qr_code_text) !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Agenda Tanam Petani Ini -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6">
                    <h3 class="font-bold text-gray-900 text-lg mb-4">Agenda Tanam Aktif ({{ $user->schedules->count() }})</h3>
                    @if ($user->schedules->isEmpty())
                        <p class="text-sm text-gray-400 py-6 text-center">Belum ada agenda tanam yang dibuat.</p>
                    @else
                        <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                            @foreach ($user->schedules as $sched)
                                <div class="py-3 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-xs">{{ $sched->activity_name }}</h4>
                                        <div class="text-2xs text-gray-400 mt-0.5">
                                            Komoditas: {{ $sched->crop->name }} &bull; Tgl: {{ $sched->scheduled_date->format('d M Y') }}
                                        </div>
                                    </div>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-2xs font-bold {{ $sched->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
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
</x-app-layout>


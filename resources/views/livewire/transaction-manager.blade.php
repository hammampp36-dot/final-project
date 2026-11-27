<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-gray-200">
            <div>
                <h2 class="font-bold text-2xl {{ $isAdmin ? 'text-indigo-950' : 'text-emerald-900' }} leading-tight">
                    {{ $isAdmin ? '🏛️ Audit & Master Kas Keuangan Global' : '💵 Buku Kas Keuangan Pertanian' }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $isAdmin ? 'Audit arus kas masuk/keluar seluruh petani, otorisasi koreksi transaksi, dan ekspor CSV global.' : 'Pencatatan real-time arus kas masuk/keluar, otomatisasi laba/rugi bersih, dan perlindungan audit integritas kas.' }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ $isAdmin ? route('admin.transactions.export') : route('transactions.export') }}" class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 border border-emerald-200 text-emerald-800 font-semibold text-xs rounded-xl shadow-2xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Unduh Rekap CSV
                </a>
            </div>
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

        <!-- 3 Real-Time KPI Cards (Dynamic Updates via Livewire) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Pemasukan -->
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-emerald-100 flex items-center gap-4">
                <div class="p-3 bg-emerald-100 text-emerald-700 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pemasukan</p>
                    <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">
                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            <!-- Pengeluaran -->
            <div class="bg-white rounded-2xl p-5 shadow-xs border border-rose-100 flex items-center gap-4">
                <div class="p-3 bg-rose-100 text-rose-700 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pengeluaran</p>
                    <h3 class="text-xl font-extrabold text-rose-600 mt-0.5">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            <!-- Saldo Bersih (Net Profit) -->
            <div class="bg-white rounded-2xl p-5 shadow-xs border {{ $netProfit >= 0 ? 'border-teal-100 bg-gradient-to-br from-teal-50/50 to-white' : 'border-amber-100 bg-gradient-to-br from-amber-50/50 to-white' }} flex items-center gap-4">
                <div class="p-3 {{ $netProfit >= 0 ? 'bg-teal-100 text-teal-700' : 'bg-amber-100 text-amber-700' }} rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Saldo Kas Bersih</p>
                    <h3 class="text-xl font-extrabold {{ $netProfit >= 0 ? 'text-teal-700' : 'text-amber-700' }} mt-0.5">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Action & Filter Bar -->
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Filter Pills -->
            <div class="flex items-center gap-2">
                <button
                    wire:click="$set('filterType', 'all')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $filterType === 'all' ? 'bg-gray-900 text-white shadow-2xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                >
                    Semua Transaksi
                </button>
                <button
                    wire:click="$set('filterType', 'income')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $filterType === 'income' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                >
                    Pemasukan
                </button>
                <button
                    wire:click="$set('filterType', 'expense')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $filterType === 'expense' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}"
                >
                    Pengeluaran
                </button>
            </div>

            <div class="flex items-center gap-3">
                <button
                    wire:click="toggleForm"
                    class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition"
                >
                    <svg class="w-3.5 h-3.5 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    {{ $showForm ? 'Tutup Formulir' : '+ Catat Transaksi Baru' }}
                </button>
            </div>
        </div>

        <!-- Livewire Form: Catat Transaksi -->
        @if ($showForm)
            <div class="bg-gradient-to-br from-emerald-50/60 to-white border border-emerald-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <span>💰</span> Catat Transaksi Keuangan Tani
                </h3>
                <p class="text-xs text-gray-500 mb-5">
                    Setiap pencatatan akan memperbarui total arus kas dan saldo bersih secara langsung.
                </p>

                <form wire:submit="saveTransaction" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Tipe Transaksi -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Transaksi</label>
                            <select
                                wire:model.live="type"
                                class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 font-semibold"
                            >
                                <option value="expense">Pengeluaran (Biaya / Beban)</option>
                                <option value="income">Pemasukan (Hasil Panen / dll)</option>
                            </select>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kategori</label>
                            <select
                                wire:model="category"
                                class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                            >
                                @foreach ($commonCategories[$type] ?? [] as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nominal -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Nominal (Rp)</label>
                            <input
                                type="number"
                                step="100"
                                wire:model="amount"
                                placeholder="Contoh: 250000"
                                class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                            />
                            @error('amount') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Transaksi -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Transaksi</label>
                            <input
                                type="date"
                                wire:model="transaction_date"
                                class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                            />
                            @error('transaction_date') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Keterangan Tambahan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan / Catatan Tambahan (Opsional)</label>
                        <input
                            type="text"
                            wire:model="description"
                            placeholder="Contoh: Pembelian pupuk NPK 50kg di Kios Tani Berkah"
                            class="w-full text-sm rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                        />
                        @error('description') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                            class="inline-flex items-center px-6 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition"
                        >
                            <span wire:loading.remove wire:target="saveTransaction">Simpan Pembukuan Kas</span>
                            <span wire:loading wire:target="saveTransaction" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Transactions Table -->
        @if ($transactions->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
                <span class="text-4xl block mb-3">🧾</span>
                <h4 class="text-base font-bold text-gray-800">Belum ada catatan transaksi</h4>
                <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                    Catat setiap pengeluaran saprotan dan hasil penjualan panen Anda untuk memantau laba bersih secara real-time.
                </p>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-xs border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3.5 px-4">Tanggal</th>
                                @if ($isAdmin)
                                    <th class="py-3.5 px-4">Petani</th>
                                @endif
                                <th class="py-3.5 px-4">Jenis</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Keterangan</th>
                                <th class="py-3.5 px-4 text-right">Nominal (Rp)</th>
                                <th class="py-3.5 px-4 text-center">Hak Akses / Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($transactions as $tx)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="py-3 px-4 font-medium text-gray-700 whitespace-nowrap">
                                        {{ $tx->transaction_date->isoFormat('D MMM Y') }}
                                    </td>
                                    @if ($isAdmin)
                                        <td class="py-3 px-4 text-gray-900 font-semibold whitespace-nowrap">
                                            {{ $tx->user->name ?? '-' }}
                                        </td>
                                    @endif
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @if ($tx->type === 'income')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800">
                                                + Pemasukan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full font-bold bg-rose-100 text-rose-800">
                                                - Pengeluaran
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-gray-800 whitespace-nowrap">
                                        {{ $tx->category }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-500 max-w-xs truncate">
                                        {{ $tx->description ?: '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold whitespace-nowrap {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        @if (Auth::user()?->isAdmin())
                                            <button
                                                wire:click="deleteTransaction({{ $tx->id }})"
                                                wire:confirm="Sebagai Administrator, Anda yakin ingin menghapus catatan pembukuan senilai Rp {{ number_format($tx->amount, 0) }}?"
                                                class="text-xs font-semibold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded-lg transition"
                                            >
                                                Hapus Transaksi
                                            </button>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-400 bg-gray-50 px-2 py-1 rounded-md border border-gray-100" title="Keamanan Audit: Petani biasa tidak diizinkan menghapus pembukuan">
                                                🔒 Terkunci (Audit Admin)
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

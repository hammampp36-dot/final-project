<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index') }}" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-emerald-900 leading-tight">
                    Catat Transaksi Keuangan Baru
                </h2>
                <p class="text-sm text-gray-600 mt-0.5">Input arus kas masuk maupun pengeluaran operasional budidaya pertanian.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        trxType: '{{ old('type', 'expense') }}',
        incomeList: {{ json_encode($incomeCategories) }},
        expenseList: {{ json_encode($expenseCategories) }}
    }">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
                <div class="p-6 sm:p-8">

                    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Tipe Transaksi (Pemasukan vs Pengeluaran) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Jenis Transaksi <span class="text-rose-500">*</span>
                            </label>

                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center justify-center p-3.5 rounded-xl border-2 cursor-pointer transition"
                                       :class="trxType === 'income' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 font-bold' : 'border-gray-200 text-gray-600 hover:border-gray-300'">
                                    <input type="radio" name="type" value="income" x-model="trxType" class="sr-only">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                    </svg>
                                    Pemasukan (Income)
                                </label>

                                <label class="flex items-center justify-center p-3.5 rounded-xl border-2 cursor-pointer transition"
                                       :class="trxType === 'expense' ? 'border-rose-600 bg-rose-50 text-rose-900 font-bold' : 'border-gray-200 text-gray-600 hover:border-gray-300'">
                                    <input type="radio" name="type" value="expense" x-model="trxType" class="sr-only">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                    </svg>
                                    Pengeluaran (Expense)
                                </label>
                            </div>
                            @error('type')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Transaksi -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-900 mb-1">
                                Kategori <span class="text-rose-500">*</span>
                            </label>

                            <!-- Dropdown for Income -->
                            <template x-if="trxType === 'income'">
                                <select name="category" id="category" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm" required>
                                    <option value="">-- Pilih Kategori Pemasukan --</option>
                                    <template x-for="cat in incomeList" :key="cat">
                                        <option :value="cat" x-text="cat" :selected="'{{ old('category') }}' === cat"></option>
                                    </template>
                                </select>
                            </template>

                            <!-- Dropdown for Expense -->
                            <template x-if="trxType === 'expense'">
                                <select name="category" id="category" class="w-full rounded-xl border-gray-300 focus:border-rose-500 focus:ring-rose-500 shadow-2xs text-sm" required>
                                    <option value="">-- Pilih Kategori Pengeluaran --</option>
                                    <template x-for="cat in expenseList" :key="cat">
                                        <option :value="cat" x-text="cat" :selected="'{{ old('category') }}' === cat"></option>
                                    </template>
                                </select>
                            </template>
                            @error('category')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nominal Jumlah (Rp) -->
                        <div>
                            <label for="amount" class="block text-sm font-semibold text-gray-900 mb-1">
                                Jumlah Nominal (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative rounded-xl shadow-2xs">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-bold text-sm">
                                    Rp
                                </div>
                                <input type="number"
                                       id="amount"
                                       name="amount"
                                       min="1"
                                       step="any"
                                       value="{{ old('amount') }}"
                                       placeholder="Contoh: 250000"
                                       class="w-full rounded-xl border-gray-300 pl-12 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-semibold @error('amount') border-rose-500 @enderror"
                                       required>
                            </div>
                            @error('amount')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Transaksi -->
                        <div>
                            <label for="transaction_date" class="block text-sm font-semibold text-gray-900 mb-1">
                                Tanggal Transaksi <span class="text-rose-500">*</span>
                            </label>
                            <input type="date"
                                   id="transaction_date"
                                   name="transaction_date"
                                   value="{{ old('transaction_date', date('Y-m-d')) }}"
                                   class="w-full sm:w-72 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm @error('transaction_date') border-rose-500 @enderror"
                                   required>
                            @error('transaction_date')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan / Deskripsi -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-1">
                                Keterangan Tambahan (Opsional)
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Contoh: Pembelian bibit cabai merah 10 pak di Toko Pertanian Berkah"
                                      class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-2xs text-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold transition">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-xs transition"
                                    :class="trxType === 'income' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'">
                                Simpan Transaksi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


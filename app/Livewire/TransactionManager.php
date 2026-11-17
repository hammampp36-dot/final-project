<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransactionManager extends Component
{
    public string $type = 'expense';

    public string $category = 'Pembelian Pupuk & Obat';

    public ?float $amount = null;

    public string $transaction_date = '';

    public string $description = '';

    public string $filterType = 'all'; // all, income, expense

    public bool $showForm = false;

    public bool $isAdmin = false;

    /**
     * @var array<string, array<int, string>>
     */
    public array $commonCategories = [
        'income' => [
            'Penjualan Hasil Panen',
            'Subsidi Pertanian',
            'Sewa Alsintan / Alat Tani',
            'Lain-lain (Pemasukan)',
        ],
        'expense' => [
            'Pembelian Pupuk & Obat',
            'Pembelian Bibit / Benih Unggul',
            'Upah Tenaga Kerja Tani',
            'Bahan Bakar Mesin & Irigasi',
            'Sewa Lahan / Alsintan',
            'Lain-lain (Pengeluaran)',
        ],
    ];

    public function mount(bool $isAdmin = false): void
    {
        $this->isAdmin = $isAdmin;
        $this->transaction_date = now()->format('Y-m-d');
        $this->category = $this->commonCategories['expense'][0];
    }

    public function updatedType(string $newType): void
    {
        if (isset($this->commonCategories[$newType])) {
            $this->category = $this->commonCategories[$newType][0];
        }
    }

    public function toggleForm(): void
    {
        $this->showForm = ! $this->showForm;
    }

    public function saveTransaction(): void
    {
        $validated = $this->validate([
            'type' => ['required', 'in:income,expense'],
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:100'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'amount.required' => 'Nominal transaksi wajib diisi.',
            'amount.min' => 'Nominal minimal Rp 100.',
            'category.required' => 'Kategori transaksi wajib ditentukan.',
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'description' => $validated['description'] ?? null,
        ]);

        $this->reset(['amount', 'description']);
        $this->showForm = false;

        session()->flash('success', 'Catatan transaksi kas berhasil dibukukan!');
    }

    public function deleteTransaction(int $transactionId): void
    {
        if (! Auth::user()?->isAdmin()) {
            abort(403, 'Akses Ditolak: Petani tidak memiliki izin audit untuk menghapus data pembukuan keuangan.');
        }

        $transaction = Transaction::findOrFail($transactionId);
        $transaction->delete();

        session()->flash('success', 'Transaksi keuangan berhasil dihapus oleh Administrator.');
    }

    public function render(): View
    {
        $baseQuery = $this->isAdmin ? Transaction::query() : Transaction::where('user_id', Auth::id());

        $totalIncome = (clone $baseQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        $listQuery = $this->isAdmin ? Transaction::with('user') : Transaction::where('user_id', Auth::id());

        if ($this->filterType === 'income') {
            $listQuery->where('type', 'income');
        } elseif ($this->filterType === 'expense') {
            $listQuery->where('type', 'expense');
        }

        $transactions = $listQuery->orderBy('transaction_date', 'desc')->latest()->get();

        return view('livewire.transaction-manager', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netProfit' => $netProfit,
            'transactions' => $transactions,
        ]);
    }
}

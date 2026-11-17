<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of financial transactions and summary cards.
     */
    public function index(Request $request): View
    {
        $query = Auth::user()->transactions()->latest('transaction_date');

        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        $transactions = $query->paginate(15)->withQueryString();

        $totalIncome = (float) Auth::user()->transactions()->where('type', 'income')->sum('amount');
        $totalExpense = (float) Auth::user()->transactions()->where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return view('transactions.index', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'netProfit'
        ));
    }

    /**
     * Show the form for recording a new transaction.
     */
    public function create(): View
    {
        $incomeCategories = [
            'Penjualan Hasil Panen',
            'Sewa Alat Mesin Pertanian',
            'Subsidi / Bantuan Pemerintah',
            'Penjualan Bibit / Benih',
            'Pendapatan Lainnya',
        ];

        $expenseCategories = [
            'Pembelian Benih / Bibit',
            'Pembelian Pupuk Organik & Kimia',
            'Pembelian Pestisida / Obat Hama',
            'Upah Tenaga Kerja Tani',
            'Sewa / Perawatan Mesin & Alat',
            'Bahan Bakar & Irigasi',
            'Operasional & Transportasi',
            'Pengeluaran Lainnya',
        ];

        return view('transactions.create', compact('incomeCategories', 'expenseCategories'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Auth::user()->transactions()->create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi keuangan berhasil dicatat!');
    }

    /**
     * Export all user transactions to a CSV file.
     */
    public function exportCsv()
    {
        $transactions = Auth::user()->transactions()->latest('transaction_date')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-keuangan-agrismart-'.date('Y-m-d').'.csv"',
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Tanggal', 'Tipe', 'Kategori', 'Nominal (Rp)', 'Keterangan']);

            foreach ($transactions as $trx) {
                fputcsv($handle, [
                    $trx->id,
                    $trx->transaction_date->format('Y-m-d'),
                    $trx->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                    $trx->category,
                    $trx->amount,
                    $trx->description ?: '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove the specified transaction from storage (Restricted to Admin).
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        // Petani biasa tidak boleh menghapus transaksi untuk menjaga integritas kas
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak: Petani tidak memiliki izin menghapus atau mengubah catatan keuangan yang telah tersimpan. Hubungi Administrator untuk perbaikan data kas.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Catatan transaksi berhasil dihapus oleh administrator.');
    }
}

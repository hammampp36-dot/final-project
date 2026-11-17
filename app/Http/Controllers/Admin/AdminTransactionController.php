<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTransactionController extends Controller
{
    /**
     * Display a listing of all financial transactions across all farmers.
     */
    public function index(Request $request): View
    {
        $query = Transaction::with('user')->latest('transaction_date');

        if ($request->filled('farmer_id')) {
            $query->where('user_id', $request->query('farmer_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        $transactions = $query->paginate(15)->withQueryString();

        $totalIncome = (float) Transaction::where('type', 'income')->sum('amount');
        $totalExpense = (float) Transaction::where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        $farmers = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'netProfit',
            'farmers'
        ));
    }

    /**
     * Delete a transaction (Admin privilege for audit/correction).
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $desc = $transaction->category.' (Rp '.number_format($transaction->amount, 0, ',', '.').')';
        $transaction->delete();

        return redirect()->route('admin.transactions.index')
            ->with('success', "Catatan transaksi kas {$desc} berhasil dihapus oleh administrator.");
    }

    /**
     * Export all system transactions to CSV.
     */
    public function exportCsv()
    {
        $transactions = Transaction::with('user')->latest('transaction_date')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-kas-global-agrismart-'.date('Y-m-d').'.csv"',
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Nama Petani', 'Email Petani', 'Tanggal', 'Tipe', 'Kategori', 'Nominal (Rp)', 'Keterangan']);

            foreach ($transactions as $trx) {
                fputcsv($handle, [
                    $trx->id,
                    $trx->user?->name ?? 'N/A',
                    $trx->user?->email ?? 'N/A',
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
}

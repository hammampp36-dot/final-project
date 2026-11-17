<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Models\Land;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the Executive Agricultural Dashboard for Admins.
     */
    public function index(): View
    {
        $today = Carbon::today()->format('Y-m-d');

        // Metrik Agregat Global
        $totalFarmers = User::where('role', 'user')->count();
        $totalLands = Land::count();
        $totalCrops = Crop::count();

        $totalIncome = (float) Transaction::where('type', 'income')->sum('amount');
        $totalExpense = (float) Transaction::where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        $totalSchedules = Schedule::count();
        $totalPendingSchedules = Schedule::where('status', 'pending')->count();
        $totalOverdueSchedules = Schedule::where('status', 'pending')->whereDate('scheduled_date', '<', $today)->count();

        // 5 Petani Terbaru
        $recentFarmers = User::where('role', 'user')
            ->withCount(['lands', 'schedules'])
            ->latest()
            ->take(5)
            ->get();

        // 5 Transaksi Keuangan Terkini Lintas Petani
        $recentTransactions = Transaction::with('user')
            ->latest('transaction_date')
            ->take(5)
            ->get();

        // 5 Agenda Kritis / Terlewat
        $criticalSchedules = Schedule::with(['user', 'crop', 'land'])
            ->where('status', 'pending')
            ->orderBy('scheduled_date', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalFarmers',
            'totalLands',
            'totalCrops',
            'totalIncome',
            'totalExpense',
            'netProfit',
            'totalSchedules',
            'totalPendingSchedules',
            'totalOverdueSchedules',
            'recentFarmers',
            'recentTransactions',
            'criticalSchedules'
        ));
    }
}

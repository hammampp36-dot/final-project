<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the main application dashboard with consolidated metrics.
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        // Administrator diarahkan langsung ke Dashboard Eksekutif Admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $totalLands = $user->lands()->count();
        $totalSchedules = $user->schedules()->count();
        $pendingSchedulesCount = $user->schedules()->where('status', 'pending')->count();
        $completedSchedulesCount = $user->schedules()->where('status', 'completed')->count();

        $totalIncome = (float) $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpense = (float) $user->transactions()->where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        $upcomingSchedules = $user->schedules()
            ->with(['crop', 'land'])
            ->where('status', 'pending')
            ->orderBy('scheduled_date', 'asc')
            ->take(5)
            ->get();

        $recentTransactions = $user->transactions()
            ->latest('transaction_date')
            ->take(5)
            ->get();

        $lands = $user->lands()->latest()->take(3)->get();

        return view('dashboard', compact(
            'totalLands',
            'totalSchedules',
            'pendingSchedulesCount',
            'completedSchedulesCount',
            'totalIncome',
            'totalExpense',
            'netProfit',
            'upcomingSchedules',
            'recentTransactions',
            'lands'
        ));
    }
}

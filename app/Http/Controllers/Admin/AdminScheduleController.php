<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminScheduleController extends Controller
{
    /**
     * Display a listing of all farming schedules across all farmers.
     */
    public function index(Request $request): View
    {
        $today = Carbon::today()->format('Y-m-d');
        $query = Schedule::with(['user', 'crop', 'land'])
            ->orderBy('scheduled_date', 'asc');

        if ($request->filled('farmer_id')) {
            $query->where('user_id', $request->query('farmer_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->query('timeframe') === 'today') {
            $query->whereDate('scheduled_date', $today);
        } elseif ($request->query('timeframe') === 'overdue') {
            $query->where('status', 'pending')->whereDate('scheduled_date', '<', $today);
        } elseif ($request->query('timeframe') === 'upcoming') {
            $query->whereDate('scheduled_date', '>', $today);
        }

        $schedules = $query->paginate(15)->withQueryString();

        $totalSchedules = Schedule::count();
        $totalPending = Schedule::where('status', 'pending')->count();
        $totalOverdue = Schedule::where('status', 'pending')->whereDate('scheduled_date', '<', $today)->count();
        $totalToday = Schedule::whereDate('scheduled_date', $today)->count();
        $farmers = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.schedules.index', compact(
            'schedules',
            'totalSchedules',
            'totalPending',
            'totalOverdue',
            'totalToday',
            'farmers'
        ));
    }

    /**
     * Toggle schedule status on behalf of supervision.
     */
    public function toggleStatus(Schedule $schedule): RedirectResponse
    {
        $newStatus = $schedule->status === 'completed' ? 'pending' : 'completed';
        $schedule->update(['status' => $newStatus]);

        return back()->with('success', "Status agenda '{$schedule->activity_name}' milik petani {$schedule->user->name} diperbarui.");
    }

    /**
     * Remove the specified schedule.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return back()->with('success', 'Agenda jadwal berhasil dihapus oleh administrator.');
    }
}

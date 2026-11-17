<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
     * Display a listing of user's farming schedules.
     */
    public function index(Request $request): View
    {
        $today = Carbon::today()->format('Y-m-d');
        $query = Auth::user()->schedules()
            ->with(['crop', 'land'])
            ->orderBy('scheduled_date', 'asc');

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

        $totalPending = Auth::user()->schedules()->where('status', 'pending')->count();
        $totalCompleted = Auth::user()->schedules()->where('status', 'completed')->count();
        $totalToday = Auth::user()->schedules()->whereDate('scheduled_date', $today)->count();
        $totalOverdue = Auth::user()->schedules()->where('status', 'pending')->whereDate('scheduled_date', '<', $today)->count();

        return view('schedules.index', compact('schedules', 'totalPending', 'totalCompleted', 'totalToday', 'totalOverdue'));
    }

    /**
     * Show the form for creating a new smart schedule from crop catalog.
     */
    public function create(): View
    {
        $crops = Crop::orderBy('name')->get();
        $lands = Auth::user()->lands()->orderBy('name')->get();

        return view('schedules.create', compact('crops', 'lands'));
    }

    /**
     * Generate smart schedule recommendations and store in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'crop_id' => ['required', 'exists:crops,id'],
            'land_id' => ['nullable', 'exists:lands,id'],
            'planting_date' => ['required', 'date'],
        ]);

        $crop = Crop::findOrFail($validated['crop_id']);
        $startDate = Carbon::parse($validated['planting_date']);
        $harvestDays = (int) $crop->estimated_harvest_days;

        // Daftar rekomendasi aktivitas cerdas berdasarkan siklus tanaman
        $activities = [];

        // Hari 0: Penanaman
        $activities[] = [
            'day' => 0,
            'name' => "Penanaman Bibit Awal {$crop->name}",
        ];

        // Hari 1: Penyiraman pertama
        $activities[] = [
            'day' => 1,
            'name' => 'Penyiraman Rutin Awal & Pemadatan Tanah',
        ];

        // Hari 7: Pengecekan tunas
        if ($harvestDays >= 14) {
            $activities[] = [
                'day' => 7,
                'name' => 'Penyiraman Berkala & Pemeriksaan Tunas Awal',
            ];
        }

        // Hari 14: Pemupukan Susulan 1
        if ($harvestDays >= 25) {
            $activities[] = [
                'day' => 14,
                'name' => 'Pemupukan Susulan Tahap 1 & Penyiangan Gulma',
            ];
        }

        // Hari 28: Pemupukan Susulan 2 / Pemasangan Ajir
        if ($harvestDays >= 40) {
            $activities[] = [
                'day' => 28,
                'name' => 'Pemupukan Susulan Tahap 2 & Penggemburan Bedengan',
            ];
        }

        // Hari 45: Pengendalian hama & nutrisi bunga
        if ($harvestDays >= 60) {
            $activities[] = [
                'day' => 45,
                'name' => 'Pengendalian Hama & Pemupukan Nutrisi Pembungaan',
            ];
        }

        // Hari 70: Pemantauan buah menjelang panen
        if ($harvestDays >= 85) {
            $activities[] = [
                'day' => (int) round($harvestDays * 0.75),
                'name' => 'Pemeriksaan Kematangan Buah & Pengurangan Debit Air',
            ];
        }

        // Hari Panen
        $activities[] = [
            'day' => $harvestDays,
            'name' => "Estimasi Panen Raya {$crop->name} (Hari ke-{$harvestDays})",
        ];

        foreach ($activities as $act) {
            $scheduledDate = $startDate->copy()->addDays($act['day']);

            Auth::user()->schedules()->create([
                'crop_id' => $crop->id,
                'land_id' => $validated['land_id'] ?? null,
                'activity_name' => $act['name'],
                'scheduled_date' => $scheduledDate->format('Y-m-d'),
                'status' => 'pending',
            ]);
        }

        return redirect()->route('schedules.index')
            ->with('success', "Smart Jadwal untuk {$crop->name} ({$harvestDays} hari) berhasil dibuat otomatis!");
    }

    /**
     * Toggle schedule status (pending / completed).
     */
    public function toggleStatus(Schedule $schedule): RedirectResponse
    {
        abort_if($schedule->user_id !== Auth::id(), 403, 'Akses tidak diizinkan.');

        $newStatus = $schedule->status === 'completed' ? 'pending' : 'completed';
        $schedule->update(['status' => $newStatus]);

        $message = $newStatus === 'completed' ? 'Aktivitas ditandai selesai!' : 'Aktivitas dikembalikan ke pending.';

        return back()->with('success', $message);
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        abort_if($schedule->user_id !== Auth::id(), 403, 'Akses tidak diizinkan.');

        $schedule->delete();

        return back()->with('success', 'Jadwal aktivitas berhasil dihapus.');
    }
}

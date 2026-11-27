<?php

namespace App\Livewire;

use App\Models\Crop;
use App\Models\Land;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ScheduleManager extends Component
{
    public ?int $crop_id = null;

    public ?int $land_id = null;

    public string $planting_date = '';

    public string $activity_name = '';

    public string $custom_date = '';

    public string $activeTab = 'all'; // all, pending, today, overdue, completed

    public bool $isAutoGenerator = true;

    public bool $isAdmin = false;

    public function mount(bool $isAdmin = false): void
    {
        $this->isAdmin = $isAdmin || (request()->routeIs('admin.*') || (Auth::check() && Auth::user()->isAdmin() && request()->is('admin*')));
        $this->planting_date = now()->format('Y-m-d');
        $this->custom_date = now()->format('Y-m-d');

        $firstCrop = Crop::first();
        if ($firstCrop) {
            $this->crop_id = $firstCrop->id;
        }

        $firstLand = Land::where('user_id', Auth::id())->first();
        if ($firstLand) {
            $this->land_id = $firstLand->id;
        }
    }

    public function generateSchedules(): void
    {
        $this->validate([
            'crop_id' => ['required', 'exists:crops,id'],
            'planting_date' => ['required', 'date'],
            'land_id' => ['nullable', 'exists:lands,id'],
        ]);

        $crop = Crop::findOrFail($this->crop_id);
        $startDate = Carbon::parse($this->planting_date);

        // Generate smart sequence of farming activities
        $activities = [
            [
                'name' => 'Persiapan Lahan & Penanaman Bibit '.$crop->name,
                'date' => $startDate->copy()->format('Y-m-d'),
            ],
            [
                'name' => 'Penyiraman & Pengecekan Hama Awal '.$crop->name,
                'date' => $startDate->copy()->addDays(7)->format('Y-m-d'),
            ],
            [
                'name' => 'Pemupukan Susulan I (NPK / Organik)',
                'date' => $startDate->copy()->addDays(21)->format('Y-m-d'),
            ],
            [
                'name' => 'Pemupukan Susulan II & Perawatan Bunga',
                'date' => $startDate->copy()->addDays(45)->format('Y-m-d'),
            ],
            [
                'name' => 'Estimasi Panen Raya '.$crop->name,
                'date' => $startDate->copy()->addDays($crop->estimated_harvest_days)->format('Y-m-d'),
            ],
        ];

        foreach ($activities as $act) {
            Schedule::create([
                'user_id' => Auth::id(),
                'crop_id' => $crop->id,
                'land_id' => $this->land_id,
                'activity_name' => $act['name'],
                'scheduled_date' => $act['date'],
                'status' => 'pending',
            ]);
        }

        session()->flash('success', 'Rangkaian jadwal cerdas budidaya '.$crop->name.' berhasil digenerate otomatis!');
    }

    public function addManualSchedule(): void
    {
        $this->validate([
            'activity_name' => ['required', 'string', 'max:255'],
            'custom_date' => ['required', 'date'],
            'crop_id' => ['nullable', 'exists:crops,id'],
            'land_id' => ['nullable', 'exists:lands,id'],
        ]);

        Schedule::create([
            'user_id' => Auth::id(),
            'crop_id' => $this->crop_id,
            'land_id' => $this->land_id,
            'activity_name' => $this->activity_name,
            'scheduled_date' => $this->custom_date,
            'status' => 'pending',
        ]);

        $this->reset(['activity_name']);
        session()->flash('success', 'Aktivitas tani baru berhasil ditambahkan ke jadwal.');
    }

    public function toggleStatus(int $scheduleId): void
    {
        $schedule = Schedule::findOrFail($scheduleId);

        if (! $this->isAdmin && $schedule->user_id !== Auth::id()) {
            abort(403);
        }

        $schedule->update([
            'status' => $schedule->status === 'completed' ? 'pending' : 'completed',
        ]);
    }

    public function deleteSchedule(int $scheduleId): void
    {
        $schedule = Schedule::findOrFail($scheduleId);

        if (! $this->isAdmin && $schedule->user_id !== Auth::id()) {
            abort(403);
        }

        $schedule->delete();
        session()->flash('success', 'Jadwal aktivitas berhasil dihapus.');
    }

    public function render(): View
    {
        $crops = Crop::orderBy('name')->get();
        $lands = $this->isAdmin ? Land::orderBy('name')->get() : Land::where('user_id', Auth::id())->orderBy('name')->get();

        $selectedCrop = $this->crop_id ? Crop::find($this->crop_id) : null;
        $estimatedHarvest = null;

        if ($selectedCrop && $this->planting_date) {
            $estimatedHarvest = Carbon::parse($this->planting_date)->addDays($selectedCrop->estimated_harvest_days);
        }

        $query = $this->isAdmin ? Schedule::with(['crop', 'land', 'user']) : Schedule::with(['crop', 'land'])->where('user_id', Auth::id());

        $today = now()->format('Y-m-d');

        // Apply Tab Filter
        if ($this->activeTab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->activeTab === 'completed') {
            $query->where('status', 'completed');
        } elseif ($this->activeTab === 'today') {
            $query->where('scheduled_date', $today);
        } elseif ($this->activeTab === 'overdue') {
            $query->where('status', 'pending')->where('scheduled_date', '<', $today);
        }

        $schedules = $query->orderBy('scheduled_date', 'asc')->get();

        // Calculate counts for tabs
        $baseQuery = $this->isAdmin ? Schedule::query() : Schedule::where('user_id', Auth::id());
        $allCount = (clone $baseQuery)->count();
        $pendingCount = (clone $baseQuery)->where('status', 'pending')->count();
        $todayCount = (clone $baseQuery)->where('scheduled_date', $today)->count();
        $overdueCount = (clone $baseQuery)->where('status', 'pending')->where('scheduled_date', '<', $today)->count();
        $completedCount = (clone $baseQuery)->where('status', 'completed')->count();

        return view('livewire.schedule-manager', [
            'crops' => $crops,
            'lands' => $lands,
            'selectedCrop' => $selectedCrop,
            'estimatedHarvest' => $estimatedHarvest,
            'schedules' => $schedules,
            'counts' => [
                'all' => $allCount,
                'pending' => $pendingCount,
                'today' => $todayCount,
                'overdue' => $overdueCount,
                'completed' => $completedCount,
            ],
        ]);
    }
}

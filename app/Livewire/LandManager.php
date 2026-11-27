<?php

namespace App\Livewire;

use App\Models\Land;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LandManager extends Component
{
    public string $name = '';

    public ?float $area_size = null;

    public string $location = '';

    public string $search = '';

    public ?int $selectedLandId = null;

    public bool $isAdmin = false;

    public bool $showCreateForm = false;

    public function mount(bool $isAdmin = false): void
    {
        $this->isAdmin = $isAdmin || (request()->routeIs('admin.*') || (Auth::check() && Auth::user()->isAdmin() && request()->is('admin*')));
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'area_size' => ['required', 'numeric', 'min:1'],
            'location' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama petak lahan wajib diisi.',
            'area_size.required' => 'Luas lahan wajib diisi.',
            'area_size.min' => 'Luas lahan minimal 1 m².',
            'location.required' => 'Lokasi lahan wajib diisi.',
        ];
    }

    public function toggleCreateForm(): void
    {
        $this->showCreateForm = ! $this->showCreateForm;
    }

    public function saveLand(): void
    {
        $validated = $this->validate();

        $qrCodeText = 'AGRI-LAND-'.strtoupper(Str::random(8));

        Land::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'area_size' => $validated['area_size'],
            'location' => $validated['location'],
            'qr_code_text' => $qrCodeText,
        ]);

        $this->reset(['name', 'area_size', 'location']);
        $this->showCreateForm = false;

        session()->flash('success', 'Petak lahan baru berhasil didaftarkan dan QR Code telah diterbitkan!');
    }

    public function deleteLand(int $landId): void
    {
        $land = Land::findOrFail($landId);

        if (! $this->isAdmin && $land->user_id !== Auth::id()) {
            abort(403, 'Akses tidak sah untuk menghapus petak lahan ini.');
        }

        $land->delete();

        if ($this->selectedLandId === $landId) {
            $this->selectedLandId = null;
        }

        session()->flash('success', 'Petak lahan berhasil dihapus.');
    }

    public function showQr(int $landId): void
    {
        $this->selectedLandId = $landId;
    }

    public function closeQr(): void
    {
        $this->selectedLandId = null;
    }

    public function render(): View
    {
        $query = $this->isAdmin ? Land::with('user') : Land::where('user_id', Auth::id());

        if (! empty($this->search)) {
            $search = '%'.$this->search.'%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('location', 'like', $search)
                    ->orWhere('qr_code_text', 'like', $search);
            });
        }

        $lands = $query->latest()->get();
        $selectedLand = $this->selectedLandId ? Land::find($this->selectedLandId) : null;

        return view('livewire.land-manager', [
            'lands' => $lands,
            'selectedLand' => $selectedLand,
        ]);
    }
}

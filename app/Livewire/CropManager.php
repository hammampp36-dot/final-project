<?php

namespace App\Livewire;

use App\Models\Crop;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CropManager extends Component
{
    public string $search = '';

    public string $selectedCategory = 'all';

    public ?int $viewingCropId = null;

    public bool $isAdmin = false;

    // Admin CRUD state
    public bool $showForm = false;

    public ?int $editingCropId = null;

    public string $name = '';

    public string $category = 'Sayuran';

    public ?int $estimated_harvest_days = 90;

    public string $planting_guide = '';

    public function mount(bool $isAdmin = false): void
    {
        $this->isAdmin = $isAdmin || (request()->routeIs('admin.*') || (Auth::check() && Auth::user()->isAdmin() && request()->is('admin*')));
    }

    public function toggleForm(): void
    {
        $this->showForm = ! $this->showForm;
        if (! $this->showForm) {
            $this->reset(['editingCropId', 'name', 'category', 'estimated_harvest_days', 'planting_guide']);
        }
    }

    public function editCrop(int $cropId): void
    {
        if (! $this->isAdmin) {
            abort(403);
        }

        $crop = Crop::findOrFail($cropId);
        $this->editingCropId = $crop->id;
        $this->name = $crop->name;
        $this->category = $crop->category;
        $this->estimated_harvest_days = $crop->estimated_harvest_days;
        $this->planting_guide = $crop->planting_guide;
        $this->showForm = true;
    }

    public function saveCrop(): void
    {
        if (! $this->isAdmin) {
            abort(403);
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'estimated_harvest_days' => ['required', 'integer', 'min:1', 'max:1000'],
            'planting_guide' => ['required', 'string'],
        ], [
            'name.required' => 'Nama tanaman wajib diisi.',
            'category.required' => 'Kategori tanaman wajib diisi.',
            'estimated_harvest_days.required' => 'Estimasi masa panen wajib diisi.',
            'planting_guide.required' => 'Panduan budidaya wajib diisi.',
        ]);

        if ($this->editingCropId) {
            $crop = Crop::findOrFail($this->editingCropId);
            $crop->update($validated);
            session()->flash('success', "Data komoditas '{$crop->name}' berhasil diperbarui!");
        } else {
            Crop::create($validated);
            session()->flash('success', "Komoditas baru '{$validated['name']}' berhasil ditambahkan ke katalog!");
        }

        $this->reset(['editingCropId', 'name', 'category', 'estimated_harvest_days', 'planting_guide']);
        $this->showForm = false;
    }

    public function deleteCrop(int $cropId): void
    {
        if (! $this->isAdmin) {
            abort(403);
        }

        $crop = Crop::findOrFail($cropId);
        $cropName = $crop->name;
        $crop->delete();

        session()->flash('success', "Komoditas '{$cropName}' berhasil dihapus dari master katalog.");
    }

    public function viewGuide(int $cropId): void
    {
        $this->viewingCropId = $cropId;
    }

    public function closeGuide(): void
    {
        $this->viewingCropId = null;
    }

    public function render(): View
    {
        $query = Crop::query();

        if (! empty($this->search)) {
            $search = '%'.$this->search.'%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('category', 'like', $search)
                    ->orWhere('planting_guide', 'like', $search);
            });
        }

        if ($this->selectedCategory !== 'all') {
            $query->where('category', $this->selectedCategory);
        }

        $crops = $query->orderBy('name')->get();
        $categories = Crop::select('category')->distinct()->pluck('category');
        $viewingCrop = $this->viewingCropId ? Crop::find($this->viewingCropId) : null;

        return view('livewire.crop-manager', [
            'crops' => $crops,
            'categories' => $categories,
            'viewingCrop' => $viewingCrop,
        ]);
    }
}

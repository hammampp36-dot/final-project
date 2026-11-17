<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCropController extends Controller
{
    /**
     * Display a listing of crops for admin management.
     */
    public function index(Request $request): View
    {
        $query = Crop::withCount('schedules')->orderBy('name');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        $crops = $query->paginate(12)->withQueryString();
        $categories = Crop::select('category')->distinct()->pluck('category');

        return view('admin.crops.index', compact('crops', 'categories'));
    }

    /**
     * Show the form for creating a new crop.
     */
    public function create(): View
    {
        return view('admin.crops.create');
    }

    /**
     * Store a newly created crop in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:crops,name'],
            'category' => ['required', 'string', 'max:100'],
            'estimated_harvest_days' => ['required', 'integer', 'min:1'],
            'planting_guide' => ['nullable', 'string', 'max:2000'],
        ]);

        Crop::create($validated);

        return redirect()->route('admin.crops.index')
            ->with('success', "Komoditas {$validated['name']} berhasil ditambahkan ke katalog master.");
    }

    /**
     * Show the form for editing the specified crop.
     */
    public function edit(Crop $crop): View
    {
        return view('admin.crops.edit', compact('crop'));
    }

    /**
     * Update the specified crop in storage.
     */
    public function update(Request $request, Crop $crop): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:crops,name,'.$crop->id],
            'category' => ['required', 'string', 'max:100'],
            'estimated_harvest_days' => ['required', 'integer', 'min:1'],
            'planting_guide' => ['nullable', 'string', 'max:2000'],
        ]);

        $crop->update($validated);

        return redirect()->route('admin.crops.index')
            ->with('success', "Data komoditas {$crop->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified crop from storage.
     */
    public function destroy(Crop $crop): RedirectResponse
    {
        $cropName = $crop->name;
        $crop->delete();

        return redirect()->route('admin.crops.index')
            ->with('success', "Komoditas {$cropName} berhasil dihapus dari katalog master.");
    }
}

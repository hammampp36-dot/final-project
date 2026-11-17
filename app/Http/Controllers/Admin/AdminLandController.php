<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Land;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLandController extends Controller
{
    /**
     * Display a listing of all lands across all farmers.
     */
    public function index(Request $request): View
    {
        $query = Land::with(['user', 'schedules'])->latest();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $lands = $query->paginate(12)->withQueryString();
        $totalLandsCount = Land::count();

        return view('admin.lands.index', compact('lands', 'totalLandsCount'));
    }

    /**
     * Display the specified land.
     */
    public function show(Land $land): View
    {
        $land->load(['user', 'schedules.crop']);

        return view('admin.lands.show', compact('land'));
    }

    /**
     * Remove the specified land from storage.
     */
    public function destroy(Land $land): RedirectResponse
    {
        $landName = $land->name;
        $land->delete();

        return redirect()->route('admin.lands.index')
            ->with('success', "Data petak lahan {$landName} berhasil dihapus.");
    }
}

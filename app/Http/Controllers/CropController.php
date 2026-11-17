<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CropController extends Controller
{
    /**
     * Display a listing of crops in the agriculture catalog.
     */
    public function index(Request $request): View
    {
        $query = Crop::query()->orderBy('name');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        $crops = $query->get();
        $categories = Crop::select('category')->distinct()->pluck('category');

        return view('crops.index', compact('crops', 'categories'));
    }
}

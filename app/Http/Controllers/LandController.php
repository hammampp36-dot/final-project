<?php

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LandController extends Controller
{
    /**
     * Display a listing of user's lands.
     */
    public function index(): View
    {
        $lands = Auth::user()->lands()
            ->withCount('schedules')
            ->latest()
            ->paginate(9);

        return view('lands.index', compact('lands'));
    }

    /**
     * Show the form for creating a new land.
     */
    public function create(): View
    {
        return view('lands.create');
    }

    /**
     * Store a newly created land in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'area_size' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $uniqueCode = 'AGRI-LAND-'.Auth::id().'-'.strtoupper(Str::random(8));

        // Format info QR Code untuk scan
        $qrPayload = json_encode([
            'code' => $uniqueCode,
            'name' => $validated['name'],
            'area' => $validated['area_size'],
            'location' => $validated['location'],
            'owner' => Auth::user()->name,
            'app' => 'Agrismart',
        ], JSON_UNESCAPED_SLASHES);

        $land = Auth::user()->lands()->create([
            'name' => $validated['name'],
            'area_size' => $validated['area_size'],
            'location' => $validated['location'],
            'qr_code_text' => $qrPayload,
        ]);

        return redirect()->route('lands.show', $land)->with('success', 'Data lahan berhasil didaftarkan beserta QR Code!');
    }

    /**
     * Display the specified land with its QR Code.
     */
    public function show(Land $land): View
    {
        abort_if($land->user_id !== Auth::id(), 403, 'Akses tidak diizinkan.');

        $land->load(['schedules.crop']);

        // Generate SVG QR Code
        $qrCodeSvg = QrCode::size(250)->generate($land->qr_code_text);

        return view('lands.show', compact('land', 'qrCodeSvg'));
    }

    /**
     * Download the QR Code as an SVG file.
     */
    public function downloadQr(Land $land)
    {
        abort_if($land->user_id !== Auth::id(), 403, 'Akses tidak diizinkan.');

        $svg = QrCode::format('svg')->size(400)->generate($land->qr_code_text);
        $fileName = 'qrcode-lahan-'.Str::slug($land->name).'.svg';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }

    /**
     * Show scanner page to scan or search land by QR text.
     */
    public function scan(Request $request): View
    {
        $landFound = null;
        $searchCode = $request->query('code');

        if ($searchCode) {
            $landFound = Auth::user()->lands()
                ->where('qr_code_text', 'like', '%'.$searchCode.'%')
                ->first();
        }

        return view('lands.scan', compact('landFound', 'searchCode'));
    }

    /**
     * Remove the specified land from storage.
     */
    public function destroy(Land $land): RedirectResponse
    {
        abort_if($land->user_id !== Auth::id(), 403, 'Akses tidak diizinkan.');

        $land->delete();

        return redirect()->route('lands.index')->with('success', 'Data lahan berhasil dihapus.');
    }
}

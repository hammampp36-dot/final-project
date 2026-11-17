<?php

namespace App\Livewire;

use App\Models\Crop;
use App\Models\Land;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FarmCalculator extends Component
{
    /**
     * Selected crop ID.
     */
    public ?int $selectedCropId = null;

    /**
     * Farm area size in square meters (m2).
     */
    public float $areaM2 = 1000;

    /**
     * Mount component.
     */
    public function mount(?int $cropId = null, float $area = 1000): void
    {
        $firstCrop = Crop::orderBy('name')->first();
        $this->selectedCropId = $cropId ?: $firstCrop?->id;
        $this->areaM2 = $area > 0 ? $area : 1000;
    }

    /**
     * Fast-fill area size from selected land.
     */
    public function setAreaFromLand(int $landId): void
    {
        $land = Land::where('user_id', Auth::id())->find($landId);

        if ($land) {
            // Ekstrak angka dari string area_size (misal: "1.500 m2" -> 1500)
            $clean = preg_replace('/[^\d]/', '', $land->area_size);
            $parsed = (float) $clean;

            if ($parsed > 0) {
                $this->areaM2 = $parsed;
            }
        }
    }

    /**
     * Calculate agronomic requirements and harvest yield.
     *
     * @return array<string, mixed>|null
     */
    public function getCalculationProperty(): ?array
    {
        $selectedCrop = Crop::find($this->selectedCropId);

        if (! $selectedCrop || $this->areaM2 <= 0) {
            return null;
        }

        $ratio = $this->areaM2 / 10000; // Proporsi per hektar

        return match ($selectedCrop->name) {
            'Cabai Merah' => [
                'crop' => 'Cabai Merah',
                'population' => (int) round(17000 * $ratio),
                'seed_amount' => round(150 * $ratio, 1).' gram benih ('.ceil(150 * $ratio / 10).' sachet)',
                'manure' => round(15 * $ratio, 1).' Ton Pupuk Kompos / Kandang',
                'npk' => round(500 * $ratio, 1).' kg NPK 16-16-16',
                'urea' => round(150 * $ratio, 1).' kg Urea',
                'kcl_sp36' => round(150 * $ratio, 1).' kg SP-36 & 100 kg KCl',
                'estimated_yield' => round(13000 * $ratio, 0).' kg (13 Ton/Ha)',
                'price_per_kg' => 35000,
                'estimated_revenue' => (float) (13000 * $ratio * 35000),
                'spacing' => '60 x 50 cm (Bedengan mulsa plastik)',
            ],
            'Tomat' => [
                'crop' => 'Tomat',
                'population' => (int) round(18000 * $ratio),
                'seed_amount' => round(120 * $ratio, 1).' gram benih berkualitas',
                'manure' => round(20 * $ratio, 1).' Ton Pupuk Kandang Matang',
                'npk' => round(600 * $ratio, 1).' kg NPK Mutiara',
                'urea' => round(100 * $ratio, 1).' kg Urea',
                'kcl_sp36' => round(200 * $ratio, 1).' kg SP-36',
                'estimated_yield' => round(40000 * $ratio, 0).' kg (40 Ton/Ha)',
                'price_per_kg' => 10000,
                'estimated_revenue' => (float) (40000 * $ratio * 10000),
                'spacing' => '60 x 60 cm (Wajib ajir bambu)',
            ],
            'Jagung Manis' => [
                'crop' => 'Jagung Manis',
                'population' => (int) round(53000 * $ratio),
                'seed_amount' => round(12 * $ratio, 1).' kg benih hibrida',
                'manure' => round(5 * $ratio, 1).' Ton Pupuk Kandang',
                'npk' => round(300 * $ratio, 1).' kg NPK Phonska',
                'urea' => round(250 * $ratio, 1).' kg Urea',
                'kcl_sp36' => round(100 * $ratio, 1).' kg SP-36',
                'estimated_yield' => round(12000 * $ratio, 0).' kg tongkol jagung segar',
                'price_per_kg' => 7000,
                'estimated_revenue' => (float) (12000 * $ratio * 7000),
                'spacing' => '75 x 25 cm (1 biji per lubang)',
            ],
            'Padi Sawah' => [
                'crop' => 'Padi Sawah',
                'population' => (int) round(160000 * $ratio).' rumpun (Jajar Legowo)',
                'seed_amount' => round(25 * $ratio, 1).' kg benih padi bersertifikat',
                'manure' => round(2 * $ratio, 1).' Ton Kompos Jerami / Organik',
                'npk' => round(300 * $ratio, 1).' kg NPK 15-15-15',
                'urea' => round(200 * $ratio, 1).' kg Urea',
                'kcl_sp36' => round(100 * $ratio, 1).' kg SP-36',
                'estimated_yield' => round(6500 * $ratio, 0).' kg Gabah Kering Panen (GKP)',
                'price_per_kg' => 7500,
                'estimated_revenue' => (float) (6500 * $ratio * 7500),
                'spacing' => 'Jajar Legowo 2:1 (20 x 10 x 40 cm)',
            ],
            'Bawang Merah' => [
                'crop' => 'Bawang Merah',
                'population' => (int) round(250000 * $ratio).' titik tanam umbi',
                'seed_amount' => round(1000 * $ratio, 0).' kg umbi bibit siap tanam',
                'manure' => round(10 * $ratio, 1).' Ton Pupuk Kandang Halus',
                'npk' => round(400 * $ratio, 1).' kg NPK',
                'urea' => round(150 * $ratio, 1).' kg Urea / ZA',
                'kcl_sp36' => round(150 * $ratio, 1).' kg SP-36',
                'estimated_yield' => round(11000 * $ratio, 0).' kg umbi basah panen',
                'price_per_kg' => 28000,
                'estimated_revenue' => (float) (11000 * $ratio * 28000),
                'spacing' => '15 x 15 cm atau 20 x 15 cm',
            ],
            default => [
                'crop' => $selectedCrop->name,
                'population' => (int) round(20000 * $ratio),
                'seed_amount' => round(2 * $ratio, 1).' kg benih',
                'manure' => round(10 * $ratio, 1).' Ton Kompos',
                'npk' => round(300 * $ratio, 1).' kg NPK',
                'urea' => round(150 * $ratio, 1).' kg Urea',
                'kcl_sp36' => round(100 * $ratio, 1).' kg Pupuk Pelengkap',
                'estimated_yield' => round(15000 * $ratio, 0).' kg hasil panen',
                'price_per_kg' => 12000,
                'estimated_revenue' => (float) (15000 * $ratio * 12000),
                'spacing' => 'Disesuaikan dengan bedengan',
            ],
        };
    }

    /**
     * Render the Livewire component view.
     */
    public function render()
    {
        $crops = Crop::orderBy('name')->get();
        $lands = Auth::check() ? Auth::user()->lands()->orderBy('name')->get() : collect();
        $selectedCrop = Crop::find($this->selectedCropId);

        return view('livewire.farm-calculator', [
            'crops' => $crops,
            'lands' => $lands,
            'selectedCrop' => $selectedCrop,
            'calculation' => $this->calculation,
        ]);
    }
}

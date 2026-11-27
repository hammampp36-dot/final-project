<?php

namespace App\Livewire;

use App\Models\Crop;
use App\Models\Land;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
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
        $requestedCropId = request()->query('crop_id') ? (int) request()->query('crop_id') : $cropId;
        $requestedArea = request()->query('area') ? (float) request()->query('area') : $area;

        $firstCrop = Crop::orderBy('name')->first();
        $this->selectedCropId = $requestedCropId ?: $firstCrop?->id;
        $this->areaM2 = $requestedArea > 0 ? $requestedArea : 1000;
    }

    /**
     * Fast-fill area size from selected land.
     */
    public function setAreaFromLand(int $landId): void
    {
        $land = Land::where('user_id', Auth::id())->find($landId);

        if ($land) {
            $clean = preg_replace('/[^\d]/', '', (string) $land->area_size);
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

        $multiplier = $this->areaM2 / 1000.0;

        $seedAmount = match ($selectedCrop->name) {
            'Cabai Merah' => number_format((int) round(1700 * $multiplier), 0, ',', '.').' Batang',
            'Tomat Mawar' => number_format((int) round(2000 * $multiplier), 0, ',', '.').' Batang',
            'Jagung Manis' => number_format(round(7 * $multiplier, 1), 1, ',', '.').' Kg',
            'Padi Sawah' => number_format(round(25 * $multiplier, 1), 1, ',', '.').' Kg',
            'Bawang Merah' => number_format(round(100 * $multiplier, 1), 1, ',', '.').' Kg Umbi',
            'Mentimun' => number_format(round(1 * $multiplier, 1), 1, ',', '.').' Kg',
            default => number_format((int) round(1500 * $multiplier), 0, ',', '.').' Bibit / Benih',
        };

        $fertilizerUrea = round(20 * $multiplier, 1);
        $fertilizerNpk = round(30 * $multiplier, 1);
        $fertilizerOrganik = round(150 * $multiplier, 1);

        $harvestYield = match ($selectedCrop->name) {
            'Cabai Merah' => number_format((int) round(1200 * $multiplier), 0, ',', '.').' Kg Cabai Segar',
            'Tomat Mawar' => number_format((int) round(2500 * $multiplier), 0, ',', '.').' Kg Buah',
            'Jagung Manis' => number_format((int) round(1800 * $multiplier), 0, ',', '.').' Kg Tongkol',
            'Padi Sawah' => number_format((int) round(650 * $multiplier), 0, ',', '.').' Kg Gabah Kering',
            'Bawang Merah' => number_format((int) round(1000 * $multiplier), 0, ',', '.').' Kg Bawang Segar',
            'Mentimun' => number_format((int) round(2000 * $multiplier), 0, ',', '.').' Kg',
            default => number_format((int) round(1000 * $multiplier), 0, ',', '.').' Kg Hasil Panen',
        };

        return [
            'crop' => $selectedCrop,
            'area' => $this->areaM2,
            'seeds' => $seedAmount,
            'urea_kg' => $fertilizerUrea,
            'npk_kg' => $fertilizerNpk,
            'organik_kg' => $fertilizerOrganik,
            'harvest_yield' => $harvestYield,
            'harvest_days' => $selectedCrop->estimated_harvest_days,
        ];
    }

    public function render()
    {
        $crops = Crop::orderBy('name')->get();
        $userLands = Auth::check() ? Land::where('user_id', Auth::id())->get() : collect();

        return view('livewire.farm-calculator', [
            'crops' => $crops,
            'userLands' => $userLands,
            'calculation' => $this->calculation,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;

class CropSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $crops = [
            [
                'name' => 'Cabai Merah',
                'category' => 'Sayuran Hortikultura',
                'planting_guide' => 'Penyiraman rutin setiap hari. Pemupukan susulan pada hari ke-14 dan ke-30. Pemantauan hama trip dan antraknosa secara berkala.',
                'estimated_harvest_days' => 90,
            ],
            [
                'name' => 'Tomat',
                'category' => 'Sayuran Buah',
                'planting_guide' => 'Penyiraman teratur tanpa menggenangi bedengan. Pasang ajir/lanjaran pada hari ke-14. Pemupukan susulan hari ke-15 dan ke-35.',
                'estimated_harvest_days' => 70,
            ],
            [
                'name' => 'Jagung Manis',
                'category' => 'Palawija',
                'planting_guide' => 'Jarak tanam 75 x 25 cm. Penyiangan gulma pada hari ke-15. Pemupukan NPK/Urea pada hari ke-21 dan hari ke-40.',
                'estimated_harvest_days' => 75,
            ],
            [
                'name' => 'Padi Sawah',
                'category' => 'Pangan Pokok',
                'planting_guide' => 'Pengairan macak-macak. Pemupukan dasar saat tanam, susulan pertama hari ke-14, dan susulan kedua hari ke-35.',
                'estimated_harvest_days' => 115,
            ],
            [
                'name' => 'Bawang Merah',
                'category' => 'Sayuran Umbi',
                'planting_guide' => 'Drainase tanah harus prima. Penyiraman pagi dan sore pada 10 hari pertama, kurangi intensitas saat umbi mulai terbentuk.',
                'estimated_harvest_days' => 60,
            ],
            [
                'name' => 'Mentimun',
                'category' => 'Sayuran Buah',
                'planting_guide' => 'Pemasangan lanjaran pada minggu ke-2. Penyiraman intensif saat berbunga dan berbuah. Pemupukan organik cair tiap minggu.',
                'estimated_harvest_days' => 45,
            ],
        ];

        foreach ($crops as $crop) {
            Crop::updateOrCreate(
                ['name' => $crop['name']],
                $crop
            );
        }
    }
}

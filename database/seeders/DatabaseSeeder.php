<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CropSeeder::class);

        // Administrator Agrismart
        User::updateOrCreate(
            ['email' => 'admin@agrismart.id'],
            [
                'name' => 'Administrator Agrismart',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Petani Mitra (User)
        User::updateOrCreate(
            ['email' => 'petani@agrismart.id'],
            [
                'name' => 'Petani Pintar',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );
    }
}

<?php

use App\Models\Crop;
use App\Models\Land;
use App\Models\Schedule;
use App\Models\User;

test('authenticated user can view lands and create a new land with qr code', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('lands.index'));
    $response->assertStatus(200);

    $response = $this->actingAs($user)->post(route('lands.store'), [
        'name' => 'Lahan Blok C - Jagung',
        'area_size' => '2.000 m²',
        'location' => 'Desa Sukamaju No. 12',
    ]);

    $land = Land::where('name', 'Lahan Blok C - Jagung')->first();
    expect($land)->not->toBeNull();
    expect($land->user_id)->toBe($user->id);
    expect($land->qr_code_text)->toContain('AGRI-LAND');

    $response->assertRedirect(route('lands.show', $land));

    $showResponse = $this->actingAs($user)->get(route('lands.show', $land));
    $showResponse->assertStatus(200);
    $showResponse->assertSee('Lahan Blok C - Jagung');
});

test('user cannot access or delete other user land', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $land = Land::create([
        'user_id' => $user1->id,
        'name' => 'Lahan Rahasia',
        'area_size' => '500 m²',
        'location' => 'Bandung',
        'qr_code_text' => 'SECRET',
    ]);

    $response = $this->actingAs($user2)->get(route('lands.show', $land));
    $response->assertStatus(403);
});

test('user can generate smart farming schedule from crop catalog', function () {
    $user = User::factory()->create();
    $crop = Crop::firstOrCreate(
        ['name' => 'Cabai Merah Test'],
        ['category' => 'Sayuran', 'estimated_harvest_days' => 90, 'planting_guide' => 'Siram teratur']
    );

    $response = $this->actingAs($user)->post(route('schedules.store'), [
        'crop_id' => $crop->id,
        'planting_date' => now()->format('Y-m-d'),
    ]);

    $response->assertRedirect(route('schedules.index'));

    $schedules = Schedule::where('user_id', $user->id)->where('crop_id', $crop->id)->get();
    expect($schedules->count())->toBeGreaterThanOrEqual(4);

    $schedule = $schedules->first();
    expect($schedule->status)->toBe('pending');

    // Toggle status to completed
    $toggleResponse = $this->actingAs($user)->patch(route('schedules.toggle', $schedule));
    $toggleResponse->assertSessionHas('success');
    expect($schedule->fresh()->status)->toBe('completed');
});

test('user can record transactions and see net profit', function () {
    $user = User::factory()->create();

    // Create income
    $this->actingAs($user)->post(route('transactions.store'), [
        'type' => 'income',
        'category' => 'Penjualan Hasil Panen',
        'amount' => 5000000,
        'transaction_date' => now()->format('Y-m-d'),
        'description' => 'Panen cabai 200 kg',
    ]);

    // Create expense
    $this->actingAs($user)->post(route('transactions.store'), [
        'type' => 'expense',
        'category' => 'Pembelian Pupuk Organik & Kimia',
        'amount' => 1500000,
        'transaction_date' => now()->format('Y-m-d'),
        'description' => 'Beli pupuk NPK',
    ]);

    $response = $this->actingAs($user)->get(route('transactions.index'));
    $response->assertStatus(200);
    $response->assertSee('5.000.000');
    $response->assertSee('1.500.000');
    $response->assertSee('3.500.000'); // Net profit
});

test('dashboard displays consolidated metrics for authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Beranda Agrismart');
});

test('user can download land qr code svg and scan/search land', function () {
    $user = User::factory()->create();
    $land = Land::create([
        'user_id' => $user->id,
        'name' => 'Lahan Uji Scan',
        'area_size' => '1.000 m²',
        'location' => 'Lembang',
        'qr_code_text' => 'AGRI-LAND-SEARCH-CODE',
    ]);

    $downloadResponse = $this->actingAs($user)->get(route('lands.qr-download', $land));
    $downloadResponse->assertStatus(200);
    $downloadResponse->assertHeader('Content-Type', 'image/svg+xml');

    $scanResponse = $this->actingAs($user)->get(route('lands.scan', ['code' => 'SEARCH-CODE']));
    $scanResponse->assertStatus(200);
    $scanResponse->assertSee('Lahan Uji Scan');
});

test('user can export transactions to csv', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('transactions.export'));
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});

test('authenticated user can view crops catalog', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('crops.index'));
    $response->assertStatus(200);
    $response->assertSee('Katalog Tanaman');
});

test('authenticated user can use farm calculator', function () {
    $user = User::factory()->create();
    $crop = Crop::firstOrCreate(
        ['name' => 'Cabai Merah'],
        ['category' => 'Sayuran', 'estimated_harvest_days' => 90, 'planting_guide' => 'Siram teratur']
    );

    $response = $this->actingAs($user)->get(route('calculator.index', [
        'crop_id' => $crop->id,
        'area' => 2000,
    ]));

    $response->assertStatus(200);
    $response->assertSee('Hasil Kalkulasi Standar Agronomi');
    $response->assertSee('Cabai Merah');
});

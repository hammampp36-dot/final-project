<?php

use App\Livewire\CropManager;
use App\Livewire\FarmCalculator;
use App\Livewire\FarmerSearch;
use App\Livewire\LandManager;
use App\Livewire\ScheduleManager;
use App\Livewire\TransactionManager;
use App\Models\Crop;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

test('farm calculator livewire component calculates real-time', function () {
    $user = User::factory()->create();
    $crop = Crop::firstOrCreate(
        ['name' => 'Cabai Merah'],
        ['category' => 'Sayuran', 'estimated_harvest_days' => 90]
    );

    Livewire::actingAs($user)
        ->test(FarmCalculator::class, ['cropId' => $crop->id, 'area' => 1000])
        ->assertSee('Cabai Merah')
        ->assertSee('1.700 Batang')
        ->set('areaM2', 2000)
        ->assertSee('3.400 Batang');
});

test('farmer search livewire component filters in real-time', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->user()->create(['name' => 'Budi Santoso Petani', 'email' => 'budi@agrismart.id']);
    User::factory()->user()->create(['name' => 'Siti Aminah Petani', 'email' => 'siti@agrismart.id']);

    Livewire::actingAs($admin)
        ->test(FarmerSearch::class)
        ->assertSee('Budi Santoso Petani')
        ->assertSee('Siti Aminah Petani')
        ->set('search', 'Budi')
        ->assertSee('Budi Santoso Petani')
        ->assertDontSee('Siti Aminah Petani');
});

test('land manager livewire registers land and auto generates qr code', function () {
    $user = User::factory()->user()->create();

    Livewire::actingAs($user)
        ->test(LandManager::class)
        ->set('name', 'Petak Timur Blok C')
        ->set('area_size', 2500)
        ->set('location', 'Desa Sindangasih')
        ->call('saveLand')
        ->assertHasNoErrors()
        ->assertSee('Petak Timur Blok C');

    $this->assertDatabaseHas('lands', [
        'user_id' => $user->id,
        'name' => 'Petak Timur Blok C',
        'area_size' => 2500,
    ]);
});

test('schedule manager livewire generates schedules and toggles status', function () {
    $user = User::factory()->user()->create();
    $crop = Crop::firstOrCreate(
        ['name' => 'Tomat Mawar'],
        ['category' => 'Sayuran', 'estimated_harvest_days' => 70]
    );

    $test = Livewire::actingAs($user)
        ->test(ScheduleManager::class)
        ->set('crop_id', $crop->id)
        ->set('planting_date', now()->format('Y-m-d'))
        ->call('generateSchedules')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('schedules', [
        'user_id' => $user->id,
        'crop_id' => $crop->id,
    ]);

    $schedule = Schedule::where('user_id', $user->id)->first();
    expect($schedule->status)->toBe('pending');

    $test->call('toggleStatus', $schedule->id);
    expect($schedule->fresh()->status)->toBe('completed');
});

test('transaction manager livewire records income expense and calculates net profit', function () {
    $user = User::factory()->user()->create();

    Livewire::actingAs($user)
        ->test(TransactionManager::class)
        ->set('type', 'income')
        ->set('category', 'Penjualan Hasil Panen')
        ->set('amount', 5000000)
        ->set('transaction_date', now()->format('Y-m-d'))
        ->call('saveTransaction')
        ->assertHasNoErrors()
        ->assertSee('Rp 5.000.000');

    $this->assertDatabaseHas('transactions', [
        'user_id' => $user->id,
        'type' => 'income',
        'amount' => 5000000,
    ]);
});

test('transaction manager livewire prevents regular user from deleting transaction', function () {
    $user = User::factory()->user()->create();
    $tx = Transaction::create([
        'user_id' => $user->id,
        'type' => 'income',
        'category' => 'Penjualan Hasil Panen',
        'amount' => 1000000,
        'transaction_date' => now()->format('Y-m-d'),
    ]);

    Livewire::actingAs($user)
        ->test(TransactionManager::class)
        ->call('deleteTransaction', $tx->id)
        ->assertForbidden();
});

test('crop manager livewire allows admin to add and manage crops', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(CropManager::class, ['isAdmin' => true])
        ->set('name', 'Melon Golden')
        ->set('category', 'Buah')
        ->set('estimated_harvest_days', 65)
        ->set('planting_guide', 'Tanam di bedengan dengan mulsa plastik perak.')
        ->call('saveCrop')
        ->assertHasNoErrors()
        ->assertSee('Melon Golden');

    $this->assertDatabaseHas('crops', [
        'name' => 'Melon Golden',
        'estimated_harvest_days' => 65,
    ]);
});

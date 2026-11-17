<?php

use App\Models\Crop;
use App\Models\Transaction;
use App\Models\User;

test('admin logging in is redirected to admin dashboard', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('dashboard'));
    $response->assertRedirect(route('admin.dashboard'));

    $adminDashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
    $adminDashboardResponse->assertStatus(200);
    $adminDashboardResponse->assertSee('Dashboard Eksekutif Pertanian Agrismart');
    $adminDashboardResponse->assertSee('10 Fitur Otoritas Administrator');
});

test('regular farmer sees user dashboard and cannot access admin routes', function () {
    $farmer = User::factory()->user()->create();

    // User dashboard
    $response = $this->actingAs($farmer)->get(route('dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Beranda Agrismart');

    // Admin routes are blocked for regular farmer
    $this->actingAs($farmer)->get(route('admin.dashboard'))->assertStatus(403);
    $this->actingAs($farmer)->get(route('admin.users.index'))->assertStatus(403);
    $this->actingAs($farmer)->get(route('admin.crops.create'))->assertStatus(403);
    $this->actingAs($farmer)->get(route('admin.transactions.index'))->assertStatus(403);
});

test('regular farmer cannot delete transactions but admin can', function () {
    $farmer = User::factory()->user()->create();
    $admin = User::factory()->admin()->create();

    $transaction = Transaction::create([
        'user_id' => $farmer->id,
        'type' => 'income',
        'category' => 'Penjualan Hasil Panen',
        'amount' => 1000000,
        'transaction_date' => now()->format('Y-m-d'),
        'description' => 'Penjualan tomat 100 kg',
    ]);

    // Farmer tries to delete -> 403 Forbidden
    $this->actingAs($farmer)
        ->delete(route('transactions.destroy', $transaction))
        ->assertStatus(403);

    expect(Transaction::find($transaction->id))->not->toBeNull();

    // Admin deletes via admin transaction destroy -> 302 Success
    $this->actingAs($admin)
        ->delete(route('admin.transactions.destroy', $transaction))
        ->assertRedirect(route('admin.transactions.index'));

    expect(Transaction::find($transaction->id))->toBeNull();
});

test('admin can manage master crops catalog CRUD', function () {
    $admin = User::factory()->admin()->create();

    // Index
    $this->actingAs($admin)->get(route('admin.crops.index'))->assertStatus(200);

    // Create
    $response = $this->actingAs($admin)->post(route('admin.crops.store'), [
        'name' => 'Semangka Inul Gold',
        'category' => 'Buah Semusim',
        'estimated_harvest_days' => 65,
        'planting_guide' => 'Perawatan intensif dan mulsa perak.',
    ]);
    $response->assertRedirect(route('admin.crops.index'));

    $crop = Crop::where('name', 'Semangka Inul Gold')->first();
    expect($crop)->not->toBeNull();

    // Update
    $this->actingAs($admin)->put(route('admin.crops.update', $crop), [
        'name' => 'Semangka Inul Gold Super',
        'category' => 'Buah Semusim',
        'estimated_harvest_days' => 60,
        'planting_guide' => 'Perawatan diperbarui.',
    ])->assertRedirect(route('admin.crops.index'));

    expect($crop->fresh()->name)->toBe('Semangka Inul Gold Super');

    // Delete
    $this->actingAs($admin)->delete(route('admin.crops.destroy', $crop))
        ->assertRedirect(route('admin.crops.index'));

    expect(Crop::find($crop->id))->toBeNull();
});

test('admin can view user list and global transactions export', function () {
    $admin = User::factory()->admin()->create();
    $farmer = User::factory()->user()->create();

    $this->actingAs($admin)->get(route('admin.users.index'))->assertStatus(200)->assertSee($farmer->name);
    $this->actingAs($admin)->get(route('admin.users.show', $farmer))->assertStatus(200);

    $exportResponse = $this->actingAs($admin)->get(route('admin.transactions.export'));
    $exportResponse->assertStatus(200);
    $exportResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StockLpg;
use App\Models\StockBatch;
use App\Models\StockOutflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed some base data
        StockLpg::create([
            'tabung_type' => '3kg',
            'stok_isi' => 50,
            'stok_kosong' => 20,
            'safety_stock' => 10,
        ]);
    }

    public function test_admin_can_access_stock_index_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.stock.index'));

        $response->assertStatus(200);
        $response->assertSee('Stok Pangkalan LPG');
        $response->assertSee('3kg');
    }

    public function test_admin_can_clear_outflow_history_for_specific_cylinder_type(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $batch = StockBatch::create([
            'tabung_type' => '3kg',
            'quantity_in' => 50,
            'quantity_remaining' => 40,
            'received_date' => now()->subDays(2),
            'created_by' => $admin->id,
        ]);

        StockOutflow::create([
            'stock_batch_id' => $batch->id,
            'tabung_type' => '3kg',
            'quantity' => 10,
            'transaction_date' => now(),
            'source' => 'penjualan_langsung',
        ]);

        // Access index page and see the outflow & button
        $response = $this->actingAs($admin)->get(route('admin.stock.index'));
        $response->assertSee('Hapus Riwayat');

        // Post to clear outflow history
        $clearResponse = $this->actingAs($admin)
            ->post(route('admin.stock.outflow.clear', '3kg'));

        $clearResponse->assertRedirect(route('admin.stock.index'));
        $clearResponse->assertSessionHas('success');

        // Assert outflow deleted
        $this->assertEquals(0, StockOutflow::where('tabung_type', '3kg')->count());

        // Verify index page no longer shows "Hapus Riwayat" and shows "Belum ada pengeluaran"
        $finalResponse = $this->actingAs($admin)->get(route('admin.stock.index'));
        $finalResponse->assertSee('Belum ada pengeluaran');
        $finalResponse->assertDontSee('Hapus Riwayat');
    }

    public function test_admin_sees_locked_status_for_consumed_batch(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create a consumed batch (quantity_remaining = 0)
        $batch = StockBatch::create([
            'tabung_type' => '3kg',
            'quantity_in' => 50,
            'quantity_remaining' => 0,
            'received_date' => now()->subDays(2),
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.stock.index'));
        $response->assertStatus(200);

        // The batch should be labeled as Terkunci, and should not show Edit/Delete links
        $response->assertSee('Terkunci');
        $response->assertDontSee(route('admin.stock.batch.edit', $batch));
    }

    public function test_admin_can_update_stock_with_valid_max_capacity(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $stock = StockLpg::first();

        $response = $this->actingAs($admin)
            ->put(route('admin.stock.update', $stock), [
                'max_stock' => 150,
                'stok_isi' => 100,
                'stok_kosong' => 40,
                'safety_stock' => 15,
            ]);

        $response->assertRedirect(route('admin.stock.index'));
        $this->assertDatabaseHas('stock_lpg', [
            'tabung_type' => '3kg',
            'max_stock' => 150,
            'stok_isi' => 100,
            'stok_kosong' => 40,
            'safety_stock' => 15,
        ]);
    }

    public function test_admin_fails_to_update_stock_if_stok_isi_exceeds_max_stock(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $stock = StockLpg::first();

        $response = $this->actingAs($admin)
            ->from(route('admin.stock.edit', $stock))
            ->put(route('admin.stock.update', $stock), [
                'max_stock' => 80,
                'stok_isi' => 85,
                'stok_kosong' => 20,
                'safety_stock' => 15,
            ]);

        $response->assertRedirect(route('admin.stock.edit', $stock));
        $response->assertSessionHas('error');
    }

    public function test_admin_fails_to_update_stock_if_stok_kosong_exceeds_max_stock(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $stock = StockLpg::first();

        $response = $this->actingAs($admin)
            ->from(route('admin.stock.edit', $stock))
            ->put(route('admin.stock.update', $stock), [
                'max_stock' => 80,
                'stok_isi' => 50,
                'stok_kosong' => 81,
                'safety_stock' => 15,
            ]);

        $response->assertRedirect(route('admin.stock.edit', $stock));
        $response->assertSessionHas('error');
    }

    public function test_admin_stock_update_auto_adjusts_stok_kosong_on_capacity_increase(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $stock = StockLpg::first(); // max_stock default = 120, stok_kosong = 20

        $response = $this->actingAs($admin)
            ->put(route('admin.stock.update', $stock), [
                'max_stock' => 125, // increase by 5
                'stok_isi' => 50,
                'stok_kosong' => 20, // submitted unchanged
                'safety_stock' => 10,
            ]);

        $response->assertRedirect(route('admin.stock.index'));
        $this->assertDatabaseHas('stock_lpg', [
            'tabung_type' => '3kg',
            'max_stock' => 125,
            'stok_kosong' => 25, // automatically increased by 5
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\StockLpg;
use App\Models\User;
use App\Models\PenjualanLangsung;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenjualanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed data
        StockLpg::create([
            'tabung_type'   => '3kg',
            'initial_stock' => 10,
            'current_stock' => 10,
            'stock_in'      => 10,
            'stock_out'     => 0,
            'safety_stock'  => 2,
            'stok_isi'      => 10,
            'stok_kosong'   => 0,
        ]);
    }

    public function test_admin_can_access_penjualan_create_page_and_see_fields(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        Customer::create([
            'name' => 'Budi Santoso',
            'ktp' => '3201010101010001',
            'phone' => '081111111111',
            'address' => 'Jl. Mawar No. 1',
            'category' => 'rumah_tangga',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.penjualan.create'));

        $response->assertStatus(200);

        // Assert customer category dropdown is present
        $response->assertSee('id="customer_category"', false);
        $response->assertSee('value="rumah_tangga"', false);
        $response->assertSee('value="usaha_mikro"', false);
        $response->assertSee('value="pengecer"', false);

        // Assert customer select element is present
        $response->assertSee('class="w-full select2-customer"', false);
    }

    public function test_check_customer_quota_endpoint(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'name' => 'Budi Santoso',
            'ktp' => '3201010101010001',
            'phone' => '081111111111',
            'address' => 'Jl. Mawar No. 1',
            'category' => 'rumah_tangga',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.penjualan.check-quota', [
                'customer_id' => $customer->id,
                'tabung_type' => '3kg',
            ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'customer' => [
                'name',
                'ktp',
                'category_label',
                'address',
            ],
            'max_quota',
            'used_quota',
            'remaining_quota',
            'status',
            'color',
            'last_transaction',
        ]);
    }

    public function test_admin_can_access_penjualan_index_page_and_see_stats_and_filters(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'name' => 'Budi Santoso',
            'ktp' => '3201010101010001',
            'phone' => '081111111111',
            'address' => 'Jl. Mawar No. 1',
            'category' => 'rumah_tangga',
            'is_active' => true,
        ]);

        // Create dummy sale
        PenjualanLangsung::create([
            'user_id' => $admin->id,
            'customer_id' => $customer->id,
            'tabung_type' => '3kg',
            'quantity' => 2,
            'customer_type' => 'rumah_tangga',
            'nama_pembeli' => 'Budi Santoso',
            'no_ktp' => '3201010101010001',
            'transaction_date' => today(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.penjualan.index'));

        $response->assertStatus(200);
        
        // Assert dashboard statistics
        $response->assertSee('Terjual Hari Ini', false);
        $response->assertSee('Terjual Bulan Ini', false);
        $response->assertSee('Total Transaksi', false);
        
        // Assert filter panel fields are present
        $response->assertSee('id="search"', false);
        $response->assertSee('id="category"', false);
        $response->assertSee('id="tabung_type"', false);
        $response->assertSee('id="month"', false);
        $response->assertSee('id="year"', false);
        
        // Assert sale item is listed
        $response->assertSee('Budi Santoso');
        $response->assertSee('3201010101010001');
    }

    public function test_admin_can_filter_penjualan_index_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $c1 = Customer::create(['name' => 'Budi Santoso', 'ktp' => '3201010101010001', 'category' => 'rumah_tangga', 'is_active' => true]);
        $c2 = Customer::create(['name' => 'Toko Bu Ani', 'ktp' => '3201010101010004', 'category' => 'pengecer', 'is_active' => true]);

        PenjualanLangsung::create([
            'user_id' => $admin->id, 'customer_id' => $c1->id, 'tabung_type' => '3kg', 'quantity' => 1,
            'customer_type' => 'rumah_tangga', 'nama_pembeli' => 'Budi Santoso', 'no_ktp' => '3201010101010001', 'transaction_date' => today()
        ]);

        PenjualanLangsung::create([
            'user_id' => $admin->id, 'customer_id' => $c2->id, 'tabung_type' => '12kg', 'quantity' => 5,
            'customer_type' => 'pengecer', 'nama_pembeli' => 'Toko Bu Ani', 'no_ktp' => '3201010101010004', 'transaction_date' => today()
        ]);

        // Filter by category: pengecer
        $response = $this->actingAs($admin)
            ->get(route('admin.penjualan.index', ['category' => 'pengecer']));
        $response->assertStatus(200);
        $response->assertSee('Toko Bu Ani');
        $response->assertDontSee('Budi Santoso');

        // Filter by search name
        $response = $this->actingAs($admin)
            ->get(route('admin.penjualan.index', ['search' => 'Budi']));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Toko Bu Ani');

        // Filter by tabung type
        $response = $this->actingAs($admin)
            ->get(route('admin.penjualan.index', ['tabung_type' => '12kg']));
        $response->assertStatus(200);
        $response->assertSee('Toko Bu Ani');
        $response->assertDontSee('Budi Santoso');
    }

    public function test_admin_sees_out_of_stock_items_on_create_page_with_warning_labels(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create an out-of-stock type
        StockLpg::create([
            'tabung_type'   => '5kg',
            'initial_stock' => 0,
            'current_stock' => 0,
            'stock_in'      => 0,
            'stock_out'     => 0,
            'safety_stock'  => 1,
            'stok_isi'      => 0,
            'stok_kosong'   => 0,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.penjualan.create'));

        $response->assertStatus(200);
        
        // Assert out-of-stock card is displayed with warning text
        $response->assertSee('Tabung 5kg');
        $response->assertSee('Stok Habis!');

        // Assert select option is disabled
        $response->assertSee('disabled', false);
        $response->assertSee('Tabung 5kg (Sisa: 0 - Stok Habis)');
    }

    public function test_check_quota_for_konsumen_umum_returns_unlimited(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'name' => 'John Doe',
            'ktp' => '3201010101010009',
            'phone' => '081234567890',
            'address' => 'Jl. Kenanga No. 5',
            'category' => 'konsumen_umum',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.penjualan.check-quota', [
                'customer_id' => $customer->id,
                'tabung_type' => '12kg',
            ]));

        $response->assertStatus(200);
        $response->assertJson([
            'max_quota' => 999,
            'remaining_quota' => 999,
            'status' => 'Aman',
            'color' => 'text-green-600',
        ]);
    }
}

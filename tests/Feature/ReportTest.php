<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\SubPangkalan;
use App\Models\User;
use App\Models\PenjualanLangsung;
use App\Models\StockLpg;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_admin_can_access_reports_page_and_see_basic_options(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Penjualan LPG');
        $response->assertDontSee('Laporan Stok LPG');
        $response->assertSee('Laporan Pelanggan');
        $response->assertSee('Sub Pangkalan (Pengecer)'); // dropdown label
    }

    public function test_admin_can_filter_sales_report_by_sub_pangkalan(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Sub Pangkalan A
        $userA = User::factory()->create(['role' => 'sub_pangkalan']);
        $subA = SubPangkalan::create([
            'user_id' => $userA->id,
            'name' => 'Pengecer A',
            'code' => 'SPA001',
            'ktp' => '1111111111111111',
            'phone' => '08111',
            'address' => 'Addr A',
            'stok_isi' => 0,
            'stok_kosong' => 0,
            'is_active' => true,
        ]);

        // Create Sub Pangkalan B
        $userB = User::factory()->create(['role' => 'sub_pangkalan']);
        $subB = SubPangkalan::create([
            'user_id' => $userB->id,
            'name' => 'Pengecer B',
            'code' => 'SPB002',
            'ktp' => '2222222222222222',
            'phone' => '08222',
            'address' => 'Addr B',
            'stok_isi' => 0,
            'stok_kosong' => 0,
            'is_active' => true,
        ]);

        // Direct sales to Pengecer A
        PenjualanLangsung::create([
            'user_id' => $admin->id,
            'customer_id' => null,
            'tabung_type' => '3kg',
            'quantity' => 5,
            'customer_type' => 'pengecer',
            'nama_pembeli' => 'Pengecer A',
            'no_ktp' => '1111111111111111',
            'transaction_date' => now(),
            'notes' => 'Sale to A',
        ]);

        // Direct sales to Pengecer B
        PenjualanLangsung::create([
            'user_id' => $admin->id,
            'customer_id' => null,
            'tabung_type' => '3kg',
            'quantity' => 8,
            'customer_type' => 'pengecer',
            'nama_pembeli' => 'Pengecer B',
            'no_ktp' => '2222222222222222',
            'transaction_date' => now(),
            'notes' => 'Sale to B',
        ]);

        // Get report with sub_pangkalan_id filter targeting Pengecer A
        $response = $this->actingAs($admin)
            ->get(route('admin.reports', [
                'report_type' => 'penjualan',
                'sub_pangkalan_id' => $subA->id,
            ]));

        $response->assertStatus(200);
        
        $records = $response->original->getData()['records'];
        $this->assertCount(1, $records);
        $this->assertEquals('Pengecer A', $records->first()->nama_pembeli);
    }

    public function test_stock_report_does_not_contain_retur_kosong(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports', [
                'report_type' => 'stok',
            ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Stok LPG');
        $response->assertDontSee('Retur Kosong'); // Should be removed from stock description
    }
}

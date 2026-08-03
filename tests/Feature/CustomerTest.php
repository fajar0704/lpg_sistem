<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_customers_index_page_and_see_stats_and_filters(): void
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
            ->get(route('admin.customers.index'));

        $response->assertStatus(200);

        // Assert statistics headers
        $response->assertSee('Total Pelanggan', false);
        $response->assertSee('Rumah Tangga', false);
        $response->assertSee('Usaha Mikro (UMKM)', false);
        $response->assertSee('Konsumen Umum', false);

        // Assert filter elements
        $response->assertSee('id="search"', false);
        $response->assertSee('id="category"', false);
        $response->assertSee('id="btn-reset-customer"', false);

        // Assert customer is listed
        $response->assertSee('Budi Santoso');
        $response->assertSee('3201010101010001');
    }

    public function test_admin_can_filter_customers_by_search_category_and_status(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create testing customers
        Customer::create([
            'name' => 'Budi Santoso', 'ktp' => '3201010101010001', 'phone' => '081111111111',
            'address' => 'Jl. Mawar No. 1', 'category' => 'rumah_tangga', 'is_active' => true
        ]);
        Customer::create([
            'name' => 'Warung Pak Eko', 'ktp' => '3201010101010002', 'phone' => '081111111112',
            'address' => 'Jl. Melati No. 2', 'category' => 'usaha_mikro', 'is_active' => false
        ]);

        // Test search
        $response = $this->actingAs($admin)
            ->get(route('admin.customers.index', ['search' => 'Budi']));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Warung Pak Eko');

        // Test category filter
        $response = $this->actingAs($admin)
            ->get(route('admin.customers.index', ['category' => 'usaha_mikro']));
        $response->assertStatus(200);
        $response->assertSee('Warung Pak Eko');
        $response->assertDontSee('Budi Santoso');

        // Test status active filter
        $response = $this->actingAs($admin)
            ->get(route('admin.customers.index', ['status' => 'active']));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Warung Pak Eko');

        // Test status inactive filter
        $response = $this->actingAs($admin)
            ->get(route('admin.customers.index', ['status' => 'inactive']));
        $response->assertStatus(200);
        $response->assertSee('Warung Pak Eko');
        $response->assertDontSee('Budi Santoso');
    }

    public function test_admin_can_access_customers_create_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.customers.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Pelanggan Baru');
        $response->assertSee('Rekam KTP');
    }

    public function test_admin_can_access_customer_show_page_and_see_details(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'name' => 'John Doe',
            'ktp' => '1234567890123456',
            'phone' => '0812345678',
            'address' => 'Jl. Merdeka No. 10',
            'category' => 'rumah_tangga',
            'is_active' => true,
        ]);

        // Access detail page
        $response = $this->actingAs($admin)
            ->get(route('admin.customers.show', $customer));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('1234567890123456');
        $response->assertSee('Jl. Merdeka No. 10');
    }

    public function test_admin_can_store_and_update_customer_with_konsumen_umum_category(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 1. Store Customer
        $responseStore = $this->actingAs($admin)
            ->post(route('admin.customers.store'), [
                'name' => 'General Customer Test',
                'ktp' => '9876543210123456',
                'phone' => '0899998888',
                'address' => 'Jl. Raya No. 5',
                'category' => 'konsumen_umum',
            ]);

        $responseStore->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('customers', [
            'name' => 'General Customer Test',
            'category' => 'konsumen_umum',
        ]);

        $customer = Customer::where('ktp', '9876543210123456')->first();

        // 2. Update Customer
        $responseUpdate = $this->actingAs($admin)
            ->put(route('admin.customers.update', $customer), [
                'name' => 'General Customer Updated',
                'ktp' => '9876543210123456',
                'phone' => '0899998888',
                'address' => 'Jl. Raya No. 5',
                'category' => 'konsumen_umum',
            ]);

        $responseUpdate->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'General Customer Updated',
            'category' => 'konsumen_umum',
        ]);
    }

    public function test_admin_can_store_customer_with_webcam_photo(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Fake storage disk
        \Illuminate\Support\Facades\Storage::fake('public');

        $base64Photo = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $response = $this->actingAs($admin)
            ->post(route('admin.customers.store'), [
                'name' => 'Webcam Customer',
                'ktp' => '1122334455667788',
                'phone' => '081234123412',
                'address' => 'Test Address',
                'category' => 'rumah_tangga',
                'photo' => $base64Photo,
            ]);

        $response->assertRedirect(route('admin.customers.index'));
        
        $customer = Customer::where('ktp', '1122334455667788')->first();
        $this->assertNotNull($customer->photo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($customer->photo);
    }
}

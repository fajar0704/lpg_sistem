<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SubPangkalan;
use App\Models\Customer;
use App\Models\StockLpg;
use App\Models\SubPangkalanTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubPangkalanCustomerTest extends TestCase
{
    use RefreshDatabase;

    private function createSubPangkalanUser(): User
    {
        $subPangkalan = SubPangkalan::create([
            'name' => 'Sub Pangkalan Test',
            'code' => 'SPT001',
            'address' => 'Jl. Test No. 1',
            'phone' => '081234567890',
            'is_active' => true,
            'stok_isi' => 50,
            'stok_kosong' => 10,
            'ktp' => '1234567890123456',
            'nama_ktp' => 'Test Owner',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat_ktp' => 'Jl. KTP No. 1',
        ]);

        StockLpg::firstOrCreate(['tabung_type' => '3kg'], ['name' => 'LPG 3kg']);

        return User::factory()->create([
            'role' => 'sub_pangkalan',
            'sub_pangkalan_id' => $subPangkalan->id,
            'is_active' => true,
        ]);
    }

    public function test_sub_pangkalan_user_can_access_customers_index(): void
    {
        $user = $this->createSubPangkalanUser();

        // Create a test customer belonging to this sub pangkalan
        Customer::create([
            'name' => 'John Doe Customer',
            'ktp' => '9876543210123456',
            'phone' => '087654321',
            'address' => 'Jl. Customer No. 2',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        // Create another test customer belonging to a DIFFERENT sub pangkalan
        $otherSubPangkalan = SubPangkalan::create([
            'name' => 'Other Sub Pangkalan',
            'code' => 'SPT002',
            'address' => 'Jl. Test No. 2',
            'phone' => '081234567891',
            'is_active' => true,
            'stok_isi' => 50,
            'stok_kosong' => 10,
            'ktp' => '1234567890123457',
            'nama_ktp' => 'Other Owner',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat_ktp' => 'Jl. KTP No. 2',
        ]);

        Customer::create([
            'name' => 'Secret Customer',
            'ktp' => '1111222233334444',
            'phone' => '089999999',
            'address' => 'Secret St.',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $otherSubPangkalan->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('sub-pangkalan.customers.index'));

        $response->assertStatus(200);
        $response->assertSee('John Doe Customer');
        $response->assertSee('9876543210123456');
        $response->assertDontSee('Secret Customer');
    }

    public function test_sub_pangkalan_user_can_access_customers_create_page(): void
    {
        $user = $this->createSubPangkalanUser();

        $response = $this->actingAs($user)
            ->get(route('sub-pangkalan.customers.create'));

        $response->assertStatus(200);
        $response->assertSee('name="name"', false);
        $response->assertSee('name="ktp"', false);
        $response->assertSee('name="phone"', false);
        $response->assertSee('name="address"', false);
        $response->assertDontSee('name="category"', false); // Category should be removed for sub pangkalan
        
        // Assert camera elements
        $response->assertSee('id="camera-video"', false);
        $response->assertSee('id="camera-canvas"', false);
    }

    public function test_sub_pangkalan_user_can_store_new_customer_with_webcam_photo(): void
    {
        $user = $this->createSubPangkalanUser();

        $photoBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

        $response = $this->actingAs($user)
            ->post(route('sub-pangkalan.customers.store'), [
                'name' => 'Alice Customer',
                'ktp' => '1122334455667788',
                'phone' => '0855443322',
                'address' => 'Jl. Wonderland No. 9',
                'photo' => $photoBase64,
            ]);

        $response->assertRedirect(route('sub-pangkalan.customers.index'));
        $response->assertSessionHas('success');

        // Assert database record exists and is linked to the Sub Pangkalan with default category
        $this->assertDatabaseHas('customers', [
            'name' => 'Alice Customer',
            'ktp' => '1122334455667788',
            'category' => 'rumah_tangga',
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        // Verify the photo column is filled
        $customer = Customer::where('ktp', '1122334455667788')->first();
        $this->assertNotNull($customer->photo);

        // Cleanup created file
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($customer->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->photo);
        }
    }

    public function test_sub_pangkalan_user_can_sell_lpg_by_selecting_registered_customer(): void
    {
        $user = $this->createSubPangkalanUser();

        // Create a registered customer under this sub pangkalan
        $customer = Customer::create([
            'name' => 'Bob Customer',
            'ktp' => '5566778899001122',
            'phone' => '0812345',
            'address' => 'Main Street',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('sub-pangkalan.sell.store'), [
                'tabung_type' => '3kg',
                'quantity' => 2,
                'customer_id' => $customer->id,
                'transaction_date' => now()->format('Y-m-d'),
            ]);

        $response->assertRedirect(route('sub-pangkalan.dashboard'));
        $response->assertSessionHas('success');

        // Assert the distribution record has been created
        $this->assertDatabaseHas('sub_pangkalan_transactions', [
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'customer_id' => $customer->id,
            'tabung_type' => '3kg',
            'quantity' => 2,
            'type' => 'out',
            'transaction_type' => 'sell',
        ]);
    }

    public function test_sub_pangkalan_user_can_sell_lpg_without_quota_restriction(): void
    {
        $user = $this->createSubPangkalanUser();

        // Ensure sub pangkalan has enough stock (e.g. 10 tabungs)
        $user->subPangkalan->update(['stok_isi' => 10, 'stok_kosong' => 0]);

        // Create a registered customer under this sub pangkalan
        $customer = Customer::create([
            'name' => 'Charlie Customer',
            'ktp' => '9988776655443322',
            'phone' => '0812345',
            'address' => 'Main Street',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        // Buying 8 tabungs (which exceeds standard quota of 5, but is allowed for sub pangkalan)
        $response = $this->actingAs($user)
            ->post(route('sub-pangkalan.sell.store'), [
                'tabung_type' => '3kg',
                'quantity' => 8,
                'customer_id' => $customer->id,
                'transaction_date' => now()->format('Y-m-d'),
            ]);

        $response->assertRedirect(route('sub-pangkalan.dashboard'));
        $response->assertSessionHas('success');
        $this->assertEquals(2, $user->subPangkalan->fresh()->stok_isi);
    }

    public function test_sub_pangkalan_user_can_view_customer_details(): void
    {
        $user = $this->createSubPangkalanUser();

        $customer = Customer::create([
            'name' => 'John Doe',
            'ktp' => '1212121212121212',
            'phone' => '0812345',
            'address' => 'Test Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('sub-pangkalan.customers.show', $customer));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('1212121212121212');
    }

    public function test_sub_pangkalan_user_cannot_view_other_sub_pangkalan_customer_details(): void
    {
        $user = $this->createSubPangkalanUser();

        $otherSubPangkalan = SubPangkalan::create([
            'name' => 'Other Sub Pangkalan',
            'code' => 'SPT002',
            'address' => 'Jl. Test No. 2',
            'phone' => '081234567891',
            'is_active' => true,
            'stok_isi' => 50,
            'stok_kosong' => 10,
            'ktp' => '1234567890123457',
            'nama_ktp' => 'Other Owner',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat_ktp' => 'Jl. KTP No. 2',
        ]);

        $otherCustomer = Customer::create([
            'name' => 'Other Customer',
            'ktp' => '3434343434343434',
            'phone' => '08989898',
            'address' => 'Other Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $otherSubPangkalan->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('sub-pangkalan.customers.show', $otherCustomer));

        $response->assertStatus(403);
    }

    public function test_sub_pangkalan_user_can_edit_customer_details(): void
    {
        $user = $this->createSubPangkalanUser();

        $customer = Customer::create([
            'name' => 'Old Name',
            'ktp' => '1212121212121212',
            'phone' => '0812345',
            'address' => 'Old Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('sub-pangkalan.customers.edit', $customer));

        $response->assertStatus(200);
        $response->assertSee('Old Name');

        // Put request to update details
        $updateResponse = $this->actingAs($user)
            ->put(route('sub-pangkalan.customers.update', $customer), [
                'name' => 'New Name',
                'ktp' => '1212121212121212',
                'phone' => '087777777',
                'address' => 'New Address',
            ]);

        $updateResponse->assertRedirect(route('sub-pangkalan.customers.index'));
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'New Name',
            'address' => 'New Address',
        ]);
    }

    public function test_sub_pangkalan_user_can_delete_customer(): void
    {
        $user = $this->createSubPangkalanUser();

        $customer = Customer::create([
            'name' => 'To Be Deleted',
            'ktp' => '1212121212121212',
            'phone' => '0812345',
            'address' => 'Test Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('sub-pangkalan.customers.destroy', $customer));

        $response->assertRedirect(route('sub-pangkalan.customers.index'));
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_sub_pangkalan_user_can_filter_dashboard_by_customer_name(): void
    {
        $user = $this->createSubPangkalanUser();

        $customer1 = Customer::create([
            'name' => 'Specific Customer A',
            'ktp' => '1212121212121212',
            'phone' => '0812345',
            'address' => 'Test Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        $customer2 = Customer::create([
            'name' => 'Hidden Customer B',
            'ktp' => '9898989898989898',
            'phone' => '0812345',
            'address' => 'Test Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        // Create transaction for customer1
        SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'customer_id' => $customer1->id,
            'tabung_type' => '3kg',
            'quantity' => 1,
            'type' => 'out',
            'transaction_type' => 'sell',
            'customer_type' => 'rumah_tangga',
            'transaction_date' => now(),
            'status' => 'approved',
        ]);

        // Create transaction for customer2
        SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'customer_id' => $customer2->id,
            'tabung_type' => '3kg',
            'quantity' => 2,
            'type' => 'out',
            'transaction_type' => 'sell',
            'customer_type' => 'rumah_tangga',
            'transaction_date' => now(),
            'status' => 'approved',
        ]);

        // Access dashboard without filters
        $response = $this->actingAs($user)
            ->get(route('sub-pangkalan.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Specific Customer A');
        $response->assertSee('Hidden Customer B');

        // Access dashboard with search filter
        $filteredResponse = $this->actingAs($user)
            ->get(route('sub-pangkalan.dashboard', ['search' => 'Specific Customer A']));
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Specific Customer A');
        $filteredResponse->assertDontSee('Hidden Customer B');
    }

    public function test_sub_pangkalan_user_can_filter_dashboard_by_month_and_date(): void
    {
        $user = $this->createSubPangkalanUser();

        $customer = Customer::create([
            'name' => 'Specific Customer A',
            'ktp' => '1212121212121212',
            'phone' => '0812345',
            'address' => 'Test Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        // Transaction on 2026-05-10
        $t1 = SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'customer_id' => $customer->id,
            'tabung_type' => '3kg',
            'quantity' => 1,
            'type' => 'out',
            'transaction_type' => 'sell',
            'customer_type' => 'rumah_tangga',
            'transaction_date' => '2026-05-10',
            'status' => 'approved',
            'notes' => 'May Transaction Note',
        ]);

        // Transaction on 2026-06-15
        $t2 = SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'customer_id' => $customer->id,
            'tabung_type' => '3kg',
            'quantity' => 2,
            'type' => 'out',
            'transaction_type' => 'sell',
            'customer_type' => 'rumah_tangga',
            'transaction_date' => '2026-06-15',
            'status' => 'approved',
            'notes' => 'June Transaction Note',
        ]);

        // Filter by month = 5 (May)
        $monthResponse = $this->actingAs($user)
            ->get(route('sub-pangkalan.dashboard', ['month' => 5]));
        $monthResponse->assertStatus(200);
        $monthResponse->assertSee('May Transaction Note');
        $monthResponse->assertDontSee('June Transaction Note');

        // Filter by exact date = 2026-06-15
        $dateResponse = $this->actingAs($user)
            ->get(route('sub-pangkalan.dashboard', ['date' => '2026-06-15']));
        $dateResponse->assertStatus(200);
        $dateResponse->assertSee('June Transaction Note');
        $dateResponse->assertDontSee('May Transaction Note');
    }

    public function test_sub_pangkalan_user_can_filter_history_by_customer_name(): void
    {
        $user = $this->createSubPangkalanUser();

        $customer1 = Customer::create([
            'name' => 'Specific Customer A',
            'ktp' => '1212121212121212',
            'phone' => '0812345',
            'address' => 'Test Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        $customer2 = Customer::create([
            'name' => 'Hidden Customer B',
            'ktp' => '9898989898989898',
            'phone' => '0812345',
            'address' => 'Test Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
        ]);

        // Create transaction for customer1
        SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'customer_id' => $customer1->id,
            'tabung_type' => '3kg',
            'quantity' => 1,
            'type' => 'out',
            'transaction_type' => 'sell',
            'customer_type' => 'rumah_tangga',
            'transaction_date' => now(),
            'status' => 'approved',
        ]);

        // Create transaction for customer2
        SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'customer_id' => $customer2->id,
            'tabung_type' => '3kg',
            'quantity' => 2,
            'type' => 'out',
            'transaction_type' => 'sell',
            'customer_type' => 'rumah_tangga',
            'transaction_date' => now(),
            'status' => 'approved',
        ]);

        // Access history without filters
        $response = $this->actingAs($user)
            ->get(route('sub-pangkalan.history'));
        $response->assertStatus(200);
        $response->assertSee('Specific Customer A');
        $response->assertSee('Hidden Customer B');

        // Access history with search filter
        $filteredResponse = $this->actingAs($user)
            ->get(route('sub-pangkalan.history', ['search' => 'Specific Customer A']));
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Specific Customer A');
        $filteredResponse->assertDontSee('Hidden Customer B');
    }

    public function test_sub_pangkalan_user_can_access_exchange_empty_tabung_page_and_see_history(): void
    {
        $user = $this->createSubPangkalanUser();

        // Create transaction for empty tabung exchange
        SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'tabung_type' => '3kg',
            'quantity' => 4,
            'type' => 'in',
            'transaction_type' => 'exchange',
            'transaction_date' => now(),
            'status' => 'pending',
            'notes' => 'Tolong segera diproses',
        ]);

        $response = $this->actingAs($user)
            ->get(route('sub-pangkalan.exchange.create'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Penukaran Terbaru');
        $response->assertSee('Tabung 3kg');
        $response->assertSee('4 Tabung');
        $response->assertSee('Menunggu');
        $response->assertSee('Tolong segera diproses');
    }

    public function test_sub_pangkalan_user_can_see_exchange_transactions_on_history_and_dashboard_pages(): void
    {
        $user = $this->createSubPangkalanUser();

        // Create transaction for empty tabung exchange
        SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'tabung_type' => '3kg',
            'quantity' => 12,
            'type' => 'in',
            'transaction_type' => 'exchange',
            'transaction_date' => now(),
            'status' => 'pending',
            'notes' => 'Tukar 12 tabung kosong',
        ]);

        // Check history page -> Should NOT see Tukar Kosong / exchange transaction here
        $historyResponse = $this->actingAs($user)
            ->get(route('sub-pangkalan.history'));
        $historyResponse->assertStatus(200);
        $historyResponse->assertDontSee('Tukar 12 tabung kosong');
        $historyResponse->assertDontSee('12 Tabung');

        // Check dashboard page -> Should see Tukar Kosong under the dedicated section
        $dashboardResponse = $this->actingAs($user)
            ->get(route('sub-pangkalan.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Tukar Tabung Kosong');
        $dashboardResponse->assertSee('Tukar 12 tabung kosong');
        $dashboardResponse->assertSee('12 Tabung');
    }

    public function test_sub_pangkalan_user_can_see_transaction_notes_on_history_and_dashboard_pages(): void
    {
        $user = $this->createSubPangkalanUser();
        $customer = \App\Models\Customer::create([
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'name' => 'John Notes Customer',
            'ktp' => '3201010101010001',
            'phone' => '0812345678',
            'address' => 'Jl. Notes Test',
            'category' => 'rumah_tangga',
            'is_active' => true,
        ]);

        SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $user->sub_pangkalan_id,
            'customer_id' => $customer->id,
            'tabung_type' => '3kg',
            'quantity' => 2,
            'type' => 'out',
            'transaction_type' => 'sell',
            'customer_type' => 'rumah_tangga',
            'transaction_date' => now(),
            'status' => 'approved',
            'notes' => 'Catatan penjualan John',
        ]);

        // Check history page
        $historyResponse = $this->actingAs($user)
            ->get(route('sub-pangkalan.history'));
        $historyResponse->assertStatus(200);
        $historyResponse->assertSee('Catatan');
        $historyResponse->assertSee('Catatan penjualan John');

        // Check dashboard page
        $dashboardResponse = $this->actingAs($user)
            ->get(route('sub-pangkalan.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Catatan');
        $dashboardResponse->assertSee('Catatan penjualan John');
    }

    public function test_sub_pangkalan_user_can_confirm_receive_transaction_on_dashboard(): void
    {
        $user = $this->createSubPangkalanUser();
        $subPangkalan = $user->subPangkalan;
        $initialStock = $subPangkalan->stok_isi;

        $transaction = SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $subPangkalan->id,
            'tabung_type' => '3kg',
            'quantity' => 15,
            'type' => 'in',
            'transaction_type' => 'receive',
            'transaction_date' => now(),
            'status' => 'pending',
            'notes' => 'Pengisian tabung isi test',
        ]);

        \App\Models\PenjualanLangsung::create([
            'user_id' => $user->id,
            'tabung_type' => '3kg',
            'quantity' => 15,
            'customer_type' => 'pengecer',
            'nama_pembeli' => $subPangkalan->name,
            'no_ktp' => $subPangkalan->ktp,
            'transaction_date' => now(),
            'notes' => 'Pengisian tabung isi test',
        ]);

        // Access dashboard -> Should see pending transaction and notes, but NO confirm button (no action column)
        $dashboardResponse = $this->actingAs($user)
            ->get(route('sub-pangkalan.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Penerimaan Tabung Isi');
        $dashboardResponse->assertSee('Pengisian tabung isi test');
        $dashboardResponse->assertSee('15 Tabung');
        $dashboardResponse->assertDontSee('Konfirmasi Terima');

        // Post confirmation
        $confirmResponse = $this->actingAs($user)
            ->post(route('sub-pangkalan.sub-pangkalan-transaction.confirm', $transaction));
        $confirmResponse->assertRedirect();
        
        // Assert stock increased
        $subPangkalan->refresh();
        $this->assertEquals($initialStock + 15, $subPangkalan->stok_isi);

        // Assert transaction status approved
        $transaction->refresh();
        $this->assertEquals('approved', $transaction->status);
    }
}


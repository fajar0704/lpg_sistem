<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SubPangkalan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubPangkalanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_sub_pangkalan_create_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.sub-pangkalan.create'));

        $response->assertStatus(200);

        // Assert form labels and inputs are present
        $response->assertSee('name="name"', false);
        $response->assertSee('name="code"', false);
        $response->assertSee('name="phone"', false);
        $response->assertSee('name="address"', false);
        
        // Assert KTP details fields
        $response->assertSee('name="ktp"', false);
        $response->assertSee('name="nama_ktp"', false);
        $response->assertSee('name="tempat_lahir"', false);
        $response->assertSee('name="tanggal_lahir"', false);
        $response->assertSee('name="jenis_kelamin"', false);
        $response->assertSee('name="alamat_ktp"', false);
        
        // Assert account credential fields
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="role"', false);
        
        // Assert camera elements
        $response->assertSee('id="camera-video"', false);
        $response->assertSee('id="camera-canvas"', false);
        $response->assertSee('name="photo"', false);
    }

    public function test_admin_can_access_sub_pangkalan_detail_page_without_sales_history(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

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

        $response = $this->actingAs($admin)
            ->get(route('admin.sub-pangkalan.detail', $subPangkalan));

        $response->assertStatus(200);

        // Assert profile information details are present
        $response->assertSee('Profil Sub Pangkalan');
        $response->assertSee('Sub Pangkalan Test');
        $response->assertSee('SPT001');
        $response->assertSee('Identitas Pemilik (KTP)');
        
        // Assert sales history table has been removed from page
        $response->assertDontSee('Riwayat Penjualan</h3>', false);
        $response->assertDontSee('<th>Validasi</th>', false);
        $response->assertDontSee('<th>Jenis</th>', false);

        // Assert password field and reset button are present
        $response->assertSee('id="password-text"', false);
        $response->assertSee('Reset Password');
        $response->assertDontSee('id="toggle-password-btn"', false);
    }

    public function test_admin_can_access_monitoring_detail_page_with_customer_names(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

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

        $customer = \App\Models\Customer::create([
            'name' => 'Charlie Customer',
            'ktp' => '1212121212121212',
            'phone' => '0812345',
            'address' => 'Test Address',
            'category' => 'rumah_tangga',
            'is_active' => true,
            'sub_pangkalan_id' => $subPangkalan->id,
        ]);

        // Create transaction for customer
        \App\Models\SubPangkalanTransaction::create([
            'user_id' => $admin->id,
            'sub_pangkalan_id' => $subPangkalan->id,
            'customer_id' => $customer->id,
            'tabung_type' => '3kg',
            'quantity' => 1,
            'type' => 'out',
            'transaction_type' => 'sell',
            'customer_type' => 'rumah_tangga',
            'transaction_date' => now(),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.monitoring.detail', $subPangkalan));

        $response->assertStatus(200);
        $response->assertSee('Charlie Customer');
        $response->assertSee('NIK: 1212121212121212');
    }

    public function test_admin_can_delete_inactive_sub_pangkalan(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $subPangkalan = SubPangkalan::create([
            'name' => 'Sub Pangkalan Deleted Test',
            'code' => 'SPT999',
            'address' => 'Jl. Test No. 999',
            'phone' => '0899999999',
            'is_active' => false,
            'stok_isi' => 0,
            'stok_kosong' => 0,
            'ktp' => '9999999999999999',
            'nama_ktp' => 'Deleted Owner',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat_ktp' => 'Jl. KTP No. 999',
        ]);

        // Create transaction for this sub pangkalan
        \App\Models\SubPangkalanTransaction::create([
            'user_id' => $admin->id,
            'sub_pangkalan_id' => $subPangkalan->id,
            'tabung_type' => '3kg',
            'quantity' => 1,
            'type' => 'out',
            'transaction_type' => 'sell',
            'customer_type' => 'rumah_tangga',
            'transaction_date' => now(),
            'status' => 'approved',
        ]);

        // Delete the Sub Pangkalan
        $response = $this->actingAs($admin)
            ->delete(route('admin.sub-pangkalan.destroy', $subPangkalan));

        $response->assertRedirect(route('admin.sub-pangkalan.index'));
        $this->assertDatabaseMissing('sub_pangkalan', [
            'id' => $subPangkalan->id,
        ]);

        // Check index page does not see the sub pangkalan
        $indexResponse = $this->actingAs($admin)
            ->get(route('admin.sub-pangkalan.index'));
        $indexResponse->assertDontSee('Sub Pangkalan Deleted Test');

        // Check monitoring page does not see the sub pangkalan
        $monitoringResponse = $this->actingAs($admin)
            ->get(route('admin.monitoring.index'));
        $monitoringResponse->assertDontSee('Sub Pangkalan Deleted Test');
    }

    public function test_admin_can_access_own_profile_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'name' => 'Super Administrator',
            'email' => 'superadmin@lpg.com',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.profile'));

        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
        $response->assertSee('Super Administrator');
        $response->assertSee('superadmin@lpg.com');
    }

    public function test_admin_can_update_own_profile_and_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'name' => 'Old Name',
            'email' => 'old@lpg.com',
            'password' => \Hash::make('password123'),
        ]);

        \Illuminate\Support\Facades\Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->image('avatar.png');

        // 1. Update Info
        $response = $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'section' => 'info',
                'name' => 'New Name',
                'email' => 'new@lpg.com',
                'photo' => $file,
            ]);

        $response->assertRedirect();
        $admin->refresh();
        $this->assertEquals('New Name', $admin->name);
        $this->assertEquals('new@lpg.com', $admin->email);
        $this->assertNotNull($admin->photo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($admin->photo);

        // 2. Update Password
        $responsePassword = $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'section' => 'password',
                'current_password' => 'password123',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $responsePassword->assertRedirect();
        $admin->refresh();
        $this->assertTrue(\Hash::check('newpassword123', $admin->password));
    }

    public function test_admin_can_access_monitoring_index_and_see_transaction_notes(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $subPangkalan = \App\Models\SubPangkalan::create([
            'name' => 'Sub Pangkalan Test',
            'code' => 'SP001',
            'ktp' => '1234567890123456',
            'phone' => '0812345678',
            'address' => 'Jl. Test',
            'is_active' => true,
        ]);

        \App\Models\SubPangkalanTransaction::create([
            'sub_pangkalan_id' => $subPangkalan->id,
            'user_id' => $admin->id,
            'tabung_type' => '3kg',
            'quantity' => 10,
            'type' => 'in',
            'transaction_type' => 'return_kosong',
            'transaction_date' => today(),
            'status' => 'pending',
            'notes' => 'Catatan transaksi kosong untuk pangkalan',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.monitoring.index'));

        $response->assertStatus(200);
        $response->assertSee('Catatan');
        $response->assertSee('Catatan transaksi kosong untuk pangkalan');
    }

    public function test_sub_pangkalan_user_can_access_profile_page_and_update_it(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

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

        $user = User::factory()->create([
            'name' => 'Sub Pangkalan Test',
            'email' => 'sp@test.com',
            'password' => \Hash::make('password123'),
            'role' => 'sub_pangkalan',
            'sub_pangkalan_id' => $subPangkalan->id,
            'is_active' => true,
        ]);

        // 1. Test profile page access
        $response = $this->actingAs($user)
            ->get(route('sub-pangkalan.profile'));

        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
        $response->assertSee('Test Owner');
        $response->assertSee('sp@test.com');

        // 2. Test profile update
        $photo = \Illuminate\Http\UploadedFile::fake()->image('avatar.jpg');

        $responseUpdate = $this->actingAs($user)
            ->put(route('sub-pangkalan.profile.update'), [
                'nama_ktp' => 'New Owner Name',
                'phone' => '08987654321',
                'email' => 'new_sp@test.com',
                'address' => 'New Address',
                'photo' => $photo,
            ]);

        $responseUpdate->assertRedirect();
        
        $subPangkalan->refresh();
        $user->refresh();

        $this->assertEquals('New Owner Name', $subPangkalan->nama_ktp);
        $this->assertEquals('08987654321', $subPangkalan->phone);
        $this->assertEquals('new_sp@test.com', $user->email);
        $this->assertEquals('New Address', $subPangkalan->address);
        $this->assertNotNull($subPangkalan->photo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($subPangkalan->photo);

        // 3. Test password update
        $responsePassword = $this->actingAs($user)
            ->put(route('sub-pangkalan.profile.update'), [
                'section' => 'password',
                'current_password' => 'password123',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $responsePassword->assertRedirect();
        $user->refresh();
        $this->assertTrue(\Hash::check('newpassword123', $user->password));
    }

    public function test_admin_can_update_login_settings(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 1. Check profile page shows form inputs
        $response = $this->actingAs($admin)
            ->get(route('admin.profile'));
        $response->assertStatus(200);
        $response->assertSee('login_title');
        $response->assertSee('login_subtitle');

        // 2. Update login settings
        $logo = \Illuminate\Http\UploadedFile::fake()->image('login_logo.png');
        $responseUpdate = $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'section' => 'login_settings',
                'login_title' => 'Custom Login Title',
                'login_subtitle' => 'Custom Login Subtitle',
                'login_logo' => $logo,
            ]);

        $responseUpdate->assertRedirect();
        
        $this->assertEquals('Custom Login Title', \App\Models\Setting::getValue('login_title'));
        $this->assertEquals('Custom Login Subtitle', \App\Models\Setting::getValue('login_subtitle'));
        
        $logoPath = \App\Models\Setting::getValue('login_logo');
        $this->assertNotNull($logoPath);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($logoPath);

        // 3. Check login page shows updated title and subtitle
        auth()->logout();
        $responseLogin = $this->get(route('login'));
        $responseLogin->assertStatus(200);
        $responseLogin->assertSee('Custom Login Title');
        $responseLogin->assertSee('Custom Login Subtitle');
    }

    public function test_admin_profile_warning_on_no_changes(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 1. Submit profile without changes
        $response = $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'section' => 'info',
                'name' => $admin->name,
                'email' => $admin->email,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('warning', 'Tidak ada perubahan pada data profil Anda.');

        // 2. Submit login settings without changes
        \App\Models\Setting::setValue('login_title', 'Sistem Pangkalan LPG');
        \App\Models\Setting::setValue('login_subtitle', 'Silakan masuk untuk mengelola LPG');

        $responseLogin = $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'section' => 'login_settings',
                'login_title' => 'Sistem Pangkalan LPG',
                'login_subtitle' => 'Silakan masuk untuk mengelola LPG',
            ]);

        $responseLogin->assertRedirect();
        $responseLogin->assertSessionHas('warning', 'Tidak ada perubahan pada pengaturan halaman login.');
    }

    public function test_admin_can_reset_sub_pangkalan_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

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

        $user = User::factory()->create([
            'name' => 'Sub Pangkalan Test',
            'email' => 'sp@test.com',
            'password' => \Hash::make('originalpassword'),
            'role' => 'sub_pangkalan',
            'sub_pangkalan_id' => $subPangkalan->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.sub-pangkalan.reset-password', $subPangkalan));

        $response->assertRedirect();
        $user->refresh();
        $this->assertTrue(\Hash::check('pangkalan123', $user->password));
    }

    public function test_admin_must_reset_password_before_modifying_email_or_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

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

        $user = User::factory()->create([
            'name' => 'Sub Pangkalan Test',
            'email' => 'sp@test.com',
            'password' => \Hash::make('originalpassword'),
            'role' => 'sub_pangkalan',
            'sub_pangkalan_id' => $subPangkalan->id,
            'is_active' => true,
        ]);

        // 1. Assert edit page shows readonly email/password because password is not reset yet
        $responseEdit = $this->actingAs($admin)
            ->get(route('admin.sub-pangkalan.edit', $subPangkalan));

        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('readonly', false);
        $responseEdit->assertSee('Reset Password');

        // 2. Submit form attempting to change email - should fail
        $responseUpdateFail = $this->actingAs($admin)
            ->put(route('admin.sub-pangkalan.update', $subPangkalan), [
                'name' => 'Updated Name',
                'code' => 'SPT001',
                'address' => 'Jl. Test No. 1',
                'phone' => '081234567890',
                'ktp' => '1234567890123456',
                'nama_ktp' => 'Test Owner',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'alamat_ktp' => 'Jl. KTP No. 1',
                'email' => 'new_sp@test.com',
            ]);

        $responseUpdateFail->assertRedirect();
        $responseUpdateFail->assertSessionHas('error', 'Email atau password hanya dapat diubah setelah Anda melakukan Reset Password terlebih dahulu.');

        // 3. Reset password first
        $this->actingAs($admin)
            ->post(route('admin.sub-pangkalan.reset-password', $subPangkalan));

        // 4. Now submit update to change email - should succeed
        $responseUpdateSuccess = $this->actingAs($admin)
            ->put(route('admin.sub-pangkalan.update', $subPangkalan), [
                'name' => 'Updated Name',
                'code' => 'SPT001',
                'address' => 'Jl. Test No. 1',
                'phone' => '081234567890',
                'ktp' => '1234567890123456',
                'nama_ktp' => 'Test Owner',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'alamat_ktp' => 'Jl. KTP No. 1',
                'email' => 'new_sp@test.com',
                'password' => 'newpassword123',
            ]);

        $responseUpdateSuccess->assertRedirect();
        
        $user->refresh();
        $this->assertEquals('new_sp@test.com', $user->email);
        $this->assertTrue(\Hash::check('newpassword123', $user->password));
    }

    public function test_sub_pangkalan_can_filter_exchange_history_via_ajax(): void
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

        $user = User::factory()->create([
            'name' => 'Sub Pangkalan Test',
            'email' => 'sp@test.com',
            'role' => 'sub_pangkalan',
            'sub_pangkalan_id' => $subPangkalan->id,
            'is_active' => true,
        ]);

        \App\Models\SubPangkalanTransaction::create([
            'user_id' => $user->id,
            'sub_pangkalan_id' => $subPangkalan->id,
            'tabung_type' => '3kg',
            'quantity' => 5,
            'type' => 'out',
            'transaction_type' => 'exchange',
            'transaction_date' => '2026-07-20',
            'status' => 'pending',
            'notes' => 'Catatan pending',
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('sub-pangkalan.exchange.create', ['status' => 'pending']), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['html']);
        $this->assertStringContainsString('Catatan pending', $response->json('html'));
    }

    public function test_sub_pangkalan_can_filter_refill_history_via_ajax(): void
    {
        $subPangkalan = SubPangkalan::create([
            'name' => 'Sub Pangkalan Refill Test',
            'code' => 'SPT002',
            'address' => 'Jl. Refill No. 2',
            'phone' => '081299998888',
            'is_active' => true,
            'stok_isi' => 50,
            'stok_kosong' => 10,
            'ktp' => '9999888877776666',
            'nama_ktp' => 'Refill Owner',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1992-02-02',
            'jenis_kelamin' => 'Laki-laki',
            'alamat_ktp' => 'Jl. KTP Refill',
        ]);

        $user = User::factory()->create([
            'name' => 'Sub Pangkalan Refill User',
            'email' => 'sp_refill@test.com',
            'role' => 'sub_pangkalan',
            'sub_pangkalan_id' => $subPangkalan->id,
            'is_active' => true,
        ]);

        \App\Models\PenjualanLangsung::create([
            'user_id' => $user->id,
            'customer_type' => 'pengecer',
            'tabung_type' => '3kg',
            'quantity' => 20,
            'transaction_date' => '2026-07-20',
            'buyer_name' => 'Sub Pangkalan Refill Test',
            'no_ktp' => '9999888877776666',
            'notes' => 'Pasokan rutin tabung isi',
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('sub-pangkalan.dashboard', ['target' => 'refill', 'refill_month' => '7']), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['html']);
        $this->assertStringContainsString('Pasokan rutin tabung isi', $response->json('html'));
    }

    public function test_sub_pangkalan_can_fetch_history_via_ajax(): void
    {
        $subPangkalan = SubPangkalan::create([
            'name' => 'Sub Pangkalan History Test',
            'code' => 'SPH001',
            'address' => 'Jl. History Test',
            'phone' => '081234567899',
            'is_active' => true,
            'stok_isi' => 50,
            'stok_kosong' => 10,
            'ktp' => '1111222233334444',
            'nama_ktp' => 'History Owner',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1995-05-05',
            'jenis_kelamin' => 'Laki-laki',
            'alamat_ktp' => 'Jl. KTP History',
        ]);

        $user = User::factory()->create([
            'name' => 'Sub Pangkalan History User',
            'email' => 'sp_history@test.com',
            'role' => 'sub_pangkalan',
            'sub_pangkalan_id' => $subPangkalan->id,
            'is_active' => true,
        ]);

        \App\Models\SubPangkalanTransaction::create([
            'sub_pangkalan_id' => $subPangkalan->id,
            'user_id' => $user->id,
            'tabung_type' => '3kg',
            'quantity' => 5,
            'type' => 'out',
            'transaction_type' => 'sell',
            'transaction_date' => '2026-07-20',
            'status' => 'approved',
            'notes' => 'Penjualan riwayat ajax test',
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('sub-pangkalan.history', ['page' => 1]), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['html', 'total']);
        $this->assertStringContainsString('Penjualan riwayat ajax test', $response->json('html'));
    }

    public function test_sub_pangkalan_user_can_delete_profile_photo(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->image('avatar.jpg');
        $path = $file->store('sub_pangkalan_photos', 'public');

        $subPangkalan = SubPangkalan::create([
            'name' => 'Sub Pangkalan Photo Delete Test',
            'code' => 'SPPDT01',
            'address' => 'Jl. Test Photo',
            'phone' => '081234567891',
            'ktp' => '1234567890123456',
            'nama_ktp' => 'Owner Name',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat_ktp' => 'Jl. KTP Address',
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Sub Pangkalan Photo Owner',
            'email' => 'photoowner@test.com',
            'password' => \Hash::make('password123'),
            'role' => 'sub_pangkalan',
            'sub_pangkalan_id' => $subPangkalan->id,
            'is_active' => true,
        ]);
        $user->photo = $path;
        $user->save();

        $response = $this->actingAs($user)
            ->from(route('sub-pangkalan.profile'))
            ->delete(route('sub-pangkalan.profile.delete-photo'));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Foto profil berhasil dihapus.');

        $this->assertNull($user->fresh()->photo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertMissing($path);
    }
}


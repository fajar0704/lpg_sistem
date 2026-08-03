<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create([
            'name' => 'Admin Pangkalan',
            'email' => 'admin@lpg.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_profile_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.profile'));

        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
        $response->assertSee('Admin Pangkalan');
    }

    public function test_admin_can_update_profile_info(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.profile.update'), [
                'section' => 'info',
                'name' => 'Admin Baru',
                'email' => 'adminbaru@lpg.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Admin Baru',
            'email' => 'adminbaru@lpg.com',
        ]);
    }

    public function test_admin_can_upload_and_delete_profile_photo(): void
    {
        $photo = UploadedFile::fake()->image('profile.jpg');

        $response = $this->actingAs($this->admin)
            ->put(route('admin.profile.update'), [
                'section' => 'info',
                'name' => $this->admin->name,
                'email' => $this->admin->email,
                'photo' => $photo,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $this->admin->refresh();
        $this->assertNotNull($this->admin->photo);
        Storage::disk('public')->assertExists($this->admin->photo);

        // Delete photo test
        $deleteResponse = $this->actingAs($this->admin)
            ->delete(route('admin.profile.delete-photo'));

        $deleteResponse->assertRedirect();
        $deleteResponse->assertSessionHas('success', 'Foto profil berhasil dihapus.');

        $this->admin->refresh();
        $this->assertNull($this->admin->photo);
    }

    public function test_admin_can_update_password_with_valid_current_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.profile.update'), [
                'section' => 'password',
                'current_password' => 'password123',
                'password' => 'newsecret123',
                'password_confirmation' => 'newsecret123',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password berhasil diperbarui.');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('newsecret123', $this->admin->password));
    }

    public function test_admin_cannot_update_password_with_invalid_current_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.profile.update'), [
                'section' => 'password',
                'current_password' => 'wrongpassword',
                'password' => 'newsecret123',
                'password_confirmation' => 'newsecret123',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_admin_can_update_and_reset_login_page_settings(): void
    {
        $logo = UploadedFile::fake()->image('custom_logo.png');

        $response = $this->actingAs($this->admin)
            ->put(route('admin.profile.update'), [
                'section' => 'login_settings',
                'login_title' => 'Sistem Elpiji Terpadu',
                'login_subtitle' => 'Halaman Masuk Resmi Administrator',
                'login_logo' => $logo,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pengaturan halaman login berhasil diperbarui.');

        $this->assertEquals('Sistem Elpiji Terpadu', Setting::getValue('login_title'));
        $this->assertEquals('Halaman Masuk Resmi Administrator', Setting::getValue('login_subtitle'));
        $this->assertNotNull(Setting::getValue('login_logo'));

        // Reset logo test
        $resetResponse = $this->actingAs($this->admin)
            ->delete(route('admin.profile.delete-login-logo'));

        $resetResponse->assertRedirect();
        $resetResponse->assertSessionHas('success', 'Logo login kustom berhasil dihapus dan dikembalikan ke logo default.');

        $this->assertNull(Setting::getValue('login_logo'));
    }
}

<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use App\Models\Proyek;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProyekResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_dari_dasbor_admin()
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_tamu_diarahkan_dari_indeks_proyek()
    {
        $response = $this->get('/admin/proyeks');

        $response->assertRedirect('/admin/login');
    }

    public function test_pengguna_terautentikasi_dapat_mengakses_dasbor_admin()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertSuccessful();
    }

    public function test_pengguna_terautentikasi_dapat_mengakses_indeks_proyek()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/proyeks');

        $response->assertSuccessful();
    }

    public function test_akses_panel_berdasarkan_domain_email()
    {
        // Memverifikasi bahwa email dengan huruf besar (misalnya USER@CYBERPUNK.IO) dapat mengakses panel.
        $userUppercase = User::factory()->create(['email' => 'USER@CYBERPUNK.IO']);
        $this->actingAs($userUppercase)->get('/admin')->assertSuccessful();

        // Mengubah lingkungan aplikasi (environment) secara sementara ke produksi (production)
        $this->app->detectEnvironment(fn() => 'production');

        // Memverifikasi bahwa email non-@cyberpunk.io tidak dapat mengakses panel
        $userGmail = User::factory()->create(['email' => 'user@gmail.com']);
        $this->actingAs($userGmail)->get('/admin')->assertForbidden();

        // Memverifikasi bahwa email valid @cyberpunk.io dengan huruf kecil dapat mengakses panel
        $userLowercase = User::factory()->create(['email' => 'user@cyberpunk.io']);
        $this->actingAs($userLowercase)->get('/admin')->assertSuccessful();

        // Memverifikasi bahwa email valid @cyberpunk.io dengan huruf besar dapat mengakses panel
        $userUppercaseProd = User::factory()->create(['email' => 'ANOTHER@CYBERPUNK.IO']);
        $this->actingAs($userUppercaseProd)->get('/admin')->assertSuccessful();
    }
}

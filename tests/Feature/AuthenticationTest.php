<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. guest dapat membuka halaman login
     */
    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun Anda');
    }

    /**
     * 2. guest dapat membuka halaman register
     */
    public function test_guest_can_view_register_page(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
    }

    /**
     * 3. user dapat register dengan data valid
     */
    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('customer.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@example.com',
            'name' => 'Ahmad Dahlan',
        ]);
    }

    /**
     * 4. password tersimpan dalam bentuk hash
     */
    public function test_password_is_stored_as_hash(): void
    {
        $this->post('/register', [
            'name' => 'Dewi Sartika',
            'email' => 'dewi@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $user = User::where('email', 'dewi@example.com')->first();

        $this->assertNotNull($user);
        $this->assertNotEquals('rahasia123', $user->password);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    /**
     * 5. user baru otomatis memiliki role customer
     */
    public function test_new_registered_user_automatically_has_customer_role(): void
    {
        $this->post('/register', [
            'name' => 'Hasanudin',
            'email' => 'hasan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'hasan@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role);
    }

    /**
     * 6. email duplicate ditolak
     */
    public function test_duplicate_email_is_rejected(): void
    {
        User::create([
            'name' => 'Pengguna Lama',
            'email' => 'kembar@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->post('/register', [
            'name' => 'Pengguna Baru',
            'email' => 'kembar@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * 7. password confirmation wajib sesuai
     */
    public function test_password_confirmation_must_match(): void
    {
        $response = $this->post('/register', [
            'name' => 'Candra Wijaya',
            'email' => 'candra@example.com',
            'password' => 'password123',
            'password_confirmation' => 'beda12345',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'candra@example.com']);
    }

    /**
     * 8. user dapat login dengan credential valid
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Pelanggan Asli',
            'email' => 'pelanggan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'email' => 'pelanggan@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('customer.dashboard'));
    }

    /**
     * 9. credential invalid ditolak
     */
    public function test_invalid_credentials_are_rejected(): void
    {
        User::create([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'email' => 'pelanggan@example.com',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * 10. session diregenerate setelah login
     */
    public function test_session_is_regenerated_after_login(): void
    {
        $user = User::create([
            'name' => 'Pelanggan Sesi',
            'email' => 'sesi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->get('/login');
        $oldSessionId = session()->getId();

        $this->post('/login', [
            'email' => 'sesi@example.com',
            'password' => 'password123',
        ]);

        $newSessionId = session()->getId();
        $this->assertNotEquals($oldSessionId, $newSessionId);
    }

    /**
     * 11. authenticated user dapat logout
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Pelanggan Logout',
            'email' => 'logout@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }

    /**
     * 12. logout mengakhiri session
     */
    public function test_logout_invalidates_session(): void
    {
        $user = User::create([
            'name' => 'Pelanggan Sesi Out',
            'email' => 'sesiout@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->actingAs($user);
        session()->put('custom_test_key', 'some_value');

        $this->post('/logout');

        $this->assertNull(session()->get('custom_test_key'));
        $this->assertGuest();
    }

    /**
     * 13. customer dapat mengakses customer area
     */
    public function test_customer_can_access_customer_area(): void
    {
        $customer = User::create([
            'name' => 'Pelanggan Area',
            'email' => 'customerarea@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get('/customer/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dasbor Pelanggan');
    }

    /**
     * 14. guest tidak dapat mengakses customer area
     */
    public function test_guest_cannot_access_customer_area(): void
    {
        $response = $this->get('/customer/dashboard');

        $response->assertRedirect(route('login'));
    }

    /**
     * 15. customer tidak dapat mengakses admin area
     */
    public function test_customer_cannot_access_admin_area(): void
    {
        $customer = User::create([
            'name' => 'Pelanggan Bukan Admin',
            'email' => 'bukanadmin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * 16. admin dapat mengakses admin area
     */
    public function test_admin_can_access_admin_area(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'adminutama@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dasbor Administrator');
    }

    /**
     * 17. role tidak dapat ditentukan melalui registration input
     */
    public function test_role_cannot_be_set_via_registration_input(): void
    {
        $response = $this->post('/register', [
            'name' => 'Hacker Wannabe',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // Percobaan manipulasi input role
        ]);

        $user = User::where('email', 'hacker@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role);
        $this->assertNotEquals('admin', $user->role);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Route;
use App\Models\Seat;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer1;
    protected User $customer2;
    protected Bus $bus;
    protected Route $route;
    protected Trip $trip;
    protected Seat $seat1;
    protected Seat $seat2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin.sec@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->customer1 = User::create([
            'name' => 'Customer One',
            'email' => 'cust1.sec@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->customer2 = User::create([
            'name' => 'Customer Two',
            'email' => 'cust2.sec@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->bus = Bus::create([
            'name' => 'CAN Sec Bus',
            'code' => 'CAN-SEC-01',
            'total_seats' => 4,
        ]);

        $this->seat1 = Seat::create(['bus_id' => $this->bus->id, 'seat_number' => '1A']);
        $this->seat2 = Seat::create(['bus_id' => $this->bus->id, 'seat_number' => '1B']);

        $this->route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Semarang',
            'duration' => 360,
        ]);

        $this->trip = Trip::create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'departure_at' => Carbon::now()->addDays(2),
            'arrival_at' => Carbon::now()->addDays(2)->addHours(6),
            'price' => 200000,
            'status' => 'scheduled',
        ]);
    }

    public function test_guest_is_redirected_from_all_admin_routes(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/buses')->assertRedirect('/login');
        $this->get('/admin/routes')->assertRedirect('/login');
        $this->get('/admin/trips')->assertRedirect('/login');
        $this->get('/admin/orders')->assertRedirect('/login');
    }

    public function test_customer_is_forbidden_from_all_admin_routes(): void
    {
        $this->actingAs($this->customer1);

        $this->get('/admin/dashboard')->assertForbidden();
        $this->get('/admin/buses')->assertForbidden();
        $this->get('/admin/routes')->assertForbidden();
        $this->get('/admin/trips')->assertForbidden();
        $this->get('/admin/orders')->assertForbidden();
    }

    public function test_guest_is_redirected_from_all_customer_routes(): void
    {
        $this->get('/customer/dashboard')->assertRedirect('/login');
        $this->get('/customer/trips')->assertRedirect('/login');
        $this->get('/customer/orders')->assertRedirect('/login');
        $this->get("/customer/trips/{$this->trip->id}/seats")->assertRedirect('/login');
    }

    public function test_admin_is_forbidden_from_all_customer_routes(): void
    {
        $this->actingAs($this->admin);

        $this->get('/customer/dashboard')->assertForbidden();
        $this->get('/customer/trips')->assertForbidden();
        $this->get('/customer/orders')->assertForbidden();
        $this->get("/customer/trips/{$this->trip->id}/seats")->assertForbidden();
    }

    public function test_registration_mass_assignment_cannot_elevate_to_admin(): void
    {
        $response = $this->post('/register', [
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'role' => 'admin',
        ]);

        $response->assertRedirect('/customer/dashboard');

        $user = User::where('email', 'attacker@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role);
    }

    public function test_customer_cannot_view_another_customer_order_detail(): void
    {
        $order = Order::create([
            'user_id' => $this->customer1->id,
            'trip_id' => $this->trip->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 200000,
            'status' => 'pending',
        ]);

        // Customer 2 tries to view Customer 1's order
        $this->actingAs($this->customer2)
            ->get("/customer/orders/{$order->id}")
            ->assertForbidden();
    }

    public function test_customer_cannot_update_order_status_via_admin_route(): void
    {
        $order = Order::create([
            'user_id' => $this->customer1->id,
            'trip_id' => $this->trip->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 200000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->customer1)
            ->patch("/admin/orders/{$order->id}/status", [
                'status' => 'confirmed',
            ])
            ->assertForbidden();

        $this->assertEquals('pending', $order->fresh()->status);
    }
}

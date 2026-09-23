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

class FinalSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Bus $bus;
    protected Route $route;
    protected Trip $trip;
    protected Seat $seat1;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Smoke',
            'email' => 'admin@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->customer = User::create([
            'name' => 'Customer Smoke',
            'email' => 'customer@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->bus = Bus::create([
            'name' => 'CAN Executive Smoke',
            'code' => 'CAN-SMK-01',
            'total_seats' => 30,
        ]);

        $this->seat1 = Seat::create([
            'bus_id' => $this->bus->id,
            'seat_number' => '1A',
        ]);

        $this->route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $this->trip = Trip::create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'departure_at' => Carbon::now('Asia/Jakarta')->addDays(2),
            'arrival_at' => Carbon::now('Asia/Jakarta')->addDays(2)->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $this->order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->trip->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'seat_id' => $this->seat1->id,
            'passenger_name' => 'Budi Santoso',
            'passenger_identity' => '3301234567890001',
            'price' => 250000,
        ]);
    }

    public function test_guest_can_access_landing_and_auth_pages(): void
    {
        $this->get('/')->assertOk();
        $this->get('/tentang')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }

    public function test_customer_can_access_all_customer_pages(): void
    {
        $this->actingAs($this->customer);

        // Dashboard
        $this->get('/customer/dashboard')->assertOk();

        // Trips search & index
        $this->get('/customer/trips')->assertOk();

        // Trip detail
        $this->get("/customer/trips/{$this->trip->id}")->assertOk();

        // Seat selection
        $this->get("/customer/trips/{$this->trip->id}/seats")->assertOk();

        // Booking form (with valid session)
        $this->withSession([
            'booking.trip_id' => $this->trip->id,
            'booking.seat_ids' => [$this->seat1->id],
        ])->get("/customer/trips/{$this->trip->id}/booking")->assertOk();

        // Order history
        $this->get('/customer/orders')->assertOk();

        // Order detail
        $this->get("/customer/orders/{$this->order->id}")->assertOk();
    }

    public function test_admin_can_access_all_admin_pages(): void
    {
        $this->actingAs($this->admin);

        // Admin Dashboard
        $this->get('/admin/dashboard')->assertOk();

        // Buses index & create & edit
        $this->get('/admin/buses')->assertOk();
        $this->get('/admin/buses/create')->assertOk();
        $this->get("/admin/buses/{$this->bus->id}/edit")->assertOk();

        // Routes index & create & edit
        $this->get('/admin/routes')->assertOk();
        $this->get('/admin/routes/create')->assertOk();
        $this->get("/admin/routes/{$this->route->id}/edit")->assertOk();

        // Trips index & create & edit
        $this->get('/admin/trips')->assertOk();
        $this->get('/admin/trips/create')->assertOk();
        $this->get("/admin/trips/{$this->trip->id}/edit")->assertOk();

        // Orders index & show
        $this->get('/admin/orders')->assertOk();
        $this->get("/admin/orders/{$this->order->id}")->assertOk();
    }
}

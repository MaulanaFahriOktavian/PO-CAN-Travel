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
use Tests\TestCase;

class AdvancedProductExperienceTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $admin;
    protected Bus $bus;
    protected Route $route;
    protected Trip $tripCheap;
    protected Trip $tripExpensive;
    protected Seat $seat1;
    protected Seat $seat2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::create([
            'name' => 'Fahri Customer',
            'email' => 'fahri@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $this->admin = User::create([
            'name' => 'Fahri Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->bus = Bus::create([
            'name' => 'CAN Royal 01',
            'code' => 'CAN-RY01',
            'total_seats' => 30,
        ]);

        $this->seat1 = Seat::create([
            'bus_id' => $this->bus->id,
            'seat_number' => '1A',
        ]);

        $this->seat2 = Seat::create([
            'bus_id' => $this->bus->id,
            'seat_number' => '1B',
        ]);

        $this->route = Route::create([
            'origin' => 'Semarang',
            'destination' => 'Bandung',
            'duration' => 360,
        ]);

        $this->tripCheap = Trip::create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'departure_at' => Carbon::now()->addDays(2)->setTime(8, 0),
            'arrival_at' => Carbon::now()->addDays(2)->setTime(14, 0),
            'price' => 150000,
            'status' => 'scheduled',
        ]);

        $this->tripExpensive = Trip::create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'departure_at' => Carbon::now()->addDays(2)->setTime(20, 0),
            'arrival_at' => Carbon::now()->addDays(3)->setTime(2, 0),
            'price' => 250000,
            'status' => 'scheduled',
        ]);
    }

    /**
     * 1. Homepage loads successfully with hero copy and available database routes.
     */
    public function test_homepage_renders_product_landing_with_routes(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Perjalanan antarkota, lebih mudah dipesan.');
        $response->assertSee('Semarang');
        $response->assertSee('Bandung');
        $response->assertSee('Cara Memesan Tiket');
        $response->assertSee('Pertanyaan yang Sering Diajukan');
    }

    /**
     * 2. About page renders comprehensive guide without marketing slop.
     */
    public function test_about_page_renders_product_guides(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('Tentang PO CAN Travel');
        $response->assertSee('Cara Memilih Kursi');
        $response->assertSee('Cara Melihat Pesanan');
        $response->assertSee('Rute Perjalanan yang Dilayani');
    }

    /**
     * 3. Trip search sorting by price works as expected.
     */
    public function test_customer_can_sort_trips_by_price(): void
    {
        // Price Ascending: cheap first
        $responseAsc = $this->actingAs($this->customer)
            ->get(route('customer.trips.index', ['sort' => 'price_asc']));

        $responseAsc->assertStatus(200);
        $tripsAsc = $responseAsc->viewData('trips');
        $this->assertEquals($this->tripCheap->id, $tripsAsc->first()->id);

        // Price Descending: expensive first
        $responseDesc = $this->actingAs($this->customer)
            ->get(route('customer.trips.index', ['sort' => 'price_desc']));

        $responseDesc->assertStatus(200);
        $tripsDesc = $responseDesc->viewData('trips');
        $this->assertEquals($this->tripExpensive->id, $tripsDesc->first()->id);
    }

    /**
     * 4. Remaining seats count is properly computed on trip index.
     */
    public function test_trip_displays_accurate_remaining_seats_count(): void
    {
        // Create an order booking 1 seat
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripCheap->id,
            'order_code' => 'ORD-TEST1234',
            'total_amount' => 150000,
            'status' => 'confirmed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $this->seat1->id,
            'passenger_name' => 'Fahri',
            'passenger_identity' => '3301234567890001',
            'price' => 150000,
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.trips.index'));
        $response->assertStatus(200);

        // Total bus seats 30 - 1 booked = 29
        $response->assertSee('Sisa 29 kursi');
    }

    /**
     * 5. Customer can search order history by order code.
     */
    public function test_customer_can_search_order_history_by_order_code(): void
    {
        $orderTarget = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripCheap->id,
            'order_code' => 'ORD-FINDME99',
            'total_amount' => 150000,
            'status' => 'pending',
        ]);

        $orderOther = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripExpensive->id,
            'order_code' => 'ORD-IGNORE11',
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.orders.index', ['search' => 'FINDME99']));

        $response->assertStatus(200);
        $response->assertSee('ORD-FINDME99');
        $response->assertDontSee('ORD-IGNORE11');
    }

    /**
     * 6. Customer dashboard displays active orders.
     */
    public function test_customer_dashboard_displays_active_orders(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripCheap->id,
            'order_code' => 'ORD-ACTIVE01',
            'total_amount' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('ORD-ACTIVE01');
        $response->assertSee('Pesanan Aktif');
        $response->assertSee('Menunggu Pembayaran');
    }

    /**
     * 7. Admin dashboard displays pending orders requiring attention.
     */
    public function test_admin_dashboard_displays_pending_orders_needing_attention(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripCheap->id,
            'order_code' => 'ORD-ADMINATTN',
            'total_amount' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('ORD-ADMINATTN');
        $response->assertSee('Pesanan Membutuhkan Perhatian');
    }

    /**
     * 8. Admin trip table displays seat occupancy rate.
     */
    public function test_admin_trip_table_displays_seat_occupancy_rate(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripCheap->id,
            'order_code' => 'ORD-OCCUPANCY',
            'total_amount' => 150000,
            'status' => 'confirmed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $this->seat1->id,
            'passenger_name' => 'Fahri',
            'passenger_identity' => '3301234567890001',
            'price' => 150000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.trips.index'));

        $response->assertStatus(200);
        $response->assertSee('Okupansi Kursi');
        // 1 / 30 seats (3%)
        $response->assertSee('1 / 30 Kursi (3%)');
    }
}

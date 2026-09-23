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

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;
    private User $customer2;
    private Bus $bus;
    private Route $route;
    private Trip $trip;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.orders@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->customer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->customer2 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $seatNumbers = Bus::generateSeatNumbers(30);
        foreach ($seatNumbers as $num) {
            Seat::create([
                'bus_id' => $this->bus->id,
                'seat_number' => $num,
            ]);
        }

        $this->route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $this->trip = Trip::create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'departure_at' => Carbon::parse('2026-09-25 08:00:00', 'Asia/Jakarta'),
            'arrival_at' => Carbon::parse('2026-09-25 16:00:00', 'Asia/Jakarta'),
            'price' => 250000,
            'status' => 'scheduled',
        ]);
    }

    private function createOrder(User $user, string $status = 'pending', int $price = 250000, ?Carbon $createdAt = null): Order
    {
        $seat = $this->bus->seats()->whereNotIn('id', OrderItem::pluck('seat_id'))->first();

        $order = Order::create([
            'user_id' => $user->id,
            'trip_id' => $this->trip->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => $price,
            'status' => $status,
        ]);

        if ($createdAt) {
            $order->created_at = $createdAt;
            $order->save();
        }

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => $user->name,
            'passenger_identity' => '3201123456780001',
            'price' => $price,
        ]);

        return $order;
    }

    // ==========================================
    // 1. AUTHORIZATION
    // ==========================================

    public function test_guest_cannot_access_admin_orders_index(): void
    {
        $this->get(route('admin.orders.index'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_admin_orders_show(): void
    {
        $order = $this->createOrder($this->customer);
        $this->get(route('admin.orders.show', $order))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_update_order_status(): void
    {
        $order = $this->createOrder($this->customer);
        $this->patch(route('admin.orders.status', $order), ['status' => 'confirmed'])->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_orders_index(): void
    {
        $this->actingAs($this->customer)
            ->get(route('admin.orders.index'))
            ->assertStatus(403);
    }

    public function test_customer_cannot_access_admin_orders_show(): void
    {
        $order = $this->createOrder($this->customer);
        $this->actingAs($this->customer)
            ->get(route('admin.orders.show', $order))
            ->assertStatus(403);
    }

    public function test_customer_cannot_update_order_status(): void
    {
        $order = $this->createOrder($this->customer);
        $this->actingAs($this->customer)
            ->patch(route('admin.orders.status', $order), ['status' => 'confirmed'])
            ->assertStatus(403);
    }

    public function test_admin_can_access_admin_orders_index(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.orders.index'))
            ->assertStatus(200)
            ->assertSee('Kelola Pesanan');
    }

    // ==========================================
    // 2. LISTING
    // ==========================================

    public function test_admin_sees_customer_orders(): void
    {
        $orderA = $this->createOrder($this->customer);
        $orderB = $this->createOrder($this->customer2);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertSee($orderA->order_code);
        $response->assertSee($orderB->order_code);
    }

    public function test_order_displays_trip_and_customer_information(): void
    {
        $order = $this->createOrder($this->customer);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertSee($this->customer->name);
        $response->assertSee($this->customer->email);
        $response->assertSee('Jakarta');
        $response->assertSee('Jepara');
        $response->assertSee('CAN Executive 01');
        $response->assertSee('Rp' . number_format($order->total_amount, 0, ',', '.'));
    }

    public function test_orders_sorted_newest_first(): void
    {
        $orderOld = $this->createOrder($this->customer, 'pending', 250000, Carbon::now()->subDays(2));
        $orderNew = $this->createOrder($this->customer2, 'pending', 250000, Carbon::now());

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $content = $response->getContent();
        $posNew = strpos($content, $orderNew->order_code);
        $posOld = strpos($content, $orderOld->order_code);

        $this->assertTrue($posNew !== false && $posOld !== false);
        $this->assertTrue($posNew < $posOld, 'Order terbaru harus tampil lebih dahulu.');
    }

    public function test_orders_pagination_works(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $this->createOrder($this->customer);
        }

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('page=2');
    }

    // ==========================================
    // 3. FILTER
    // ==========================================

    public function test_filter_pending_orders(): void
    {
        $orderPending = $this->createOrder($this->customer, 'pending');
        $orderConfirmed = $this->createOrder($this->customer2, 'confirmed');

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'pending']));

        $response->assertStatus(200);
        $response->assertSee($orderPending->order_code);
        $response->assertDontSee($orderConfirmed->order_code);
    }

    public function test_filter_confirmed_orders(): void
    {
        $orderPending = $this->createOrder($this->customer, 'pending');
        $orderConfirmed = $this->createOrder($this->customer2, 'confirmed');

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'confirmed']));

        $response->assertStatus(200);
        $response->assertSee($orderConfirmed->order_code);
        $response->assertDontSee($orderPending->order_code);
    }

    public function test_filter_cancelled_orders(): void
    {
        $orderCancelled = $this->createOrder($this->customer, 'cancelled');
        $orderCompleted = $this->createOrder($this->customer2, 'completed');

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'cancelled']));

        $response->assertStatus(200);
        $response->assertSee($orderCancelled->order_code);
        $response->assertDontSee($orderCompleted->order_code);
    }

    public function test_filter_completed_orders(): void
    {
        $orderCancelled = $this->createOrder($this->customer, 'cancelled');
        $orderCompleted = $this->createOrder($this->customer2, 'completed');

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'completed']));

        $response->assertStatus(200);
        $response->assertSee($orderCompleted->order_code);
        $response->assertDontSee($orderCancelled->order_code);
    }

    public function test_invalid_status_filter_falls_back_to_all_orders(): void
    {
        $orderPending = $this->createOrder($this->customer, 'pending');
        $orderConfirmed = $this->createOrder($this->customer2, 'confirmed');

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'unknown_status']));

        $response->assertStatus(200);
        $response->assertSee($orderPending->order_code);
        $response->assertSee($orderConfirmed->order_code);
    }

    public function test_filter_keeps_pagination_query_string(): void
    {
        for ($i = 0; $i < 12; $i++) {
            $this->createOrder($this->customer, 'pending');
        }

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'pending']));

        $response->assertStatus(200);
        $response->assertSee('status=pending');
        $response->assertSee('page=2');
    }

    // ==========================================
    // 4. SEARCH
    // ==========================================

    public function test_search_by_order_code(): void
    {
        $orderA = $this->createOrder($this->customer);
        $orderB = $this->createOrder($this->customer2);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['search' => $orderA->order_code]));

        $response->assertStatus(200);
        $response->assertSee($orderA->order_code);
        $response->assertDontSee($orderB->order_code);
    }

    public function test_search_by_customer_name(): void
    {
        $orderA = $this->createOrder($this->customer);
        $orderB = $this->createOrder($this->customer2);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['search' => 'Budi Santoso']));

        $response->assertStatus(200);
        $response->assertSee($orderA->order_code);
        $response->assertDontSee($orderB->order_code);
    }

    public function test_search_by_customer_email(): void
    {
        $orderA = $this->createOrder($this->customer);
        $orderB = $this->createOrder($this->customer2);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['search' => 'dewi.lestari']));

        $response->assertStatus(200);
        $response->assertSee($orderB->order_code);
        $response->assertDontSee($orderA->order_code);
    }

    public function test_search_does_not_leak_irrelevant_orders(): void
    {
        $orderA = $this->createOrder($this->customer);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.index', ['search' => 'NonExistentString']));

        $response->assertStatus(200);
        $response->assertDontSee($orderA->order_code);
        $response->assertSee('Tidak ada pesanan ditemukan');
    }

    // ==========================================
    // 5. DETAIL
    // ==========================================

    public function test_admin_can_view_order_detail(): void
    {
        $order = $this->createOrder($this->customer);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee($order->order_code);
        $response->assertSee($this->customer->name);
        $response->assertSee($this->customer->email);
        $response->assertSee('CAN Executive 01');
        $response->assertSee('3201123456780001');
        $response->assertSee('Rp250.000');
    }

    // ==========================================
    // 6. STATUS TRANSITION RULES
    // ==========================================

    public function test_pending_to_confirmed_succeeds(): void
    {
        $order = $this->createOrder($this->customer, 'pending');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('confirmed', $order->status);
    }

    public function test_pending_to_cancelled_succeeds(): void
    {
        $order = $this->createOrder($this->customer, 'pending');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'cancelled',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
    }

    public function test_confirmed_to_completed_succeeds(): void
    {
        $order = $this->createOrder($this->customer, 'confirmed');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'completed',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    public function test_confirmed_to_cancelled_succeeds(): void
    {
        $order = $this->createOrder($this->customer, 'confirmed');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'cancelled',
        ]);

        $response->assertRedirect(route('admin.orders.show', $order));
        $response->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
    }

    public function test_completed_to_pending_rejected(): void
    {
        $order = $this->createOrder($this->customer, 'completed');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'pending',
        ]);

        $response->assertSessionHasErrors(['status']);
        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    public function test_completed_to_confirmed_rejected(): void
    {
        $order = $this->createOrder($this->customer, 'completed');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertSessionHasErrors(['status']);
        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    public function test_completed_to_cancelled_rejected(): void
    {
        $order = $this->createOrder($this->customer, 'completed');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'cancelled',
        ]);

        $response->assertSessionHasErrors(['status']);
        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    public function test_cancelled_to_pending_rejected(): void
    {
        $order = $this->createOrder($this->customer, 'cancelled');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'pending',
        ]);

        $response->assertSessionHasErrors(['status']);
        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
    }

    public function test_cancelled_to_confirmed_rejected(): void
    {
        $order = $this->createOrder($this->customer, 'cancelled');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertSessionHasErrors(['status']);
        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
    }

    public function test_cancelled_to_completed_rejected(): void
    {
        $order = $this->createOrder($this->customer, 'cancelled');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'completed',
        ]);

        $response->assertSessionHasErrors(['status']);
        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
    }

    public function test_invalid_status_value_rejected(): void
    {
        $order = $this->createOrder($this->customer, 'pending');

        $response = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'invalid_random_status',
        ]);

        $response->assertSessionHasErrors(['status']);
        $order->refresh();
        $this->assertEquals('pending', $order->status);
    }
}

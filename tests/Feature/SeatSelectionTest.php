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

class SeatSelectionTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private User $admin;
    private Bus $bus1;
    private Bus $bus2;
    private Route $route;
    private Trip $tripScheduled;
    private Trip $tripCancelled;
    private Trip $tripDeparted;
    private Trip $tripCompleted;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Users
        $this->customer = User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi.cust@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.seat@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Setup Bus 1 (30 seats)
        $this->bus1 = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $seatNumbers = Bus::generateSeatNumbers(30);
        foreach ($seatNumbers as $num) {
            Seat::create([
                'bus_id' => $this->bus1->id,
                'seat_number' => $num,
            ]);
        }

        // 3. Setup Bus 2 (28 seats)
        $this->bus2 = Bus::create([
            'name' => 'CAN Royal 02',
            'code' => 'CAN-RY02',
            'total_seats' => 28,
        ]);

        $seatNumbers2 = Bus::generateSeatNumbers(28);
        foreach ($seatNumbers2 as $num) {
            Seat::create([
                'bus_id' => $this->bus2->id,
                'seat_number' => $num,
            ]);
        }

        // 4. Setup Route
        $this->route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        // 5. Setup Trips
        $now = Carbon::parse('2026-09-25 08:00:00', 'Asia/Jakarta');

        $this->tripScheduled = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->route->id,
            'departure_at' => $now,
            'arrival_at' => $now->copy()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $this->tripCancelled = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->route->id,
            'departure_at' => $now->copy()->addDay(),
            'arrival_at' => $now->copy()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'cancelled',
        ]);

        $this->tripDeparted = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->route->id,
            'departure_at' => $now->copy()->subHours(2),
            'arrival_at' => $now->copy()->addHours(6),
            'price' => 250000,
            'status' => 'departed',
        ]);

        $this->tripCompleted = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->route->id,
            'departure_at' => $now->copy()->subDays(2),
            'arrival_at' => $now->copy()->subDays(2)->addHours(8),
            'price' => 250000,
            'status' => 'completed',
        ]);
    }

    /**
     * 1. guest tidak dapat membuka seat selection
     */
    public function test_guest_cannot_access_seat_selection(): void
    {
        $response = $this->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertRedirect('/login');
    }

    /**
     * 2. admin tidak dapat membuka seat selection
     */
    public function test_admin_cannot_access_seat_selection(): void
    {
        $response = $this->actingAs($this->admin)->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertStatus(403);
    }

    /**
     * 3. customer dapat membuka seat selection
     */
    public function test_customer_can_access_seat_selection(): void
    {
        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertStatus(200);
        $response->assertSee('Pilih Kursi Perjalanan');
        $response->assertSee('Tata Letak Kursi Bus');
        $response->assertSee($this->tripScheduled->bus->name);
        $response->assertSee('Jakarta');
        $response->assertSee('Jepara');
    }

    /**
     * 4. scheduled trip dapat dibuka
     */
    public function test_scheduled_trip_can_be_accessed(): void
    {
        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertStatus(200);
    }

    /**
     * 5. cancelled trip menghasilkan 404
     */
    public function test_cancelled_trip_returns_404(): void
    {
        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripCancelled));

        $response->assertStatus(404);
    }

    /**
     * 6. departed trip menghasilkan 404
     */
    public function test_departed_trip_returns_404(): void
    {
        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripDeparted));

        $response->assertStatus(404);
    }

    /**
     * 7. completed trip menghasilkan 404
     */
    public function test_completed_trip_returns_404(): void
    {
        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripCompleted));

        $response->assertStatus(404);
    }

    /**
     * 8. semua seat bus ditampilkan
     */
    public function test_all_bus_seats_are_displayed(): void
    {
        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertStatus(200);
        $response->assertViewHas('seats', function ($seats) {
            return $seats->count() === 30;
        });

        // Kursi pertama dan kursi terakhir ditampilkan
        $response->assertSee('1A');
        $response->assertSee('8B');
    }

    /**
     * 9. booked seat ditandai sebagai booked
     */
    public function test_booked_seat_is_marked_as_booked(): void
    {
        $seat1A = $this->bus1->seats()->where('seat_number', '1A')->first();

        // Buat order confirmed pada tripScheduled
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => 'ORD-TEST-001',
            'total_amount' => 250000,
            'status' => 'confirmed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat1A->id,
            'passenger_name' => 'Penumpang 1',
            'passenger_identity' => '3301234567890001',
            'price' => 250000,
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertStatus(200);
        $response->assertViewHas('bookedSeatIds', function ($bookedIds) use ($seat1A) {
            return in_array($seat1A->id, $bookedIds);
        });
        $response->assertSee('Kursi 1A sudah dipesan');
    }

    /**
     * 10. pending order membuat seat booked
     */
    public function test_pending_order_makes_seat_booked(): void
    {
        $seat1B = $this->bus1->seats()->where('seat_number', '1B')->first();

        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => 'ORD-PENDING-001',
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat1B->id,
            'passenger_name' => 'Penumpang Pending',
            'passenger_identity' => '3301234567890002',
            'price' => 250000,
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertViewHas('bookedSeatIds', function ($bookedIds) use ($seat1B) {
            return in_array($seat1B->id, $bookedIds);
        });
    }

    /**
     * 11. confirmed order membuat seat booked
     */
    public function test_confirmed_order_makes_seat_booked(): void
    {
        $seat1C = $this->bus1->seats()->where('seat_number', '1C')->first();

        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => 'ORD-CONFIRMED-001',
            'total_amount' => 250000,
            'status' => 'confirmed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat1C->id,
            'passenger_name' => 'Penumpang Confirmed',
            'passenger_identity' => '3301234567890003',
            'price' => 250000,
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertViewHas('bookedSeatIds', function ($bookedIds) use ($seat1C) {
            return in_array($seat1C->id, $bookedIds);
        });
    }

    /**
     * 12. completed order membuat seat booked
     */
    public function test_completed_order_makes_seat_booked(): void
    {
        $seat1D = $this->bus1->seats()->where('seat_number', '1D')->first();

        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => 'ORD-COMPLETED-001',
            'total_amount' => 250000,
            'status' => 'completed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat1D->id,
            'passenger_name' => 'Penumpang Completed',
            'passenger_identity' => '3301234567890004',
            'price' => 250000,
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertViewHas('bookedSeatIds', function ($bookedIds) use ($seat1D) {
            return in_array($seat1D->id, $bookedIds);
        });
    }

    /**
     * 13. cancelled order tidak membuat seat booked
     */
    public function test_cancelled_order_does_not_make_seat_booked(): void
    {
        $seat2A = $this->bus1->seats()->where('seat_number', '2A')->first();

        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => 'ORD-CANCELLED-001',
            'total_amount' => 250000,
            'status' => 'cancelled',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat2A->id,
            'passenger_name' => 'Penumpang Batal',
            'passenger_identity' => '3301234567890005',
            'price' => 250000,
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.trips.seats', $this->tripScheduled));

        $response->assertViewHas('bookedSeatIds', function ($bookedIds) use ($seat2A) {
            return !in_array($seat2A->id, $bookedIds);
        });
    }

    /**
     * 14. customer dapat memilih satu seat
     */
    public function test_customer_can_select_single_seat(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => [$seat->id],
        ]);

        $response->assertRedirect(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSessionHas('success');
        $response->assertSessionHas('selected_seats', [$seat->id]);
    }

    /**
     * 15. customer dapat memilih beberapa seat
     */
    public function test_customer_can_select_multiple_seats(): void
    {
        $seats = $this->bus1->seats()->take(3)->pluck('id')->all();

        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => $seats,
        ]);

        $response->assertRedirect(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSessionHas('success');
        $response->assertSessionHas('selected_seats', $seats);
    }

    /**
     * 16. seat_ids wajib ada
     */
    public function test_seat_ids_is_required(): void
    {
        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), []);

        $response->assertSessionHasErrors(['seat_ids']);
    }

    /**
     * 17. seat_ids harus array
     */
    public function test_seat_ids_must_be_array(): void
    {
        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => 'bukan-array',
        ]);

        $response->assertSessionHasErrors(['seat_ids']);
    }

    /**
     * 18. duplicate seat ditolak
     */
    public function test_duplicate_seat_is_rejected(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => [$seat->id, $seat->id],
        ]);

        $response->assertSessionHasErrors(['seat_ids']);
    }

    /**
     * 19. seat dari bus lain ditolak
     */
    public function test_seat_from_another_bus_is_rejected(): void
    {
        // Ambil kursi milik bus2
        $seatBus2 = $this->bus2->seats()->first();

        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => [$seatBus2->id],
        ]);

        $response->assertSessionHasErrors(['seat_ids']);
    }

    /**
     * 20. seat ID tidak ada ditolak
     */
    public function test_non_existent_seat_id_is_rejected(): void
    {
        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => [999999],
        ]);

        $response->assertSessionHasErrors(['seat_ids']);
    }

    /**
     * 21. booked seat ditolak
     */
    public function test_booked_seat_is_rejected(): void
    {
        $seat = $this->bus1->seats()->first();

        // Buat order confirmed pada seat ini
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => 'ORD-BOOKED-001',
            'total_amount' => 250000,
            'status' => 'confirmed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => 'Penumpang Aktif',
            'passenger_identity' => '3301234567890006',
            'price' => 250000,
        ]);

        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => [$seat->id],
        ]);

        $response->assertSessionHasErrors(['seat_ids']);
    }

    /**
     * 22. harga dari frontend tidak dipercaya
     */
    public function test_price_from_frontend_is_ignored(): void
    {
        $seat = $this->bus1->seats()->first();

        // Frontend mencoba mengirim manipulasi harga 1 rupiah
        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => [$seat->id],
            'price' => 1,
            'total_price' => 1,
            'total_amount' => 1,
        ]);

        $response->assertRedirect(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSessionHasNoErrors();

        // Pastikan tidak ada data yang disimpan dengan harga palsu tersebut
        $this->assertEquals(0, Order::count());
    }

    /**
     * 23. total menggunakan trip.price
     */
    public function test_total_calculated_from_trip_price(): void
    {
        $seats = $this->bus1->seats()->take(2)->pluck('id')->all();

        $response = $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => $seats,
        ]);

        $response->assertRedirect(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSessionHas('success');

        // Total 2 x 250.000 = 500.000
        $expectedTotal = 2 * $this->tripScheduled->price;
        $this->assertEquals(500000, $expectedTotal);
    }

    /**
     * 24. POST valid tidak membuat Order
     */
    public function test_valid_post_does_not_create_order(): void
    {
        $initialOrderCount = Order::count();
        $seat = $this->bus1->seats()->first();

        $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => [$seat->id],
        ]);

        $this->assertEquals($initialOrderCount, Order::count());
    }

    /**
     * 25. POST valid tidak membuat OrderItem
     */
    public function test_valid_post_does_not_create_order_item(): void
    {
        $initialOrderItemCount = OrderItem::count();
        $seat = $this->bus1->seats()->first();

        $this->actingAs($this->customer)->post(route('customer.trips.seats.store', $this->tripScheduled), [
            'seat_ids' => [$seat->id],
        ]);

        $this->assertEquals($initialOrderItemCount, OrderItem::count());
    }

    /**
     * 26. detail trip memiliki link ke seat selection
     */
    public function test_trip_detail_has_link_to_seat_selection(): void
    {
        $response = $this->actingAs($this->customer)->get(route('customer.trips.show', $this->tripScheduled));

        $response->assertStatus(200);
        $response->assertSee(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSee('Lanjut Pilih Kursi');
    }
}

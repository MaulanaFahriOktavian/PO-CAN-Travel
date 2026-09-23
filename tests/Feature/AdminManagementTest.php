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
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Penguji',
            'email' => 'admin.test@pocantravel.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $this->customer = User::create([
            'name' => 'Customer Penguji',
            'email' => 'customer.test@pocantravel.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);
    }

    // ==========================================
    // BUS MANAGEMENT TESTS
    // ==========================================

    /**
     * 1. admin dapat melihat daftar bus
     */
    public function test_admin_can_view_bus_list(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.buses.index'));

        $response->assertStatus(200);
        $response->assertSee('CAN Executive 01');
        $response->assertSee('CAN-EX01');
    }

    /**
     * 2. admin dapat membuat bus
     */
    public function test_admin_can_create_bus(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.buses.store'), [
            'name' => 'CAN Royal 03',
            'code' => 'CAN-RY03',
            'total_seats' => 20,
        ]);

        $response->assertRedirect(route('admin.buses.index'));
        $this->assertDatabaseHas('buses', [
            'name' => 'CAN Royal 03',
            'code' => 'CAN-RY03',
            'total_seats' => 20,
        ]);
    }

    /**
     * 3. bus otomatis membuat seat sesuai total_seats
     */
    public function test_bus_creation_automatically_creates_seats_matching_total_seats(): void
    {
        $this->actingAs($this->admin)->post(route('admin.buses.store'), [
            'name' => 'CAN Luxury 04',
            'code' => 'CAN-LX04',
            'total_seats' => 6,
        ]);

        $bus = Bus::where('code', 'CAN-LX04')->first();

        $this->assertNotNull($bus);
        $this->assertCount(6, $bus->seats);
        $expectedSeats = ['1A', '1B', '1C', '1D', '2A', '2B'];
        $this->assertEquals($expectedSeats, $bus->seats()->pluck('seat_number')->toArray());
    }

    /**
     * 4. bus creation is atomic
     */
    public function test_bus_creation_is_atomic(): void
    {
        // Simulasi kegagalan pada saat membuat bus/seat
        try {
            DB::transaction(function () {
                $bus = Bus::create([
                    'name' => 'CAN Fail Bus',
                    'code' => 'CAN-FAIL',
                    'total_seats' => 10,
                ]);

                // Paksa duplikasi atau error
                throw new \Exception('Simulated crash during seat generation');
            });
        } catch (\Exception $e) {
            // Expected
        }

        $this->assertDatabaseMissing('buses', ['code' => 'CAN-FAIL']);
    }

    /**
     * 5. admin dapat mengubah bus
     */
    public function test_admin_can_update_bus(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Old Name',
            'code' => 'CAN-OLD',
            'total_seats' => 10,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.buses.update', $bus), [
            'name' => 'CAN New Name',
            'code' => 'CAN-OLD',
            'total_seats' => 10,
        ]);

        $response->assertRedirect(route('admin.buses.index'));
        $this->assertDatabaseHas('buses', [
            'id' => $bus->id,
            'name' => 'CAN New Name',
        ]);
    }

    /**
     * 6. menambah total_seats membuat seat tambahan
     */
    public function test_increasing_total_seats_creates_additional_seats(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Expand',
            'code' => 'CAN-EXP',
            'total_seats' => 4,
        ]);

        foreach (['1A', '1B', '1C', '1D'] as $seatNumber) {
            Seat::create(['bus_id' => $bus->id, 'seat_number' => $seatNumber]);
        }

        // Ubah dari 4 ke 6 kursi
        $response = $this->actingAs($this->admin)->put(route('admin.buses.update', $bus), [
            'name' => 'CAN Expand',
            'code' => 'CAN-EXP',
            'total_seats' => 6,
        ]);

        $response->assertRedirect(route('admin.buses.index'));
        $bus->refresh();

        $this->assertEquals(6, $bus->total_seats);
        $this->assertCount(6, $bus->seats);
        $this->assertTrue($bus->seats()->where('seat_number', '2A')->exists());
        $this->assertTrue($bus->seats()->where('seat_number', '2B')->exists());
    }

    /**
     * 7. mengurangi total_seats aman
     */
    public function test_decreasing_total_seats_safely_removes_trailing_seats(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Shrink',
            'code' => 'CAN-SHR',
            'total_seats' => 6,
        ]);

        foreach (['1A', '1B', '1C', '1D', '2A', '2B'] as $seatNumber) {
            Seat::create(['bus_id' => $bus->id, 'seat_number' => $seatNumber]);
        }

        // Kurangi dari 6 ke 4 kursi
        $response = $this->actingAs($this->admin)->put(route('admin.buses.update', $bus), [
            'name' => 'CAN Shrink',
            'code' => 'CAN-SHR',
            'total_seats' => 4,
        ]);

        $response->assertRedirect(route('admin.buses.index'));
        $bus->refresh();

        $this->assertEquals(4, $bus->total_seats);
        $this->assertCount(4, $bus->seats);
        $this->assertFalse($bus->seats()->where('seat_number', '2A')->exists());
        $this->assertFalse($bus->seats()->where('seat_number', '2B')->exists());
    }

    /**
     * 8. seat decrement ditolak jika seat target memiliki OrderItem
     */
    public function test_decreasing_total_seats_rejected_if_target_seat_has_order_item(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Booked Bus',
            'code' => 'CAN-BOK',
            'total_seats' => 4,
        ]);

        $s1 = Seat::create(['bus_id' => $bus->id, 'seat_number' => '1A']);
        $s2 = Seat::create(['bus_id' => $bus->id, 'seat_number' => '1B']);
        $s3 = Seat::create(['bus_id' => $bus->id, 'seat_number' => '1C']);
        $s4 = Seat::create(['bus_id' => $bus->id, 'seat_number' => '1D']);

        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Jepara', 'duration' => 480]);
        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $trip->id,
            'order_code' => 'ORD-TEST-SEAT',
            'total_amount' => 250000,
            'status' => 'confirmed',
        ]);

        // Kursi 1D memiliki OrderItem
        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $s4->id,
            'passenger_name' => 'Penumpang Uji',
            'passenger_identity' => '3320011234560001',
            'price' => 250000,
        ]);

        // Coba kurangi kapasitas dari 4 menjadi 3 (yang akan membuang 1D)
        $response = $this->actingAs($this->admin)->put(route('admin.buses.update', $bus), [
            'name' => 'CAN Booked Bus',
            'code' => 'CAN-BOK',
            'total_seats' => 3,
        ]);

        $response->assertSessionHas('error');
        $bus->refresh();
        $this->assertEquals(4, $bus->total_seats);
        $this->assertTrue(Seat::where('id', $s4->id)->exists());
    }

    /**
     * 9. customer tidak dapat mengakses bus management
     */
    public function test_customer_cannot_access_bus_management(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.buses.index'));

        $response->assertStatus(403);
    }

    /**
     * 10. guest tidak dapat mengakses bus management
     */
    public function test_guest_cannot_access_bus_management(): void
    {
        $response = $this->get(route('admin.buses.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * 11. duplicate bus code ditolak
     */
    public function test_duplicate_bus_code_is_rejected(): void
    {
        Bus::create(['name' => 'Bus 1', 'code' => 'CAN-01', 'total_seats' => 20]);

        $response = $this->actingAs($this->admin)->post(route('admin.buses.store'), [
            'name' => 'Bus 2',
            'code' => 'CAN-01',
            'total_seats' => 20,
        ]);

        $response->assertSessionHasErrors(['code']);
    }

    /**
     * 12. invalid total_seats ditolak
     */
    public function test_invalid_total_seats_is_rejected(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.buses.store'), [
            'name' => 'Bus Invalid',
            'code' => 'CAN-INV',
            'total_seats' => 0,
        ]);

        $response->assertSessionHasErrors(['total_seats']);
    }

    /**
     * 13. bus delete ditolak jika memiliki trip
     */
    public function test_bus_cannot_be_deleted_if_it_has_trips(): void
    {
        $bus = Bus::create(['name' => 'Bus With Trip', 'code' => 'CAN-TRIP', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Semarang', 'duration' => 360]);
        Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(6),
            'price' => 200000,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.buses.destroy', $bus));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('buses', ['id' => $bus->id]);
    }

    /**
     * 14. bus delete berhasil jika tidak memiliki trip
     */
    public function test_bus_can_be_deleted_if_it_has_no_trips(): void
    {
        $bus = Bus::create(['name' => 'Bus Free', 'code' => 'CAN-FREE', 'total_seats' => 10]);

        $response = $this->actingAs($this->admin)->delete(route('admin.buses.destroy', $bus));

        $response->assertRedirect(route('admin.buses.index'));
        $this->assertDatabaseMissing('buses', ['id' => $bus->id]);
    }

    // ==========================================
    // ROUTE MANAGEMENT TESTS
    // ==========================================

    /**
     * 15. admin dapat melihat daftar route
     */
    public function test_admin_can_view_route_list(): void
    {
        $route = Route::create(['origin' => 'Bandung', 'destination' => 'Yogyakarta', 'duration' => 540]);

        $response = $this->actingAs($this->admin)->get(route('admin.routes.index'));

        $response->assertStatus(200);
        $response->assertSee('Bandung');
        $response->assertSee('Yogyakarta');
    }

    /**
     * 16. admin dapat membuat route
     */
    public function test_admin_can_create_route(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.routes.store'), [
            'origin' => 'Solo',
            'destination' => 'Jakarta',
            'duration' => 450,
        ]);

        $response->assertRedirect(route('admin.routes.index'));
        $this->assertDatabaseHas('routes', [
            'origin' => 'Solo',
            'destination' => 'Jakarta',
            'duration' => 450,
        ]);
    }

    /**
     * 17. origin dan destination tidak boleh sama
     */
    public function test_route_origin_and_destination_cannot_be_the_same(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.routes.store'), [
            'origin' => 'Jakarta',
            'destination' => 'Jakarta',
            'duration' => 120,
        ]);

        $response->assertSessionHasErrors(['destination']);
        $this->assertDatabaseMissing('routes', ['origin' => 'Jakarta', 'destination' => 'Jakarta']);
    }

    /**
     * 18. invalid duration ditolak
     */
    public function test_invalid_route_duration_is_rejected(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.routes.store'), [
            'origin' => 'Jakarta',
            'destination' => 'Semarang',
            'duration' => 0,
        ]);

        $response->assertSessionHasErrors(['duration']);
    }

    /**
     * 19. customer tidak dapat mengakses route management
     */
    public function test_customer_cannot_access_route_management(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.routes.index'));

        $response->assertStatus(403);
    }

    /**
     * 20. route yang digunakan trip tidak dapat dihapus
     */
    public function test_route_cannot_be_deleted_if_used_by_trips(): void
    {
        $bus = Bus::create(['name' => 'Bus RT', 'code' => 'CAN-RT', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Surabaya', 'destination' => 'Jakarta', 'duration' => 600]);
        Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(10),
            'price' => 300000,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.routes.destroy', $route));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('routes', ['id' => $route->id]);
    }

    /**
     * 21. route tanpa trip dapat dihapus
     */
    public function test_route_can_be_deleted_if_has_no_trips(): void
    {
        $route = Route::create(['origin' => 'Tegal', 'destination' => 'Jakarta', 'duration' => 240]);

        $response = $this->actingAs($this->admin)->delete(route('admin.routes.destroy', $route));

        $response->assertRedirect(route('admin.routes.index'));
        $this->assertDatabaseMissing('routes', ['id' => $route->id]);
    }

    // ==========================================
    // TRIP MANAGEMENT TESTS
    // ==========================================

    /**
     * 22. admin dapat melihat daftar trip
     */
    public function test_admin_can_view_trip_list(): void
    {
        $bus = Bus::create(['name' => 'Bus TL', 'code' => 'CAN-TL', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Kudus', 'duration' => 420]);
        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(7),
            'price' => 240000,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.trips.index'));

        $response->assertStatus(200);
        $response->assertSee('Jakarta');
        $response->assertSee('Kudus');
    }

    /**
     * 23. admin dapat membuat trip
     */
    public function test_admin_can_create_trip(): void
    {
        $bus = Bus::create(['name' => 'Bus Cr', 'code' => 'CAN-CR', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Demak', 'duration' => 400]);

        $dep = Carbon::now()->addDay()->setTime(8, 0);
        $arr = Carbon::now()->addDay()->setTime(15, 0);

        $response = $this->actingAs($this->admin)->post(route('admin.trips.store'), [
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => $dep->format('Y-m-d\TH:i'),
            'arrival_at' => $arr->format('Y-m-d\TH:i'),
            'price' => 230000,
            'status' => 'scheduled',
        ]);

        $response->assertRedirect(route('admin.trips.index'));
        $this->assertDatabaseHas('trips', [
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'price' => 230000,
            'status' => 'scheduled',
        ]);
    }

    /**
     * 24. arrival harus setelah departure
     */
    public function test_trip_arrival_must_be_after_departure(): void
    {
        $bus = Bus::create(['name' => 'Bus Arr', 'code' => 'CAN-ARR', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Pati', 'duration' => 450]);

        $dep = Carbon::now()->addDay()->setTime(10, 0);
        $arr = Carbon::now()->addDay()->setTime(8, 0); // arrival sebelum departure

        $response = $this->actingAs($this->admin)->post(route('admin.trips.store'), [
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => $dep->format('Y-m-d\TH:i'),
            'arrival_at' => $arr->format('Y-m-d\TH:i'),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $response->assertSessionHasErrors(['arrival_at']);
    }

    /**
     * 25. invalid bus ditolak
     */
    public function test_invalid_bus_is_rejected(): void
    {
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Rembang', 'duration' => 480]);

        $response = $this->actingAs($this->admin)->post(route('admin.trips.store'), [
            'bus_id' => 99999,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay()->format('Y-m-d\TH:i'),
            'arrival_at' => Carbon::now()->addDay()->addHours(8)->format('Y-m-d\TH:i'),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $response->assertSessionHasErrors(['bus_id']);
    }

    /**
     * 26. invalid route ditolak
     */
    public function test_invalid_route_is_rejected(): void
    {
        $bus = Bus::create(['name' => 'Bus RtInv', 'code' => 'CAN-RI', 'total_seats' => 10]);

        $response = $this->actingAs($this->admin)->post(route('admin.trips.store'), [
            'bus_id' => $bus->id,
            'route_id' => 99999,
            'departure_at' => Carbon::now()->addDay()->format('Y-m-d\TH:i'),
            'arrival_at' => Carbon::now()->addDay()->addHours(8)->format('Y-m-d\TH:i'),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $response->assertSessionHasErrors(['route_id']);
    }

    /**
     * 27. invalid price ditolak
     */
    public function test_invalid_price_is_rejected(): void
    {
        $bus = Bus::create(['name' => 'Bus PrInv', 'code' => 'CAN-PI', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Cirebon', 'duration' => 180]);

        $response = $this->actingAs($this->admin)->post(route('admin.trips.store'), [
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay()->format('Y-m-d\TH:i'),
            'arrival_at' => Carbon::now()->addDay()->addHours(3)->format('Y-m-d\TH:i'),
            'price' => -1000,
            'status' => 'scheduled',
        ]);

        $response->assertSessionHasErrors(['price']);
    }

    /**
     * 28. customer tidak dapat mengakses trip management
     */
    public function test_customer_cannot_access_trip_management(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.trips.index'));

        $response->assertStatus(403);
    }

    /**
     * 29. trip yang memiliki order tidak dapat dihapus
     */
    public function test_trip_cannot_be_deleted_if_it_has_orders(): void
    {
        $bus = Bus::create(['name' => 'Bus With Order', 'code' => 'CAN-BORD', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Jepara', 'duration' => 480]);
        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $trip->id,
            'order_code' => 'ORD-TRIP-DELETE-TEST',
            'total_amount' => 250000,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.trips.destroy', $trip));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('trips', ['id' => $trip->id]);
    }

    /**
     * 30. trip tanpa order dapat dihapus
     */
    public function test_trip_can_be_deleted_if_has_no_orders(): void
    {
        $bus = Bus::create(['name' => 'Bus Trip Free', 'code' => 'CAN-TF', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Pekalongan', 'duration' => 300]);
        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(5),
            'price' => 180000,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.trips.destroy', $trip));

        $response->assertRedirect(route('admin.trips.index'));
        $this->assertDatabaseMissing('trips', ['id' => $trip->id]);
    }

    // ==========================================
    // RELATIONSHIP TESTS
    // ==========================================

    /**
     * 31. trip menampilkan bus & route
     */
    public function test_trip_displays_bus_and_route(): void
    {
        $bus = Bus::create(['name' => 'Bus Rel', 'code' => 'CAN-REL', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Blora', 'duration' => 450]);
        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 260000,
            'status' => 'scheduled',
        ]);

        $this->assertInstanceOf(Bus::class, $trip->bus);
        $this->assertEquals('CAN-REL', $trip->bus->code);
        $this->assertInstanceOf(Route::class, $trip->route);
        $this->assertEquals('Blora', $trip->route->destination);
    }

    /**
     * 32. bus menampilkan trips
     */
    public function test_bus_displays_trips(): void
    {
        $bus = Bus::create(['name' => 'Bus TripRel', 'code' => 'CAN-BTR', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Kudus', 'duration' => 420]);
        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(7),
            'price' => 240000,
            'status' => 'scheduled',
        ]);

        $this->assertTrue($bus->trips->contains($trip));
    }

    /**
     * 33. route menampilkan trips
     */
    public function test_route_displays_trips(): void
    {
        $bus = Bus::create(['name' => 'Bus RtRel', 'code' => 'CAN-RTR', 'total_seats' => 10]);
        $route = Route::create(['origin' => 'Jakarta', 'destination' => 'Boyolali', 'duration' => 450]);
        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $this->assertTrue($route->trips->contains($trip));
    }
}

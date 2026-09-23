<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerTripTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private User $admin;
    private Bus $bus1;
    private Bus $bus2;
    private Route $routeJakartaJepara;
    private Route $routeJakartaSemarang;
    private Trip $tripScheduled;
    private Trip $tripDeparted;
    private Trip $tripCompleted;
    private Trip $tripCancelled;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Users
        $this->customer = User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi.customer@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.utama@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Setup Buses
        $this->bus1 = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $this->bus2 = Bus::create([
            'name' => 'CAN Royal 02',
            'code' => 'CAN-RY02',
            'total_seats' => 28,
        ]);

        // 3. Setup Routes
        $this->routeJakartaJepara = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $this->routeJakartaSemarang = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Semarang',
            'duration' => 360,
        ]);

        // 4. Setup Trips
        $targetDate = Carbon::parse('2026-09-25 08:00:00', 'Asia/Jakarta');

        $this->tripScheduled = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->routeJakartaJepara->id,
            'departure_at' => $targetDate,
            'arrival_at' => $targetDate->copy()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $this->tripDeparted = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->routeJakartaJepara->id,
            'departure_at' => $targetDate->copy()->subHours(2),
            'arrival_at' => $targetDate->copy()->addHours(6),
            'price' => 250000,
            'status' => 'departed',
        ]);

        $this->tripCompleted = Trip::create([
            'bus_id' => $this->bus2->id,
            'route_id' => $this->routeJakartaSemarang->id,
            'departure_at' => $targetDate->copy()->subDay(),
            'arrival_at' => $targetDate->copy()->subDay()->addHours(6),
            'price' => 220000,
            'status' => 'completed',
        ]);

        $this->tripCancelled = Trip::create([
            'bus_id' => $this->bus2->id,
            'route_id' => $this->routeJakartaSemarang->id,
            'departure_at' => $targetDate->copy()->addDay(),
            'arrival_at' => $targetDate->copy()->addDay()->addHours(6),
            'price' => 220000,
            'status' => 'cancelled',
        ]);
    }

    /**
     * 1. guest tidak dapat mengakses customer trips
     */
    public function test_guest_cannot_access_customer_trips(): void
    {
        $response = $this->get('/customer/trips');

        $response->assertRedirect('/login');
    }

    /**
     * 2. customer dapat membuka halaman trip
     */
    public function test_customer_can_view_trips_page(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/trips');

        $response->assertStatus(200);
        $response->assertSee('Cari Jadwal Perjalanan');
        $response->assertSee('Kota Asal');
        $response->assertSee('Kota Tujuan');
        $response->assertSee('Tanggal Keberangkatan');
    }

    /**
     * 3. customer dapat melihat trip yang tersedia
     */
    public function test_customer_can_see_available_trips(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/trips');

        $response->assertStatus(200);
        $response->assertSee($this->tripScheduled->bus->name);
        $response->assertSee('CAN-EX01');
        $response->assertSee('Jakarta');
        $response->assertSee('Jepara');
        $response->assertSee('Rp250.000');
    }

    /**
     * 4. pencarian berdasarkan origin
     */
    public function test_search_by_origin(): void
    {
        // Buat trip lain dengan origin Jepara
        $routeJeparaJakarta = Route::create([
            'origin' => 'Jepara',
            'destination' => 'Jakarta',
            'duration' => 480,
        ]);

        $tripJepara = Trip::create([
            'bus_id' => $this->bus2->id,
            'route_id' => $routeJeparaJakarta->id,
            'departure_at' => Carbon::parse('2026-09-26 09:00:00', 'Asia/Jakarta'),
            'arrival_at' => Carbon::parse('2026-09-26 17:00:00', 'Asia/Jakarta'),
            'price' => 260000,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->customer)->get('/customer/trips?origin=Jepara');

        $response->assertStatus(200);
        $response->assertSee('CAN-RY02');
        $response->assertDontSee('CAN-EX01');
    }

    /**
     * 5. pencarian berdasarkan destination
     */
    public function test_search_by_destination(): void
    {
        // Buat trip dengan destination Semarang
        $tripSemarang = Trip::create([
            'bus_id' => $this->bus2->id,
            'route_id' => $this->routeJakartaSemarang->id,
            'departure_at' => Carbon::parse('2026-09-25 10:00:00', 'Asia/Jakarta'),
            'arrival_at' => Carbon::parse('2026-09-25 16:00:00', 'Asia/Jakarta'),
            'price' => 220000,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->customer)->get('/customer/trips?destination=Semarang');

        $response->assertStatus(200);
        $response->assertSee('Semarang');
        $response->assertSee('CAN-RY02');
        $response->assertDontSee('CAN-EX01');
        $response->assertViewHas('trips', function ($trips) use ($tripSemarang) {
            return $trips->contains('id', $tripSemarang->id)
                && !$trips->contains('id', $this->tripScheduled->id);
        });
    }

    /**
     * 6. pencarian berdasarkan tanggal
     */
    public function test_search_by_departure_date(): void
    {
        // Buat trip pada tanggal berbeda (2026-09-27)
        $tripOtherDate = Trip::create([
            'bus_id' => $this->bus2->id,
            'route_id' => $this->routeJakartaSemarang->id,
            'departure_at' => Carbon::parse('2026-09-27 10:00:00', 'Asia/Jakarta'),
            'arrival_at' => Carbon::parse('2026-09-27 16:00:00', 'Asia/Jakarta'),
            'price' => 220000,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->customer)->get('/customer/trips?departure_date=2026-09-25');

        $response->assertStatus(200);
        $response->assertSee('CAN-EX01');
        $response->assertDontSee('CAN-RY02');
    }

    /**
     * 7. pencarian origin + destination + tanggal
     */
    public function test_search_by_origin_destination_and_date(): void
    {
        // Buat trip lain yang mirip tapi beda tanggal
        Trip::create([
            'bus_id' => $this->bus2->id,
            'route_id' => $this->routeJakartaJepara->id,
            'departure_at' => Carbon::parse('2026-09-28 08:00:00', 'Asia/Jakarta'),
            'arrival_at' => Carbon::parse('2026-09-28 16:00:00', 'Asia/Jakarta'),
            'price' => 270000,
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($this->customer)->get('/customer/trips?origin=Jakarta&destination=Jepara&departure_date=2026-09-25');

        $response->assertStatus(200);
        $response->assertSee('CAN-EX01');
        $response->assertDontSee('CAN-RY02');
    }

    /**
     * 8. trip scheduled ditampilkan
     */
    public function test_scheduled_trip_is_displayed(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/trips');

        $response->assertStatus(200);
        $response->assertSee($this->tripScheduled->bus->name);
    }

    /**
     * 9. trip cancelled tidak ditampilkan
     */
    public function test_cancelled_trip_is_not_displayed(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/trips');

        $response->assertStatus(200);
        $response->assertDontSee($this->tripCancelled->bus->code);
    }

    /**
     * 10. trip departed tidak ditampilkan
     */
    public function test_departed_trip_is_not_displayed(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/trips');

        $response->assertStatus(200);
        // Pastikan hanya 1 trip yang muncul yaitu tripScheduled
        $response->assertViewHas('trips', function ($trips) {
            return $trips->contains('id', $this->tripScheduled->id)
                && !$trips->contains('id', $this->tripDeparted->id);
        });
    }

    /**
     * 11. trip completed tidak ditampilkan
     */
    public function test_completed_trip_is_not_displayed(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/trips');

        $response->assertStatus(200);
        $response->assertViewHas('trips', function ($trips) {
            return !$trips->contains('id', $this->tripCompleted->id);
        });
    }

    /**
     * 12. customer dapat membuka detail trip
     */
    public function test_customer_can_view_trip_detail(): void
    {
        $response = $this->actingAs($this->customer)->get("/customer/trips/{$this->tripScheduled->id}");

        $response->assertStatus(200);
        $response->assertSee('Informasi Perjalanan');
        $response->assertSee('Lanjut Pilih Kursi');
    }

    /**
     * 13. detail menampilkan bus dan route
     */
    public function test_trip_detail_displays_bus_and_route(): void
    {
        $response = $this->actingAs($this->customer)->get("/customer/trips/{$this->tripScheduled->id}");

        $response->assertStatus(200);
        $response->assertSee($this->tripScheduled->bus->name);
        $response->assertSee($this->tripScheduled->bus->code);
        $response->assertSee($this->tripScheduled->route->origin);
        $response->assertSee($this->tripScheduled->route->destination);
        $response->assertSee('Rp250.000');
    }

    /**
     * 14. trip tidak ditemukan menghasilkan 404
     */
    public function test_non_existent_trip_returns_404(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/trips/999999');

        $response->assertStatus(404);
    }

    /**
     * 15. admin tidak dapat mengakses customer trip area
     */
    public function test_admin_cannot_access_customer_trips(): void
    {
        // Akses daftar trips customer
        $indexResponse = $this->actingAs($this->admin)->get('/customer/trips');
        $indexResponse->assertStatus(403);

        // Akses detail trip customer
        $showResponse = $this->actingAs($this->admin)->get("/customer/trips/{$this->tripScheduled->id}");
        $showResponse->assertStatus(403);
    }

    /**
     * 16. hasil kosong menampilkan empty state
     */
    public function test_empty_search_results_shows_empty_state(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/trips?origin=KotaTidakAda');

        $response->assertStatus(200);
        $response->assertSee('Belum ada perjalanan yang sesuai dengan pencarian.');
    }

    /**
     * 17. validasi input pencarian
     */
    public function test_search_input_validation(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/trips?departure_date=tanggal-salah');

        $response->assertSessionHasErrors(['departure_date']);
    }

    /**
     * 18. customer tidak dapat melihat detail trip non-scheduled (cancelled, departed, completed)
     */
    public function test_customer_cannot_view_detail_of_non_scheduled_trip(): void
    {
        // Cancelled trip harus 404
        $cancelledResponse = $this->actingAs($this->customer)->get("/customer/trips/{$this->tripCancelled->id}");
        $cancelledResponse->assertStatus(404);

        // Departed trip harus 404
        $departedResponse = $this->actingAs($this->customer)->get("/customer/trips/{$this->tripDeparted->id}");
        $departedResponse->assertStatus(404);

        // Completed trip harus 404
        $completedResponse = $this->actingAs($this->customer)->get("/customer/trips/{$this->tripCompleted->id}");
        $completedResponse->assertStatus(404);
    }
}

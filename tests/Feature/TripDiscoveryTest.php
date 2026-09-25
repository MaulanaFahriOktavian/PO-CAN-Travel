<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Facility;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TripDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    private Bus $bus1;
    private Bus $bus2;
    private Route $routeJakartaJepara;
    private Route $routeJakartaSemarang;
    private Trip $tripScheduled;
    private Trip $tripCheaper;
    private Trip $tripCancelled;
    private Trip $tripDeparted;
    private Trip $tripCompleted;
    private Trip $tripPast;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Buses
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

        // Facilities
        $facility = Facility::create([
            'name' => 'AC Sentral',
            'slug' => 'ac-sentral',
            'description' => 'Penyejuk udara kabin merata',
        ]);
        $this->bus1->facilities()->attach($facility);

        // 2. Routes
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

        // 3. Trips (future date relative to now)
        $futureDate = now()->addDays(2)->setTime(8, 0, 0);

        $this->tripScheduled = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->routeJakartaJepara->id,
            'departure_at' => $futureDate,
            'arrival_at' => $futureDate->copy()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $this->tripCheaper = Trip::create([
            'bus_id' => $this->bus2->id,
            'route_id' => $this->routeJakartaSemarang->id,
            'departure_at' => $futureDate->copy()->addHours(2),
            'arrival_at' => $futureDate->copy()->addHours(8),
            'price' => 190000,
            'status' => 'scheduled',
        ]);

        $this->tripCancelled = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->routeJakartaJepara->id,
            'departure_at' => $futureDate->copy()->addDays(1),
            'arrival_at' => $futureDate->copy()->addDays(1)->addHours(8),
            'price' => 250000,
            'status' => 'cancelled',
        ]);

        $this->tripDeparted = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->routeJakartaJepara->id,
            'departure_at' => $futureDate->copy()->subHours(4),
            'arrival_at' => $futureDate->copy()->addHours(4),
            'price' => 250000,
            'status' => 'departed',
        ]);

        $this->tripCompleted = Trip::create([
            'bus_id' => $this->bus2->id,
            'route_id' => $this->routeJakartaSemarang->id,
            'departure_at' => $futureDate->copy()->subDays(2),
            'arrival_at' => $futureDate->copy()->subDays(2)->addHours(6),
            'price' => 220000,
            'status' => 'completed',
        ]);

        $this->tripPast = Trip::create([
            'bus_id' => $this->bus1->id,
            'route_id' => $this->routeJakartaJepara->id,
            'departure_at' => now()->subDay(),
            'arrival_at' => now()->subDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);
    }

    /**
     * 1. Public search berhasil dan menampilkan trip yang terjadwal.
     */
    public function test_public_search_succeeds_and_displays_available_trips(): void
    {
        $response = $this->get(route('trips.index'));

        $response->assertStatus(200);
        $response->assertSee('Jakarta');
        $response->assertSee('Jepara');
        $response->assertSee('Semarang');
        $response->assertSee($this->tripScheduled->bus->name);
        $response->assertSee($this->tripScheduled->bus->code);
        $response->assertSee('Rp250.000');
        $response->assertSee('Rp190.000');
    }

    /**
     * 2. Filter berdasarkan origin dan destination.
     */
    public function test_public_search_filter_by_origin_and_destination(): void
    {
        $response = $this->get(route('trips.index', [
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Jepara');
        $response->assertSee($this->tripScheduled->bus->code);
        $response->assertDontSee($this->tripCheaper->bus->code);
    }

    /**
     * 3. Filter berdasarkan tanggal keberangkatan (departure_date dan alias date).
     */
    public function test_public_search_filter_by_date(): void
    {
        $targetDateStr = $this->tripScheduled->departure_at->format('Y-m-d');

        // departure_date parameter
        $response1 = $this->get(route('trips.index', [
            'departure_date' => $targetDateStr,
        ]));
        $response1->assertStatus(200);
        $response1->assertSee($this->tripScheduled->bus->code);

        // date parameter alias
        $response2 = $this->get(route('trips.index', [
            'date' => $targetDateStr,
        ]));
        $response2->assertStatus(200);
        $response2->assertSee($this->tripScheduled->bus->code);
    }

    /**
     * 4. Sorting berdasarkan harga dan jam keberangkatan.
     */
    public function test_public_search_sorting(): void
    {
        // price_asc
        $responsePriceAsc = $this->get(route('trips.index', ['sort' => 'price_asc']));
        $responsePriceAsc->assertStatus(200);
        $responsePriceAsc->assertViewHas('trips', function ($trips) {
            return $trips->first()->id === $this->tripCheaper->id;
        });

        // price_desc
        $responsePriceDesc = $this->get(route('trips.index', ['sort' => 'price_desc']));
        $responsePriceDesc->assertStatus(200);
        $responsePriceDesc->assertViewHas('trips', function ($trips) {
            return $trips->first()->id === $this->tripScheduled->id;
        });

        // departure_asc
        $responseDepAsc = $this->get(route('trips.index', ['sort' => 'departure_asc']));
        $responseDepAsc->assertStatus(200);
        $responseDepAsc->assertViewHas('trips', function ($trips) {
            return $trips->first()->id === $this->tripScheduled->id;
        });
    }

    /**
     * 5. Validasi route: origin dan destination tidak boleh sama.
     */
    public function test_public_search_rejects_identical_origin_and_destination(): void
    {
        $response = $this->get(route('trips.index', [
            'origin' => 'Jakarta',
            'destination' => 'Jakarta',
        ]));

        $response->assertSessionHasErrors(['destination']);
    }

    /**
     * 6. Validasi tanggal tidak valid.
     */
    public function test_public_search_rejects_invalid_date_format(): void
    {
        $response = $this->get(route('trips.index', [
            'departure_date' => 'invalid-date-string',
        ]));

        $response->assertSessionHasErrors(['departure_date']);
    }

    /**
     * 7. Empty state jika tidak ada hasil pencarian.
     */
    public function test_public_search_shows_clear_empty_state_without_ai_slop(): void
    {
        $response = $this->get(route('trips.index', [
            'origin' => 'Jakarta',
            'destination' => 'KotaKhayalan',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Belum ada perjalanan yang sesuai dengan pencarian.');
        $response->assertDontSee('Oops');
        $response->assertDontSee('Something went wrong');
        $response->assertDontSee('Uh oh');
    }

    /**
     * 8. Detail perjalanan publik menampilkan data operasional lengkap.
     */
    public function test_public_trip_detail_displays_full_operational_info(): void
    {
        $response = $this->get(route('trips.show', $this->tripScheduled));

        $response->assertStatus(200);
        $response->assertSee('Jakarta');
        $response->assertSee('Jepara');
        $response->assertSee($this->tripScheduled->bus->name);
        $response->assertSee($this->tripScheduled->bus->code);
        $response->assertSee('AC Sentral');
        $response->assertSee('Rp250.000');
        $response->assertSee('Rangkaian Waktu Perjalanan');
        $response->assertSee('Armada Bus Bertugas');
        $response->assertSee('Fasilitas Armada Bus');
    }

    /**
     * 9. Trip tidak ditemukan menghasilkan 404.
     */
    public function test_public_trip_non_existent_returns_404(): void
    {
        $response = $this->get('/perjalanan/999999');

        $response->assertStatus(404);
    }

    /**
     * 10. Trip cancelled menghasilkan 404 pada detail publik.
     */
    public function test_public_trip_cancelled_returns_404(): void
    {
        $response = $this->get(route('trips.show', $this->tripCancelled));

        $response->assertStatus(404);
    }

    /**
     * 11. Trip departed dan completed menghasilkan 404 pada detail publik.
     */
    public function test_public_trip_departed_and_completed_return_404(): void
    {
        $responseDeparted = $this->get(route('trips.show', $this->tripDeparted));
        $responseDeparted->assertStatus(404);

        $responseCompleted = $this->get(route('trips.show', $this->tripCompleted));
        $responseCompleted->assertStatus(404);
    }

    /**
     * 12. Trip yang tanggalnya sudah lewat tidak muncul di pencarian publik dan 404 pada detail.
     */
    public function test_public_past_trip_is_excluded_from_search_and_returns_404_on_show(): void
    {
        // Excluded from index
        $indexResponse = $this->get(route('trips.index'));
        $indexResponse->assertViewHas('trips', function ($trips) {
            return !$trips->contains('id', $this->tripPast->id);
        });

        // 404 on show
        $showResponse = $this->get(route('trips.show', $this->tripPast));
        $showResponse->assertStatus(404);
    }
}

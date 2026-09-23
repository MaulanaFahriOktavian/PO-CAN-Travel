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

class OrderBookingTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private User $customer2;
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
            'email' => 'budi.order@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->customer2 = User::create([
            'name' => 'Siti Pelanggan',
            'email' => 'siti.order@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.order@pocantravel.com',
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
            'departure_at' => $now->copy()->subDay(),
            'arrival_at' => $now->copy()->subDay()->addHours(8),
            'price' => 250000,
            'status' => 'completed',
        ]);
    }

    // ==========================================
    // GROUP A: HAK AKSES & MIDDLEWARE (1 - 5)
    // ==========================================

    /**
     * 1. Guest tidak boleh mengakses form booking
     */
    public function test_guest_cannot_access_booking_form(): void
    {
        $response = $this->get(route('customer.trips.booking', $this->tripScheduled));

        $response->assertRedirect(route('login'));
    }

    /**
     * 2. Guest tidak boleh melakukan submit order
     */
    public function test_guest_cannot_submit_order(): void
    {
        $response = $this->post(route('customer.trips.booking.store', $this->tripScheduled), []);

        $response->assertRedirect(route('login'));
    }

    /**
     * 3. Admin tidak boleh mengakses form booking customer
     */
    public function test_admin_cannot_access_booking_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('customer.trips.booking', $this->tripScheduled));

        $response->assertStatus(403);
    }

    /**
     * 4. Admin tidak boleh melakukan submit order customer
     */
    public function test_admin_cannot_submit_order(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('customer.trips.booking.store', $this->tripScheduled), []);

        $response->assertStatus(403);
    }

    /**
     * 5. Customer dapat mengakses form booking dengan session valid
     */
    public function test_customer_can_access_booking_form_with_valid_session(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->get(route('customer.trips.booking', $this->tripScheduled));

        $response->assertStatus(200);
        $response->assertSee('Data Penumpang');
        $response->assertSee($seat->seat_number);
        $response->assertSee('Rincian Pembayaran');
        $response->assertSee('Rp' . number_format($this->tripScheduled->price, 0, ',', '.'));
    }

    // ==========================================
    // GROUP B: SESSION & VALIDASI STATE (6 - 11)
    // ==========================================

    /**
     * 6. Form booking redirect jika session kosong
     */
    public function test_booking_form_redirects_if_no_session(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('customer.trips.booking', $this->tripScheduled));

        $response->assertRedirect(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSessionHas('error', 'Silakan pilih kursi terlebih dahulu.');
    }

    /**
     * 7. Form booking redirect jika trip_id di session tidak cocok
     */
    public function test_booking_form_redirects_if_session_trip_mismatch(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => 99999,
                'booking.seat_ids' => [$seat->id],
            ])
            ->get(route('customer.trips.booking', $this->tripScheduled));

        $response->assertRedirect(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSessionHas('error');
    }

    /**
     * 8. Form booking redirect jika seat_ids di session kosong
     */
    public function test_booking_form_redirects_if_session_seats_empty(): void
    {
        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [],
            ])
            ->get(route('customer.trips.booking', $this->tripScheduled));

        $response->assertRedirect(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSessionHas('error');
    }

    /**
     * 9. Form booking 404 jika trip bukan scheduled
     */
    public function test_booking_form_404_if_trip_not_scheduled(): void
    {
        $seat = $this->bus1->seats()->first();
        $session = [
            'booking.trip_id' => $this->tripCancelled->id,
            'booking.seat_ids' => [$seat->id],
        ];

        $this->actingAs($this->customer)
            ->withSession($session)
            ->get(route('customer.trips.booking', $this->tripCancelled))
            ->assertStatus(404);

        $this->actingAs($this->customer)
            ->withSession($session)
            ->get(route('customer.trips.booking', $this->tripDeparted))
            ->assertStatus(404);

        $this->actingAs($this->customer)
            ->withSession($session)
            ->get(route('customer.trips.booking', $this->tripCompleted))
            ->assertStatus(404);
    }

    /**
     * 10. Store order redirect jika session kosong
     */
    public function test_store_order_redirects_if_no_session(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi',
                        'identity' => '3201123456780001',
                    ],
                ],
            ]);

        $response->assertRedirect(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSessionHas('error');
        $this->assertEquals(0, Order::count());
    }

    /**
     * 11. Store order redirect jika trip_id di session tidak cocok dengan route
     */
    public function test_store_order_redirects_if_session_trip_mismatch(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => 99999,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi',
                        'identity' => '3201123456780001',
                    ],
                ],
            ]);

        $response->assertRedirect(route('customer.trips.seats', $this->tripScheduled));
        $response->assertSessionHas('error');
        $this->assertEquals(0, Order::count());
    }

    // ==========================================
    // GROUP C: VALIDASI INPUT PENUMPANG (12 - 19)
    // ==========================================

    /**
     * 12. Passengers data wajib diisi
     */
    public function test_passengers_data_is_required(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), []);

        $response->assertSessionHasErrors(['passengers']);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 13. Passengers harus berupa array
     */
    public function test_passengers_must_be_array(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => 'string_bukan_array',
            ]);

        $response->assertSessionHasErrors(['passengers']);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 14. Nama penumpang wajib diisi untuk setiap seat
     */
    public function test_passenger_name_is_required_for_each_seat(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => '',
                        'identity' => '3201123456780001',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(["passengers.{$seat->id}.name"]);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 15. Nama penumpang maksimal 100 karakter
     */
    public function test_passenger_name_cannot_exceed_100_chars(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => str_repeat('A', 101),
                        'identity' => '3201123456780001',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(["passengers.{$seat->id}.name"]);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 16. Nomor identitas wajib diisi untuk setiap seat
     */
    public function test_passenger_identity_is_required_for_each_seat(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi Santoso',
                        'identity' => '',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(["passengers.{$seat->id}.identity"]);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 17. Nomor identitas maksimal 50 karakter
     */
    public function test_passenger_identity_cannot_exceed_50_chars(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi Santoso',
                        'identity' => str_repeat('1', 51),
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(["passengers.{$seat->id}.identity"]);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 18. Validasi passenger: tidak boleh kurang seat dari yang dipilih di session
     */
    public function test_passengers_keys_must_exact_match_selected_seat_ids_no_missing(): void
    {
        $seats = $this->bus1->seats()->take(2)->get();
        $seat1 = $seats[0];
        $seat2 = $seats[1];

        // Session memilih 2 seat
        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat1->id, $seat2->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    // Hanya mengirim data 1 seat
                    $seat1->id => [
                        'name' => 'Budi Santoso',
                        'identity' => '3201123456780001',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['passengers']);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 19. Validasi passenger: tidak boleh berlebih seat dari yang dipilih di session
     */
    public function test_passengers_keys_must_exact_match_selected_seat_ids_no_excess(): void
    {
        $seats = $this->bus1->seats()->take(2)->get();
        $seat1 = $seats[0];
        $seat2 = $seats[1];

        // Session hanya memilih 1 seat
        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat1->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    // Mengirim data 2 seat
                    $seat1->id => [
                        'name' => 'Budi Santoso',
                        'identity' => '3201123456780001',
                    ],
                    $seat2->id => [
                        'name' => 'Siti Aminah',
                        'identity' => '3201123456780002',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['passengers']);
        $this->assertEquals(0, Order::count());
    }

    // ==========================================
    // GROUP D: HARGA & ATOMISITAS ORDER (20 - 26)
    // ==========================================

    /**
     * 20. Harga pesanan dihitung dari database, bukan dari input request
     */
    public function test_order_price_calculated_from_database_not_request(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi Santoso',
                        'identity' => '3201123456780001',
                    ],
                ],
                'price' => 100,
                'total_amount' => 100,
            ]);

        $response->assertRedirect();
        $order = Order::first();
        $this->assertNotNull($order);
        // Tetap menggunakan harga database (250.000)
        $this->assertEquals(250000, $order->total_amount);
    }

    /**
     * 21. Total amount order sama dengan jumlah kursi dikali harga trip
     */
    public function test_order_total_amount_equals_seat_count_times_trip_price(): void
    {
        $seats = $this->bus1->seats()->take(3)->get();
        $seatIds = $seats->pluck('id')->all();

        $passengers = [];
        foreach ($seats as $i => $seat) {
            $passengers[$seat->id] = [
                'name' => "Penumpang {$i}",
                'identity' => "ID{$i}000000",
            ];
        }

        $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => $seatIds,
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => $passengers,
            ]);

        $order = Order::first();
        $expectedTotal = 3 * $this->tripScheduled->price; // 3 x 250.000 = 750.000
        $this->assertEquals($expectedTotal, $order->total_amount);
    }

    /**
     * 22. Order baru dibuat dengan status default 'pending'
     */
    public function test_order_created_with_pending_status(): void
    {
        $seat = $this->bus1->seats()->first();

        $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi Santoso',
                        'identity' => '3201123456780001',
                    ],
                ],
            ]);

        $order = Order::first();
        $this->assertEquals('pending', $order->status);
    }

    /**
     * 23. OrderItem dibuat untuk setiap kursi yang dipilih
     */
    public function test_order_items_created_for_each_selected_seat(): void
    {
        $seats = $this->bus1->seats()->take(2)->get();
        $seat1 = $seats[0];
        $seat2 = $seats[1];

        $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat1->id, $seat2->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat1->id => [
                        'name' => 'Budi Santoso',
                        'identity' => '3201123456780001',
                    ],
                    $seat2->id => [
                        'name' => 'Siti Aminah',
                        'identity' => '3201123456780002',
                    ],
                ],
            ]);

        $order = Order::first();
        $this->assertEquals(2, $order->orderItems()->count());

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'seat_id' => $seat1->id,
            'passenger_name' => 'Budi Santoso',
            'passenger_identity' => '3201123456780001',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'seat_id' => $seat2->id,
            'passenger_name' => 'Siti Aminah',
            'passenger_identity' => '3201123456780002',
        ]);
    }

    /**
     * 24. OrderItem.price menjadi snapshot harga trip saat pemesanan
     */
    public function test_order_item_price_is_snapshot_of_trip_price(): void
    {
        $seat = $this->bus1->seats()->first();

        $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi Santoso',
                        'identity' => '3201123456780001',
                    ],
                ],
            ]);

        $orderItem = OrderItem::first();
        $this->assertEquals(250000, $orderItem->price);

        // Simulasi perubahan harga trip oleh admin di masa depan
        $this->tripScheduled->update(['price' => 350000]);

        // Harga di OrderItem harus tetap sama (snapshot tidak berubah)
        $orderItem->refresh();
        $this->assertEquals(250000, $orderItem->price);
    }

    /**
     * 25. Format kode pesanan valid sesuai pola PCT-YYYYMMDD-XXXXXX
     */
    public function test_order_code_format_is_valid(): void
    {
        $seat = $this->bus1->seats()->first();

        $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi Santoso',
                        'identity' => '3201123456780001',
                    ],
                ],
            ]);

        $order = Order::first();
        $this->assertMatchesRegularExpression('/^PCT-\d{8}-[A-Z0-9]{6}$/', $order->order_code);
    }

    /**
     * 26. Session booking dibersihkan setelah pemesanan berhasil
     */
    public function test_session_cleared_after_order_creation(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
                'selected_seats' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi Santoso',
                        'identity' => '3201123456780001',
                    ],
                ],
            ]);

        $response->assertSessionMissing('booking.trip_id');
        $response->assertSessionMissing('booking.seat_ids');
        $response->assertSessionMissing('selected_seats');
    }

    // =======================================================
    // GROUP E: PROTEKSI DOUBLE BOOKING & BEHAVIORAL (27 - 33)
    // =======================================================

    /**
     * 27. Kursi dengan order pending tidak dapat dipesan kembali
     */
    public function test_cannot_order_seat_with_pending_order(): void
    {
        $seat = $this->bus1->seats()->first();

        // Customer 1 membuat order pending pada kursi ini
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => 'Budi',
            'passenger_identity' => '123456',
            'price' => 250000,
        ]);

        // Customer 2 mencoba memesan kursi yang sama
        $response = $this->actingAs($this->customer2)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Siti',
                        'identity' => '654321',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['seats']);
        // Order kedua tidak dibuat
        $this->assertEquals(1, Order::count());
    }

    /**
     * 28. Kursi dengan order confirmed tidak dapat dipesan kembali
     */
    public function test_cannot_order_seat_with_confirmed_order(): void
    {
        $seat = $this->bus1->seats()->first();

        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'confirmed',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => 'Budi',
            'passenger_identity' => '123456',
            'price' => 250000,
        ]);

        $response = $this->actingAs($this->customer2)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Siti',
                        'identity' => '654321',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['seats']);
        $this->assertEquals(1, Order::count());
    }

    /**
     * 29. Kursi dengan order completed tidak dapat dipesan kembali
     */
    public function test_cannot_order_seat_with_completed_order(): void
    {
        $seat = $this->bus1->seats()->first();

        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'completed',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => 'Budi',
            'passenger_identity' => '123456',
            'price' => 250000,
        ]);

        $response = $this->actingAs($this->customer2)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Siti',
                        'identity' => '654321',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['seats']);
        $this->assertEquals(1, Order::count());
    }

    /**
     * 30. Kursi dengan order cancelled DAPAT dipesan kembali
     */
    public function test_can_order_seat_with_cancelled_order(): void
    {
        $seat = $this->bus1->seats()->first();

        // Pesanan sebelumnya dibatalkan
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'cancelled',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => 'Budi',
            'passenger_identity' => '123456',
            'price' => 250000,
        ]);

        // Customer 2 memesan kursi yang sudah dibatalkan
        $response = $this->actingAs($this->customer2)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Siti',
                        'identity' => '654321',
                    ],
                ],
            ]);

        $response->assertRedirect();
        // Berhasil membuat order baru (total 2 order: 1 cancelled + 1 pending)
        $this->assertEquals(2, Order::count());
        $newOrder = Order::where('user_id', $this->customer2->id)->first();
        $this->assertNotNull($newOrder);
        $this->assertEquals('pending', $newOrder->status);
    }

    /**
     * 31. Kursi dari bus lain ditolak
     */
    public function test_cannot_order_seat_from_different_bus(): void
    {
        $seatOtherBus = $this->bus2->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seatOtherBus->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seatOtherBus->id => [
                        'name' => 'Budi',
                        'identity' => '123456',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['seats']);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 32. Kursi yang tidak ada di database ditolak
     */
    public function test_cannot_order_non_existent_seat(): void
    {
        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [999999],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    999999 => [
                        'name' => 'Budi',
                        'identity' => '123456',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['seats']);
        $this->assertEquals(0, Order::count());
    }

    /**
     * 33. Kursi duplikat di dalam session ditolak
     */
    public function test_cannot_order_duplicate_seat_ids_in_session(): void
    {
        $seat = $this->bus1->seats()->first();

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id, $seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi',
                        'identity' => '123456',
                    ],
                ],
            ]);

        // Error terdeteksi baik di custom request atau controller
        $this->assertEquals(0, Order::count());
    }

    // ====================================================
    // GROUP F: TRANSACTION ROLLBACK & INTEGRITY (34 - 36)
    // ====================================================

    /**
     * 34. Order dan OrderItem dibuat secara atomik dalam satu transaksi
     */
    public function test_order_and_items_created_atomically(): void
    {
        $seat = $this->bus1->seats()->first();

        $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi',
                        'identity' => '123456',
                    ],
                ],
            ]);

        $this->assertEquals(1, Order::count());
        $this->assertEquals(1, OrderItem::count());

        $order = Order::first();
        $this->assertEquals(1, $order->orderItems()->count());
    }

    /**
     * 35. Rollback memastikan tidak ada partial order jika kursi sudah terisi
     */
    public function test_transaction_rollback_when_seat_already_booked_leaves_no_orphan_order(): void
    {
        $seats = $this->bus1->seats()->take(2)->get();
        $seat1 = $seats[0];
        $seat2 = $seats[1];

        // Seat 2 sudah di-book oleh customer lain sebelumnya
        $existingOrder = Order::create([
            'user_id' => $this->customer2->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $existingOrder->id,
            'seat_id' => $seat2->id,
            'passenger_name' => 'Siti',
            'passenger_identity' => '654321',
            'price' => 250000,
        ]);

        $this->assertEquals(1, Order::count());
        $this->assertEquals(1, OrderItem::count());

        // Customer mencoba memesan seat 1 dan seat 2 sekaligus
        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat1->id, $seat2->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat1->id => [
                        'name' => 'Budi',
                        'identity' => '123456',
                    ],
                    $seat2->id => [
                        'name' => 'Joko',
                        'identity' => '789012',
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['seats']);

        // Tidak boleh ada Order baru maupun OrderItem baru yang tertinggal (rollback bersih)
        $this->assertEquals(1, Order::count());
        $this->assertEquals(1, OrderItem::count());
        $this->assertDatabaseMissing('orders', ['user_id' => $this->customer->id]);
        $this->assertDatabaseMissing('order_items', ['seat_id' => $seat1->id]);
    }

    /**
     * 36. Rollback terjadi jika status trip tiba-tiba berubah menjadi non-scheduled saat proses
     */
    public function test_transaction_rollback_when_trip_becomes_inactive(): void
    {
        $seat = $this->bus1->seats()->first();

        // Jadwal trip diubah menjadi cancelled tepat sebelum order dibuat
        $this->tripScheduled->update(['status' => 'cancelled']);

        $response = $this->actingAs($this->customer)
            ->withSession([
                'booking.trip_id' => $this->tripScheduled->id,
                'booking.seat_ids' => [$seat->id],
            ])
            ->post(route('customer.trips.booking.store', $this->tripScheduled), [
                'passengers' => [
                    $seat->id => [
                        'name' => 'Budi',
                        'identity' => '123456',
                    ],
                ],
            ]);

        $this->assertEquals(0, Order::count());
        $this->assertEquals(0, OrderItem::count());
    }

    // ==========================================
    // GROUP G: ORDER DETAIL & OWNERSHIP (37 - 40)
    // ==========================================

    /**
     * 37. Customer dapat melihat detail order miliknya sendiri
     */
    public function test_customer_can_view_own_order_detail(): void
    {
        $seat = $this->bus1->seats()->first();

        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => 'Budi Santoso',
            'passenger_identity' => '3201123456780001',
            'price' => 250000,
        ]);

        $response = $this->actingAs($this->customer)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee($order->order_code);
        $response->assertSee('Menunggu Pembayaran');
        $response->assertSee('Budi Santoso');
        $response->assertSee($seat->seat_number);
        $response->assertSee('Rp' . number_format($order->total_amount, 0, ',', '.'));
        $response->assertSee('Jakarta');
        $response->assertSee('Jepara');
    }

    /**
     * 38. Customer tidak boleh melihat order milik customer lain (403)
     */
    public function test_customer_cannot_view_other_customer_order_detail(): void
    {
        $seat = $this->bus1->seats()->first();

        // Order milik customer 1
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => 'Budi Santoso',
            'passenger_identity' => '3201123456780001',
            'price' => 250000,
        ]);

        // Customer 2 mencoba mengakses order milik Customer 1
        $response = $this->actingAs($this->customer2)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(403);
    }

    /**
     * 39. Guest tidak boleh melihat detail order
     */
    public function test_guest_cannot_view_order_detail(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        $response = $this->get(route('customer.orders.show', $order));

        $response->assertRedirect(route('login'));
    }

    /**
     * 40. Admin tidak boleh melihat order detail via route customer (403)
     */
    public function test_admin_cannot_view_customer_order_detail_via_customer_route(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'trip_id' => $this->tripScheduled->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(403);
    }
}

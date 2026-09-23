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

class OrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    private User $customerA;
    private User $customerB;
    private User $admin;
    private Bus $bus;
    private Route $route;
    private Trip $trip;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Users
        $this->customerA = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.history@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->customerB = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti.history@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin.history@pocantravel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Bus & Seats
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

        // 3. Route
        $this->route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        // 4. Trip
        $this->trip = Trip::create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'departure_at' => Carbon::parse('2026-09-25 08:00:00', 'Asia/Jakarta'),
            'arrival_at' => Carbon::parse('2026-09-25 16:00:00', 'Asia/Jakarta'),
            'price' => 250000,
            'status' => 'scheduled',
        ]);
    }

    /**
     * Helper membuat order pengujian
     */
    private function createOrderFor(User $user, string $status = 'pending', int $price = 250000, ?Carbon $createdAt = null): Order
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
    // GROUP A: AUTHORIZATION (1 - 3)
    // ==========================================

    /**
     * 1. Guest tidak boleh mengakses riwayat pesanan (redirect ke login)
     */
    public function test_guest_cannot_access_order_history(): void
    {
        $response = $this->get(route('customer.orders.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * 2. Admin tidak boleh mengakses riwayat pesanan customer (403)
     */
    public function test_admin_cannot_access_customer_order_history(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('customer.orders.index'));

        $response->assertStatus(403);
    }

    /**
     * 3. Customer dapat mengakses riwayat pesanan miliknya
     */
    public function test_customer_can_access_own_order_history(): void
    {
        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Pesanan');
    }

    // ==========================================
    // GROUP B: HISTORY LISTING (4 - 10)
    // ==========================================

    /**
     * 4. Customer melihat order miliknya sendiri
     */
    public function test_customer_sees_own_orders(): void
    {
        $order = $this->createOrderFor($this->customerA);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee($order->order_code);
    }

    /**
     * 5. Customer tidak melihat order milik user lain
     */
    public function test_customer_does_not_see_another_users_orders(): void
    {
        $orderA = $this->createOrderFor($this->customerA);
        $orderB = $this->createOrderFor($this->customerB);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee($orderA->order_code);
        $response->assertDontSee($orderB->order_code);
    }

    /**
     * 6. Order diurutkan dari yang terbaru (newest first)
     */
    public function test_orders_sorted_newest_first(): void
    {
        $orderOld = $this->createOrderFor($this->customerA, 'pending', 250000, Carbon::now()->subDays(2));
        $orderNew = $this->createOrderFor($this->customerA, 'pending', 250000, Carbon::now());

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $content = $response->getContent();

        $posNew = strpos($content, $orderNew->order_code);
        $posOld = strpos($content, $orderOld->order_code);

        $this->assertTrue($posNew !== false && $posOld !== false);
        $this->assertTrue($posNew < $posOld, 'Order terbaru harus tampil lebih awal daripada order lama.');
    }

    /**
     * 7. Item order menampilkan informasi perjalanan
     */
    public function test_order_displays_trip_information(): void
    {
        $order = $this->createOrderFor($this->customerA);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('Jakarta');
        $response->assertSee('Jepara');
        $response->assertSee('CAN Executive 01');
        $response->assertSee('CAN-EX01');
    }

    /**
     * 8. Item order menampilkan total harga pesanan
     */
    public function test_order_displays_total_amount(): void
    {
        $order = $this->createOrderFor($this->customerA, 'pending', 250000);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('Rp' . number_format($order->total_amount, 0, ',', '.'));
    }

    /**
     * 9. Item order menampilkan status yang tepat
     */
    public function test_order_displays_status(): void
    {
        $this->createOrderFor($this->customerA, 'pending');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('Menunggu Pembayaran');
    }

    /**
     * 10. Empty state ditampilkan jika customer belum memiliki pesanan
     */
    public function test_empty_history_works(): void
    {
        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada pesanan');
        $response->assertSee('Pesanan tiket yang Anda buat akan muncul di sini.');
        $response->assertSee('Cari Perjalanan');
        $response->assertSee(route('customer.trips.index'));
    }

    // ==========================================
    // GROUP C: FILTER STATUS (11 - 16)
    // ==========================================

    /**
     * 11. Filter pending hanya menampilkan order pending
     */
    public function test_filter_pending_orders(): void
    {
        $pendingOrder = $this->createOrderFor($this->customerA, 'pending');
        $confirmedOrder = $this->createOrderFor($this->customerA, 'confirmed');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index', ['status' => 'pending']));

        $response->assertStatus(200);
        $response->assertSee($pendingOrder->order_code);
        $response->assertDontSee($confirmedOrder->order_code);
    }

    /**
     * 12. Filter confirmed hanya menampilkan order confirmed
     */
    public function test_filter_confirmed_orders(): void
    {
        $pendingOrder = $this->createOrderFor($this->customerA, 'pending');
        $confirmedOrder = $this->createOrderFor($this->customerA, 'confirmed');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index', ['status' => 'confirmed']));

        $response->assertStatus(200);
        $response->assertSee($confirmedOrder->order_code);
        $response->assertDontSee($pendingOrder->order_code);
    }

    /**
     * 13. Filter cancelled hanya menampilkan order cancelled
     */
    public function test_filter_cancelled_orders(): void
    {
        $cancelledOrder = $this->createOrderFor($this->customerA, 'cancelled');
        $completedOrder = $this->createOrderFor($this->customerA, 'completed');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index', ['status' => 'cancelled']));

        $response->assertStatus(200);
        $response->assertSee($cancelledOrder->order_code);
        $response->assertDontSee($completedOrder->order_code);
    }

    /**
     * 14. Filter completed hanya menampilkan order completed
     */
    public function test_filter_completed_orders(): void
    {
        $cancelledOrder = $this->createOrderFor($this->customerA, 'cancelled');
        $completedOrder = $this->createOrderFor($this->customerA, 'completed');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index', ['status' => 'completed']));

        $response->assertStatus(200);
        $response->assertSee($completedOrder->order_code);
        $response->assertDontSee($cancelledOrder->order_code);
    }

    /**
     * 15. Parameter status invalid diabaikan secara aman (menampilkan semua order)
     */
    public function test_invalid_status_filter_falls_back_to_all_orders(): void
    {
        $order1 = $this->createOrderFor($this->customerA, 'pending');
        $order2 = $this->createOrderFor($this->customerA, 'confirmed');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index', ['status' => 'invalid_injection_status']));

        $response->assertStatus(200);
        $response->assertSee($order1->order_code);
        $response->assertSee($order2->order_code);
    }

    /**
     * 16. Filter status dipertahankan pada query string paginasi
     */
    public function test_filter_keeps_pagination_query_string(): void
    {
        // Buat 12 order pending
        for ($i = 0; $i < 12; $i++) {
            $this->createOrderFor($this->customerA, 'pending');
        }

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index', ['status' => 'pending']));

        $response->assertStatus(200);
        // Link paginasi mengandung ?status=pending&page=2
        $response->assertSee('status=pending');
        $response->assertSee('page=2');
    }

    // ==========================================
    // GROUP D: PAGINATION (17 - 18)
    // ==========================================

    /**
     * 17. Riwayat pesanan dipaginasi 10 item per halaman
     */
    public function test_history_pagination_works(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $this->createOrderFor($this->customerA);
        }

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('page=2');
    }

    /**
     * 18. Paginasi hanya menghitung order milik customer yang sedang login
     */
    public function test_only_customer_own_orders_are_paginated(): void
    {
        // Customer A hanya punya 3 order
        for ($i = 0; $i < 3; $i++) {
            $this->createOrderFor($this->customerA);
        }

        // Customer B punya 12 order
        for ($i = 0; $i < 12; $i++) {
            $this->createOrderFor($this->customerB);
        }

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.index'));

        $response->assertStatus(200);
        // Customer A hanya punya 3 order, jadi tidak boleh ada link page=2
        $response->assertDontSee('page=2');
    }

    // ==========================================
    // GROUP E: ORDER DETAIL & SECURITY (19 - 28)
    // ==========================================

    /**
     * 19. Customer dapat melihat detail order miliknya
     */
    public function test_customer_can_view_own_order_detail(): void
    {
        $order = $this->createOrderFor($this->customerA);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee($order->order_code);
    }

    /**
     * 20. Customer tidak boleh melihat order customer lain (403 IDOR prevention)
     */
    public function test_customer_cannot_view_another_customers_order(): void
    {
        $orderB = $this->createOrderFor($this->customerB);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $orderB));

        $response->assertStatus(403);
    }

    /**
     * 21. Guest tidak boleh melihat detail order (redirect login)
     */
    public function test_guest_cannot_view_order_detail(): void
    {
        $order = $this->createOrderFor($this->customerA);

        $response = $this->get(route('customer.orders.show', $order));

        $response->assertRedirect(route('login'));
    }

    /**
     * 22. Admin tidak boleh melihat detail order via customer route (403)
     */
    public function test_admin_cannot_view_customer_order_detail(): void
    {
        $order = $this->createOrderFor($this->customerA);

        $response = $this->actingAs($this->admin)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(403);
    }

    /**
     * 23. Detail menampilkan rincian daftar penumpang
     */
    public function test_detail_displays_passengers(): void
    {
        $order = $this->createOrderFor($this->customerA);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Daftar Penumpang');
    }

    /**
     * 24. Detail menampilkan nomor kursi penumpang
     */
    public function test_detail_displays_seat_number(): void
    {
        $order = $this->createOrderFor($this->customerA);
        $seatNumber = $order->orderItems->first()->seat->seat_number;

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee($seatNumber);
    }

    /**
     * 25. Detail menampilkan nama lengkap penumpang
     */
    public function test_detail_displays_passenger_name(): void
    {
        $order = $this->createOrderFor($this->customerA);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee($this->customerA->name);
    }

    /**
     * 26. Detail menampilkan nomor identitas penumpang
     */
    public function test_detail_displays_passenger_identity(): void
    {
        $order = $this->createOrderFor($this->customerA);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('3201123456780001');
    }

    /**
     * 27. Detail menampilkan snapshot tarif per tiket
     */
    public function test_detail_displays_historical_item_price(): void
    {
        $order = $this->createOrderFor($this->customerA, 'pending', 250000);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Rp250.000');
    }

    /**
     * 28. Detail menampilkan total pesanan dari order
     */
    public function test_detail_displays_order_total(): void
    {
        $order = $this->createOrderFor($this->customerA, 'pending', 250000);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Total Pembayaran');
        $response->assertSee('Rp250.000');
    }

    // ==========================================
    // GROUP F: HISTORICAL PRICE (29 - 32)
    // ==========================================

    /**
     * 29. Order dibuat dengan snapshot harga 250.000
     */
    public function test_order_created_with_snapshot_price(): void
    {
        $order = $this->createOrderFor($this->customerA, 'pending', 250000);

        $this->assertEquals(250000, $order->orderItems->first()->price);
        $this->assertEquals(250000, $order->total_amount);
    }

    /**
     * 30. Perubahan harga trip di database tidak mengubah harga histori
     */
    public function test_order_detail_shows_snapshot_price_even_if_trip_price_changes(): void
    {
        $order = $this->createOrderFor($this->customerA, 'pending', 250000);

        // Trip mengalami kenaikan harga menjadi 350.000
        $this->trip->update(['price' => 350000]);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        // Halaman detail tetap menampilkan snapshot 250.000
        $response->assertSee('Rp250.000');
        $response->assertDontSee('Rp350.000');
    }

    /**
     * 31. Total pesanan tetap utuh meskipun harga trip berubah
     */
    public function test_order_total_remains_original_amount_when_trip_price_changes(): void
    {
        $order = $this->createOrderFor($this->customerA, 'pending', 250000);

        $this->trip->update(['price' => 400000]);

        $order->refresh();
        $this->assertEquals(250000, $order->total_amount);
    }

    /**
     * 32. Tampilan detail memuat tanggal pembuatan pesanan
     */
    public function test_order_detail_displays_created_at_date(): void
    {
        $date = Carbon::parse('2026-09-23 10:30:00', 'Asia/Jakarta');
        $order = $this->createOrderFor($this->customerA, 'pending', 250000, $date);

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('23 Sep 2026');
    }

    // ==========================================
    // GROUP G: STATUS LABELS (33 - 36)
    // ==========================================

    /**
     * 33. Status pending menampilkan label 'Menunggu Pembayaran'
     */
    public function test_pending_displays_menunggu_pembayaran(): void
    {
        $order = $this->createOrderFor($this->customerA, 'pending');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Menunggu Pembayaran');
    }

    /**
     * 34. Status confirmed menampilkan label 'Dikonfirmasi'
     */
    public function test_confirmed_displays_dikonfirmasi(): void
    {
        $order = $this->createOrderFor($this->customerA, 'confirmed');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Dikonfirmasi');
    }

    /**
     * 35. Status cancelled menampilkan label 'Dibatalkan'
     */
    public function test_cancelled_displays_dibatalkan(): void
    {
        $order = $this->createOrderFor($this->customerA, 'cancelled');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Dibatalkan');
    }

    /**
     * 36. Status completed menampilkan label 'Selesai'
     */
    public function test_completed_displays_selesai(): void
    {
        $order = $this->createOrderFor($this->customerA, 'completed');

        $response = $this->actingAs($this->customerA)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Selesai');
    }

    // ==========================================
    // GROUP H: DASHBOARD & NAVIGATION (37 - 38)
    // ==========================================

    /**
     * 37. Dasbor customer memuat navigasi ke Riwayat Pesanan
     */
    public function test_customer_dashboard_contains_order_history_link(): void
    {
        $response = $this->actingAs($this->customerA)
            ->get(route('customer.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Pesanan');
        $response->assertSee(route('customer.orders.index'));
    }

    /**
     * 38. Navbar customer memuat tautan ke Riwayat Pesanan
     */
    public function test_customer_navbar_contains_order_history_link(): void
    {
        $response = $this->actingAs($this->customerA)
            ->get(route('customer.trips.index'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Pesanan');
        $response->assertSee(route('customer.orders.index'));
    }
}

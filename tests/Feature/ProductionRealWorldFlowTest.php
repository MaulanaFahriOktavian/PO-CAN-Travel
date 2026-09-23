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

class ProductionRealWorldFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Bus $bus;
    protected Route $route;
    protected Trip $trip;
    protected Seat $seat1;
    protected Seat $seat2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
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
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $this->trip = Trip::create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'departure_at' => Carbon::now('Asia/Jakarta')->addDays(3)->setTime(8, 0),
            'arrival_at' => Carbon::now('Asia/Jakarta')->addDays(3)->setTime(16, 0),
            'price' => 250000,
            'status' => 'scheduled',
        ]);
    }

    /**
     * SCENARIO 1: Pengguna baru melakukan registrasi, mencari tiket, memilih kursi,
     * melengkapi data penumpang, membuat order, melihat bukti tiket, dan logout.
     */
    public function test_scenario_1_end_to_end_customer_booking_flow(): void
    {
        // 1. Registrasi Akun Baru
        $registerResponse = $this->post(route('register'), [
            'name' => 'Ahmad Subagyo',
            'email' => 'ahmad.subagyo@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $registerResponse->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'ahmad.subagyo@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role);

        // 2. Akses Pencarian Perjalanan (Jakarta -> Jepara)
        $searchResponse = $this->get(route('customer.trips.index', [
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'date' => Carbon::now('Asia/Jakarta')->addDays(3)->format('Y-m-d'),
        ]));

        $searchResponse->assertOk();
        $searchResponse->assertSee('Jakarta');
        $searchResponse->assertSee('Jepara');
        $searchResponse->assertSee('CAN Executive 01');

        // 3. Buka Detail Perjalanan
        $tripResponse = $this->get(route('customer.trips.show', $this->trip));
        $tripResponse->assertOk();
        $tripResponse->assertSee('Lanjut Pilih Kursi');

        // 4. Pilih Kursi (1A)
        $seatPageResponse = $this->get(route('customer.trips.seats', $this->trip));
        $seatPageResponse->assertOk();
        $seatPageResponse->assertSee('1A');

        $selectSeatResponse = $this->post(route('customer.trips.seats.store', $this->trip), [
            'seat_ids' => [$this->seat1->id],
        ]);

        $selectSeatResponse->assertRedirect(route('customer.trips.booking', $this->trip));
        $this->assertEquals([$this->seat1->id], session('booking.seat_ids'));

        // 5. Buka Form Booking & Isi Data Penumpang
        $bookingPageResponse = $this->get(route('customer.trips.booking', $this->trip));
        $bookingPageResponse->assertOk();
        $bookingPageResponse->assertSee('1A');

        $storeOrderResponse = $this->post(route('customer.trips.booking.store', $this->trip), [
            'trip_id' => $this->trip->id,
            'passengers' => [
                $this->seat1->id => [
                    'name' => 'Ahmad Subagyo',
                    'identity' => '3301234567890001',
                ],
            ],
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals(250000, $order->total_amount);

        $storeOrderResponse->assertRedirect(route('customer.orders.show', $order));

        // 6. Buka Detail Pesanan / E-Ticket Bukti Pemesanan
        $orderDetailResponse = $this->get(route('customer.orders.show', $order));
        $orderDetailResponse->assertOk();
        $orderDetailResponse->assertSee($order->order_code);
        $orderDetailResponse->assertSee('Menunggu Pembayaran');
        $orderDetailResponse->assertSee('Ahmad Subagyo');
        $orderDetailResponse->assertSee('3301234567890001');

        // 7. Buka Riwayat Pesanan
        $historyResponse = $this->get(route('customer.orders.index'));
        $historyResponse->assertOk();
        $historyResponse->assertSee($order->order_code);

        // 8. Logout
        $logoutResponse = $this->post(route('logout'));
        $logoutResponse->assertRedirect(route('home'));
        $this->assertGuest();
    }

    /**
     * SCENARIO 2: Admin login, melihat pesanan baru, membuka rincian pesanan,
     * mengubah status dari pending -> confirmed, dan customer melihat status baru.
     */
    public function test_scenario_2_admin_order_status_transition(): void
    {
        $customer = User::create([
            'name' => 'Budi Pengguna',
            'email' => 'budi.pengguna@example.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $admin = User::create([
            'name' => 'Admin Operasional',
            'email' => 'admin.ops@pocantravel.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'trip_id' => $this->trip->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $this->seat1->id,
            'passenger_name' => 'Budi Pengguna',
            'passenger_identity' => '3301987654320001',
            'price' => 250000,
        ]);

        // 1. Admin login & melihat pesanan di dashboard
        $this->actingAs($admin);
        $adminOrdersResponse = $this->get(route('admin.orders.index'));
        $adminOrdersResponse->assertOk();
        $adminOrdersResponse->assertSee($order->order_code);

        // 2. Buka detail pesanan di panel admin
        $adminDetailResponse = $this->get(route('admin.orders.show', $order));
        $adminDetailResponse->assertOk();
        $adminDetailResponse->assertSee($order->order_code);
        $adminDetailResponse->assertSee('Konfirmasi Pesanan');

        // 3. Update status: pending -> confirmed
        $updateStatusResponse = $this->patch(route('admin.orders.status', $order), [
            'status' => 'confirmed',
        ]);
        $updateStatusResponse->assertRedirect(route('admin.orders.show', $order));

        $order->refresh();
        $this->assertEquals('confirmed', $order->status);

        // 4. Verifikasi sisi Customer melihat status Dikonfirmasi
        $this->actingAs($customer);
        $customerDetailResponse = $this->get(route('customer.orders.show', $order));
        $customerDetailResponse->assertOk();
        $customerDetailResponse->assertSee('Dikonfirmasi');
    }

    /**
     * SCENARIO 3: Pencegahan Double Booking.
     * Ketika kursi sudah dipesan oleh Customer A, Customer B tidak bisa memesannya.
     */
    public function test_scenario_3_seat_conflict_double_booking_prevention(): void
    {
        $customerA = User::create([
            'name' => 'Customer A',
            'email' => 'customera@example.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $customerB = User::create([
            'name' => 'Customer B',
            'email' => 'customerb@example.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        // Customer A memesan seat1 (1A)
        $orderA = Order::create([
            'user_id' => $customerA->id,
            'trip_id' => $this->trip->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $orderA->id,
            'seat_id' => $this->seat1->id,
            'passenger_name' => 'Customer A',
            'passenger_identity' => '3301111111110001',
            'price' => 250000,
        ]);

        // Customer B mencoba memilih kursi 1A pada trip yang sama
        $this->actingAs($customerB);

        $response = $this->post(route('customer.trips.seats.store', $this->trip), [
            'seat_ids' => [$this->seat1->id],
        ]);

        $response->assertSessionHasErrors('seat_ids');
    }

    /**
     * SCENARIO 4: Pemeriksaan Otorisasi & Pencegahan IDOR.
     */
    public function test_scenario_4_authorization_and_idor_prevention(): void
    {
        $customerA = User::create([
            'name' => 'Customer A',
            'email' => 'user.a@example.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $customerB = User::create([
            'name' => 'Customer B',
            'email' => 'user.b@example.com',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $admin = User::create([
            'name' => 'Admin Role',
            'email' => 'admin.role@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $orderA = Order::create([
            'user_id' => $customerA->id,
            'trip_id' => $this->trip->id,
            'order_code' => Order::generateOrderCode(),
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        // 1. Customer B mencoba membuka order Customer A -> 403 Forbidden
        $this->actingAs($customerB);
        $this->get(route('customer.orders.show', $orderA))->assertStatus(403);

        // 2. Customer mencoba mengakses area Admin -> 403 Forbidden
        $this->get(route('admin.dashboard'))->assertStatus(403);
        $this->get(route('admin.orders.index'))->assertStatus(403);

        // 3. Guest mencoba mengakses area Customer -> Redirect ke Login
        auth()->logout();
        $this->get(route('customer.dashboard'))->assertRedirect(route('login'));
        $this->get(route('customer.orders.index'))->assertRedirect(route('login'));
    }
}

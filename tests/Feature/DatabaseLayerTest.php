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
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseLayerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1. Bus memiliki banyak seat.
     */
    public function test_bus_has_many_seats(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 2,
        ]);

        $seat1 = Seat::create(['bus_id' => $bus->id, 'seat_number' => '1A']);
        $seat2 = Seat::create(['bus_id' => $bus->id, 'seat_number' => '1B']);

        $this->assertCount(2, $bus->seats);
        $this->assertTrue($bus->seats->contains($seat1));
        $this->assertTrue($bus->seats->contains($seat2));
    }

    /**
     * 2. Bus memiliki banyak trip.
     */
    public function test_bus_has_many_trips(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $trip1 = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $trip2 = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDays(2),
            'arrival_at' => Carbon::now()->addDays(2)->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $this->assertCount(2, $bus->trips);
        $this->assertTrue($bus->trips->contains($trip1));
        $this->assertTrue($bus->trips->contains($trip2));
    }

    /**
     * 3. Route memiliki banyak trip.
     */
    public function test_route_has_many_trips(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $this->assertCount(1, $route->trips);
        $this->assertTrue($route->trips->contains($trip));
    }

    /**
     * 4. Trip memiliki bus.
     */
    public function test_trip_belongs_to_bus(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Royal 02',
            'code' => 'CAN-RY02',
            'total_seats' => 28,
        ]);

        $route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Semarang',
            'duration' => 360,
        ]);

        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(6),
            'price' => 220000,
            'status' => 'scheduled',
        ]);

        $this->assertInstanceOf(Bus::class, $trip->bus);
        $this->assertEquals($bus->id, $trip->bus->id);
    }

    /**
     * 5. Trip memiliki route.
     */
    public function test_trip_belongs_to_route(): void
    {
        $bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $this->assertInstanceOf(Route::class, $trip->route);
        $this->assertEquals($route->id, $trip->route->id);
    }

    /**
     * 6. User memiliki order.
     */
    public function test_user_has_many_orders(): void
    {
        $user = User::create([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan@test.com',
            'password' => 'secret123',
            'role' => 'customer',
        ]);

        $bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'order_code' => 'ORD-TEST-001',
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        $this->assertCount(1, $user->orders);
        $this->assertTrue($user->orders->contains($order));
        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    /**
     * 7. Order memiliki trip.
     */
    public function test_order_belongs_to_trip(): void
    {
        $user = User::create([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan2@test.com',
            'password' => 'secret123',
            'role' => 'customer',
        ]);

        $bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'order_code' => 'ORD-TEST-002',
            'total_amount' => 250000,
            'status' => 'confirmed',
        ]);

        $this->assertInstanceOf(Trip::class, $order->trip);
        $this->assertEquals($trip->id, $order->trip->id);
        $this->assertCount(1, $trip->orders);
    }

    /**
     * 8. Order memiliki order items.
     */
    public function test_order_has_many_order_items(): void
    {
        $user = User::create([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan3@test.com',
            'password' => 'secret123',
            'role' => 'customer',
        ]);

        $bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $seat = Seat::create(['bus_id' => $bus->id, 'seat_number' => '1A']);

        $route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'order_code' => 'ORD-TEST-003',
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => 'Budi Santoso',
            'passenger_identity' => '3320011234560001',
            'price' => 250000,
        ]);

        $this->assertCount(1, $order->orderItems);
        $this->assertTrue($order->orderItems->contains($orderItem));
        $this->assertInstanceOf(Order::class, $orderItem->order);
        $this->assertEquals($order->id, $orderItem->order->id);
    }

    /**
     * 9. Order item memiliki seat.
     */
    public function test_order_item_belongs_to_seat(): void
    {
        $user = User::create([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan4@test.com',
            'password' => 'secret123',
            'role' => 'customer',
        ]);

        $bus = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $seat = Seat::create(['bus_id' => $bus->id, 'seat_number' => '2B']);

        $route = Route::create([
            'origin' => 'Jakarta',
            'destination' => 'Jepara',
            'duration' => 480,
        ]);

        $trip = Trip::create([
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_at' => Carbon::now()->addDay(),
            'arrival_at' => Carbon::now()->addDay()->addHours(8),
            'price' => 250000,
            'status' => 'scheduled',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'order_code' => 'ORD-TEST-004',
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'seat_id' => $seat->id,
            'passenger_name' => 'Budi Santoso',
            'passenger_identity' => '3320011234560001',
            'price' => 250000,
        ]);

        $this->assertInstanceOf(Seat::class, $orderItem->seat);
        $this->assertEquals($seat->id, $orderItem->seat->id);
        $this->assertTrue($seat->orderItems->contains($orderItem));
    }

    /**
     * 10. Unique seat number per bus bekerja.
     */
    public function test_unique_seat_number_per_bus_constraint(): void
    {
        $bus1 = Bus::create([
            'name' => 'CAN Executive 01',
            'code' => 'CAN-EX01',
            'total_seats' => 30,
        ]);

        $bus2 = Bus::create([
            'name' => 'CAN Royal 02',
            'code' => 'CAN-RY02',
            'total_seats' => 28,
        ]);

        // Kursi 1A pada bus 1
        Seat::create(['bus_id' => $bus1->id, 'seat_number' => '1A']);

        // Kursi 1A pada bus 2 harus valid karena bus berbeda
        $seatBus2 = Seat::create(['bus_id' => $bus2->id, 'seat_number' => '1A']);
        $this->assertNotNull($seatBus2->id);

        // Duplikat kursi 1A pada bus 1 harus gagal dan melempar QueryException
        $this->expectException(QueryException::class);
        Seat::create(['bus_id' => $bus1->id, 'seat_number' => '1A']);
    }
}

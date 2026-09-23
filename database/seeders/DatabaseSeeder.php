<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Route;
use App\Models\Seat;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users (1 Admin, 1 Customer)
        User::firstOrCreate(
            ['email' => 'admin@pocantravel.com'],
            [
                'name' => 'Admin PO CAN',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'customer@pocantravel.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        // 2. Buses
        $busesData = [
            [
                'name' => 'CAN Executive 01',
                'code' => 'CAN-EX01',
                'total_seats' => 30,
            ],
            [
                'name' => 'CAN Royal 02',
                'code' => 'CAN-RY02',
                'total_seats' => 28,
            ],
        ];

        $buses = [];
        foreach ($busesData as $busData) {
            $bus = Bus::firstOrCreate(
                ['code' => $busData['code']],
                [
                    'name' => $busData['name'],
                    'total_seats' => $busData['total_seats'],
                ]
            );
            $buses[$bus->code] = $bus;

            // 3. Seats (Generate kursi sesuai total_seats armada bus)
            $seatLetters = ['A', 'B', 'C', 'D'];
            $seatCount = 0;
            $row = 1;

            while ($seatCount < $bus->total_seats) {
                foreach ($seatLetters as $letter) {
                    if ($seatCount >= $bus->total_seats) {
                        break;
                    }

                    Seat::firstOrCreate([
                        'bus_id' => $bus->id,
                        'seat_number' => "{$row}{$letter}",
                    ]);

                    $seatCount++;
                }
                $row++;
            }
        }

        // 4. Routes (Rute nyata perjalanan antarkota)
        $routesData = [
            [
                'origin' => 'Jakarta',
                'destination' => 'Jepara',
                'duration' => 480, // 8 jam
            ],
            [
                'origin' => 'Jepara',
                'destination' => 'Jakarta',
                'duration' => 480, // 8 jam
            ],
            [
                'origin' => 'Jakarta',
                'destination' => 'Semarang',
                'duration' => 360, // 6 jam
            ],
        ];

        $routes = [];
        foreach ($routesData as $rData) {
            $route = Route::firstOrCreate(
                [
                    'origin' => $rData['origin'],
                    'destination' => $rData['destination'],
                ],
                [
                    'duration' => $rData['duration'],
                ]
            );
            $routes["{$route->origin}-{$route->destination}"] = $route;
        }

        // 5. Trips (Jadwal perjalanan dengan status scheduled)
        $now = Carbon::now('Asia/Jakarta');

        $tripsData = [
            [
                'bus_code' => 'CAN-EX01',
                'route_key' => 'Jakarta-Jepara',
                'departure_at' => $now->copy()->addDay()->setTime(7, 0, 0),
                'arrival_at' => $now->copy()->addDay()->setTime(15, 0, 0),
                'price' => 250000,
                'status' => 'scheduled',
            ],
            [
                'bus_code' => 'CAN-RY02',
                'route_key' => 'Jakarta-Jepara',
                'departure_at' => $now->copy()->addDay()->setTime(19, 30, 0),
                'arrival_at' => $now->copy()->addDays(2)->setTime(3, 30, 0),
                'price' => 280000,
                'status' => 'scheduled',
            ],
            [
                'bus_code' => 'CAN-EX01',
                'route_key' => 'Jepara-Jakarta',
                'departure_at' => $now->copy()->addDays(2)->setTime(8, 0, 0),
                'arrival_at' => $now->copy()->addDays(2)->setTime(16, 0, 0),
                'price' => 250000,
                'status' => 'scheduled',
            ],
            [
                'bus_code' => 'CAN-RY02',
                'route_key' => 'Jakarta-Semarang',
                'departure_at' => $now->copy()->addDays(3)->setTime(9, 0, 0),
                'arrival_at' => $now->copy()->addDays(3)->setTime(15, 0, 0),
                'price' => 220000,
                'status' => 'scheduled',
            ],
        ];

        foreach ($tripsData as $tData) {
            $bus = $buses[$tData['bus_code']];
            $route = $routes[$tData['route_key']];

            Trip::firstOrCreate(
                [
                    'bus_id' => $bus->id,
                    'route_id' => $route->id,
                    'departure_at' => $tData['departure_at'],
                ],
                [
                    'arrival_at' => $tData['arrival_at'],
                    'price' => $tData['price'],
                    'status' => $tData['status'],
                ]
            );
        }
    }
}

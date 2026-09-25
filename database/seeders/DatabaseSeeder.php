<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusImage;
use App\Models\Facility;
use App\Models\Order;
use App\Models\OrderItem;
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
     * Seed the application's database with complete, realistic dummy data.
     */
    public function run(): void
    {
        // ==========================================
        // 1. USERS (Admin & Multiple Customers)
        // ==========================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@pocantravel.com'],
            [
                'name' => 'Admin PO CAN',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $customerBudi = User::firstOrCreate(
            ['email' => 'customer@pocantravel.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        // ==========================================
        // 2. MASTER FACILITIES (Fasilitas Operasional)
        // ==========================================
        $facilitiesData = [
            [
                'name' => 'Pendingin Udara (AC)',
                'slug' => 'ac',
                'icon' => 'wind',
                'description' => 'Sistem pendingin udara kabin sentral yang menjaga suhu tetap sejuk dan stabil sepanjang perjalanan.',
            ],
            [
                'name' => 'Reclining Seat 2+2',
                'slug' => 'reclining-seat',
                'icon' => 'seat',
                'description' => 'Kursi ergonomis dengan sandaran yang dapat direbahkan serta ruang kaki (legroom) yang lapang.',
            ],
            [
                'name' => 'USB Charging Port',
                'slug' => 'usb-charging',
                'icon' => 'bolt',
                'description' => 'Port pengisian daya ponsel pada tiap baris kursi untuk menjaga perangkat Anda tetap aktif.',
            ],
            [
                'name' => 'Bagasi Kabin & Lambung',
                'slug' => 'bagasi',
                'icon' => 'briefcase',
                'description' => 'Ruang bagasi kabin atas untuk tas jinjing dan kompartemen lambung bus untuk koper besar.',
            ],
            [
                'name' => 'Air Mineral Gratis',
                'slug' => 'air-mineral',
                'icon' => 'cup',
                'description' => 'Penyediaan air mineral kemasan untuk kenyamanan setiap penumpang selama perjalanan.',
            ],
            [
                'name' => 'Lampu Baca Personal',
                'slug' => 'reading-lamp',
                'icon' => 'lamp',
                'description' => 'Lampu baca terarah di atas setiap kursi untuk membaca nyaman pada malam hari.',
            ],
            [
                'name' => 'Selimut & Bantal',
                'slug' => 'blanket-pillow',
                'icon' => 'bed',
                'description' => 'Perlengkapan istirahat higienis yang disanitasi sebelum setiap trip pemberangkatan.',
            ],
        ];

        $facilityModels = [];
        foreach ($facilitiesData as $fData) {
            $facilityModels[$fData['slug']] = Facility::firstOrCreate(
                ['slug' => $fData['slug']],
                [
                    'name' => $fData['name'],
                    'icon' => $fData['icon'],
                    'description' => $fData['description'],
                ]
            );
        }

        // ==========================================
        // 3. BUSES & SEATS (Katalog Armada Lengkap)
        // ==========================================
        $busesData = [
            [
                'name' => 'CAN Executive 01',
                'code' => 'CAN-EX01',
                'total_seats' => 30,
                'bus_type' => 'Executive Class',
                'description' => 'Armada Executive Class dengan konfigurasi kursi 2+2 berjumlah 30 kursi. Dirancang khusus untuk perjalanan jarak jauh Jakarta - Jepara dengan kenyamanan maksimal dan suspensi udara yang stabil.',
                'facilities' => ['ac', 'reclining-seat', 'usb-charging', 'bagasi', 'air-mineral', 'reading-lamp', 'blanket-pillow'],
                'images' => [
                    ['path' => 'images/hero/hero-bus.jpg', 'caption' => 'Tampak Eksterior Bus di Jalan Tol', 'is_primary' => true, 'order' => 1],
                    ['path' => 'images/about/fleet-comfort.jpg', 'caption' => 'Interior Kabin Eksekutif 2+2', 'is_primary' => false, 'order' => 2],
                ],
            ],
            [
                'name' => 'CAN Royal 02',
                'code' => 'CAN-RY02',
                'total_seats' => 28,
                'bus_type' => 'Royal Class',
                'description' => 'Armada Royal Class dengan kapasitas 28 kursi eksklusif. Menawarkan ruang gerak lebih luas dan kenyamanan kabin premium untuk rute Jakarta - Semarang dan sekitarnya.',
                'facilities' => ['ac', 'reclining-seat', 'usb-charging', 'bagasi', 'air-mineral', 'reading-lamp', 'blanket-pillow'],
                'images' => [
                    ['path' => 'images/hero/hero-bus.jpg', 'caption' => 'Armada Bus Royal Class PO CAN Travel', 'is_primary' => true, 'order' => 1],
                    ['path' => 'images/about/fleet-comfort.jpg', 'caption' => 'Kenyamanan Tempat Duduk Ergonomis', 'is_primary' => false, 'order' => 2],
                ],
            ],
            [
                'name' => 'CAN Suite 03',
                'code' => 'CAN-ST03',
                'total_seats' => 24,
                'bus_type' => 'Suite Class',
                'description' => 'Armada termewah PO CAN Travel dengan hanya 24 kursi lapang bersuspensi udara independen. Cocok untuk istirahat malam santai rute Jakarta - Yogyakarta.',
                'facilities' => ['ac', 'reclining-seat', 'usb-charging', 'bagasi', 'air-mineral', 'reading-lamp', 'blanket-pillow'],
                'images' => [
                    ['path' => 'images/hero/hero-bus.jpg', 'caption' => 'Eksterior Elegan Suite Class', 'is_primary' => true, 'order' => 1],
                    ['path' => 'images/about/fleet-comfort.jpg', 'caption' => 'Kabin Nyaman Suite Class', 'is_primary' => false, 'order' => 2],
                ],
            ],
            [
                'name' => 'CAN Premier 04',
                'code' => 'CAN-PR04',
                'total_seats' => 32,
                'bus_type' => 'Premier Class',
                'description' => 'Armada tangguh efisien berkapasitas 32 kursi dengan AC dingin dan pengisian daya USB untuk koridor favorit Jakarta - Bandung.',
                'facilities' => ['ac', 'reclining-seat', 'usb-charging', 'bagasi', 'air-mineral'],
                'images' => [
                    ['path' => 'images/hero/hero-bus.jpg', 'caption' => 'Armada Premier Class PO CAN Travel', 'is_primary' => true, 'order' => 1],
                    ['path' => 'images/about/fleet-comfort.jpg', 'caption' => 'Kenyamanan Kabin Premier', 'is_primary' => false, 'order' => 2],
                ],
            ],
        ];

        $buses = [];
        foreach ($busesData as $busData) {
            $bus = Bus::firstOrCreate(
                ['code' => $busData['code']],
                [
                    'name' => $busData['name'],
                    'total_seats' => $busData['total_seats'],
                    'bus_type' => $busData['bus_type'],
                    'description' => $busData['description'],
                ]
            );
            $bus->update([
                'bus_type' => $busData['bus_type'],
                'description' => $busData['description'],
            ]);
            $buses[$bus->code] = $bus;

            // Facilities sync
            $facilityIds = [];
            foreach ($busData['facilities'] as $fSlug) {
                if (isset($facilityModels[$fSlug])) {
                    $facilityIds[] = $facilityModels[$fSlug]->id;
                }
            }
            $bus->facilities()->sync($facilityIds);

            // Images
            foreach ($busData['images'] as $imgData) {
                BusImage::firstOrCreate(
                    [
                        'bus_id' => $bus->id,
                        'image_path' => $imgData['path'],
                    ],
                    [
                        'caption' => $imgData['caption'],
                        'is_primary' => $imgData['is_primary'],
                        'sort_order' => $imgData['order'],
                    ]
                );
            }

            // Seats generation (2+2 layout)
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

        // ==========================================
        // 4. ROUTES (Direktori Rute Antarkota Nyata)
        // ==========================================
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
            [
                'origin' => 'Semarang',
                'destination' => 'Jakarta',
                'duration' => 360, // 6 jam
            ],
            [
                'origin' => 'Jakarta',
                'destination' => 'Bandung',
                'duration' => 180, // 3 jam
            ],
            [
                'origin' => 'Bandung',
                'destination' => 'Jakarta',
                'duration' => 180, // 3 jam
            ],
            [
                'origin' => 'Jakarta',
                'destination' => 'Yogyakarta',
                'duration' => 450, // 7.5 jam
            ],
            [
                'origin' => 'Yogyakarta',
                'destination' => 'Jakarta',
                'duration' => 450, // 7.5 jam
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

        // ==========================================
        // 5. TRIPS (Jadwal Operasional Lengkap)
        // ==========================================
        $now = Carbon::now('Asia/Jakarta');

        $tripsData = [
            // --- Hari Ini (Today's Trips) ---
            [
                'key' => 'today_jkt_bdg',
                'bus_code' => 'CAN-PR04',
                'route_key' => 'Jakarta-Bandung',
                'departure_at' => $now->copy()->setTime(14, 0, 0),
                'arrival_at' => $now->copy()->setTime(17, 0, 0),
                'price' => 140000,
                'status' => 'scheduled',
            ],
            [
                'key' => 'today_jkt_smg',
                'bus_code' => 'CAN-RY02',
                'route_key' => 'Jakarta-Semarang',
                'departure_at' => $now->copy()->setTime(19, 0, 0),
                'arrival_at' => $now->copy()->addDay()->setTime(1, 0, 0),
                'price' => 220000,
                'status' => 'scheduled',
            ],

            // --- Besok (Tomorrow's Trips) ---
            [
                'key' => 'tomorrow_jkt_jpr_morning',
                'bus_code' => 'CAN-EX01',
                'route_key' => 'Jakarta-Jepara',
                'departure_at' => $now->copy()->addDay()->setTime(7, 0, 0),
                'arrival_at' => $now->copy()->addDay()->setTime(15, 0, 0),
                'price' => 250000,
                'status' => 'scheduled',
            ],
            [
                'key' => 'tomorrow_jkt_jpr_night',
                'bus_code' => 'CAN-RY02',
                'route_key' => 'Jakarta-Jepara',
                'departure_at' => $now->copy()->addDay()->setTime(19, 30, 0),
                'arrival_at' => $now->copy()->addDays(2)->setTime(3, 30, 0),
                'price' => 280000,
                'status' => 'scheduled',
            ],
            [
                'key' => 'tomorrow_jkt_yog_night',
                'bus_code' => 'CAN-ST03',
                'route_key' => 'Jakarta-Yogyakarta',
                'departure_at' => $now->copy()->addDay()->setTime(20, 0, 0),
                'arrival_at' => $now->copy()->addDays(2)->setTime(3, 30, 0),
                'price' => 275000,
                'status' => 'scheduled',
            ],

            // --- Lusa (Day 2-3) ---
            [
                'key' => 'day2_jpr_jkt',
                'bus_code' => 'CAN-EX01',
                'route_key' => 'Jepara-Jakarta',
                'departure_at' => $now->copy()->addDays(2)->setTime(8, 0, 0),
                'arrival_at' => $now->copy()->addDays(2)->setTime(16, 0, 0),
                'price' => 250000,
                'status' => 'scheduled',
            ],
            [
                'key' => 'day2_jkt_smg',
                'bus_code' => 'CAN-RY02',
                'route_key' => 'Jakarta-Semarang',
                'departure_at' => $now->copy()->addDays(2)->setTime(9, 0, 0),
                'arrival_at' => $now->copy()->addDays(2)->setTime(15, 0, 0),
                'price' => 220000,
                'status' => 'scheduled',
            ],
            [
                'key' => 'day3_bdg_jkt',
                'bus_code' => 'CAN-PR04',
                'route_key' => 'Bandung-Jakarta',
                'departure_at' => $now->copy()->addDays(3)->setTime(10, 0, 0),
                'arrival_at' => $now->copy()->addDays(3)->setTime(13, 0, 0),
                'price' => 140000,
                'status' => 'scheduled',
            ],

            // --- Riwayat Lampau (Completed Trips) ---
            [
                'key' => 'past_jpr_jkt',
                'bus_code' => 'CAN-EX01',
                'route_key' => 'Jepara-Jakarta',
                'departure_at' => $now->copy()->subDays(3)->setTime(8, 0, 0),
                'arrival_at' => $now->copy()->subDays(3)->setTime(16, 0, 0),
                'price' => 250000,
                'status' => 'completed',
            ],
        ];

        $trips = [];
        foreach ($tripsData as $tData) {
            $bus = $buses[$tData['bus_code']];
            $route = $routes[$tData['route_key']];

            $trip = Trip::firstOrCreate(
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
            $trips[$tData['key']] = $trip;
        }

        // ==========================================
        // 6. ORDERS & ORDER ITEMS (Pesanan Riil Beragam Status)
        // ==========================================

        // --- ORDER 1: Confirmed (Budi Santoso - Trip Besok Jakarta -> Jepara) ---
        $tripBesok = $trips['tomorrow_jkt_jpr_morning'];
        $busBesok = $buses['CAN-EX01'];
        $seat1A = Seat::where('bus_id', $busBesok->id)->where('seat_number', '1A')->first();
        $seat1B = Seat::where('bus_id', $busBesok->id)->where('seat_number', '1B')->first();

        if ($seat1A && $seat1B) {
            $order1 = Order::firstOrCreate(
                ['order_code' => 'PCT-' . $now->copy()->format('Ymd') . '-BDS001'],
                [
                    'user_id' => $customerBudi->id,
                    'trip_id' => $tripBesok->id,
                    'total_amount' => $tripBesok->price * 2,
                    'status' => 'confirmed',
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order1->id, 'seat_id' => $seat1A->id],
                [
                    'passenger_name' => 'Budi Santoso',
                    'passenger_identity' => '3320011504900001',
                    'price' => $tripBesok->price,
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order1->id, 'seat_id' => $seat1B->id],
                [
                    'passenger_name' => 'Dewi Sartika',
                    'passenger_identity' => '3320015509920002',
                    'price' => $tripBesok->price,
                ]
            );
        }

        // --- ORDER 2: Pending (Siti Rahmawati - Perlu Tindakan / Verifikasi Admin) ---
        $tripLusa = $trips['day2_jkt_smg'];
        $busLusa = $buses['CAN-RY02'];
        $seat2A = Seat::where('bus_id', $busLusa->id)->where('seat_number', '2A')->first();
        $seat2B = Seat::where('bus_id', $busLusa->id)->where('seat_number', '2B')->first();

        if ($seat2A && $seat2B) {
            $order2 = Order::firstOrCreate(
                ['order_code' => 'PCT-' . $now->copy()->format('Ymd') . '-STR002'],
                [
                    'user_id' => $customerSiti->id,
                    'trip_id' => $tripLusa->id,
                    'total_amount' => $tripLusa->price * 2,
                    'status' => 'pending',
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order2->id, 'seat_id' => $seat2A->id],
                [
                    'passenger_name' => 'Siti Rahmawati',
                    'passenger_identity' => '3171014502930003',
                    'price' => $tripLusa->price,
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order2->id, 'seat_id' => $seat2B->id],
                [
                    'passenger_name' => 'Hendra Wijaya',
                    'passenger_identity' => '3171011208910004',
                    'price' => $tripLusa->price,
                ]
            );
        }

        // --- ORDER 3: Confirmed (Ahmad Fauzi - Trip Hari Ini Jakarta -> Bandung) ---
        $tripHariIni = $trips['today_jkt_bdg'];
        $busHariIni = $buses['CAN-PR04'];
        $seat3C = Seat::where('bus_id', $busHariIni->id)->where('seat_number', '3C')->first();

        if ($seat3C) {
            $order3 = Order::firstOrCreate(
                ['order_code' => 'PCT-' . $now->copy()->format('Ymd') . '-AHF003'],
                [
                    'user_id' => $customerAhmad->id,
                    'trip_id' => $tripHariIni->id,
                    'total_amount' => $tripHariIni->price,
                    'status' => 'confirmed',
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order3->id, 'seat_id' => $seat3C->id],
                [
                    'passenger_name' => 'Ahmad Fauzi',
                    'passenger_identity' => '3273012206950005',
                    'price' => $tripHariIni->price,
                ]
            );
        }

        // --- ORDER 4: Completed (Budi Santoso - Riwayat Perjalanan Lampau) ---
        $tripLampau = $trips['past_jpr_jkt'];
        $busLampau = $buses['CAN-EX01'];
        $seatLampau = Seat::where('bus_id', $busLampau->id)->where('seat_number', '2C')->first();

        if ($seatLampau) {
            $order4 = Order::firstOrCreate(
                ['order_code' => 'PCT-' . $now->copy()->subDays(3)->format('Ymd') . '-BDS004'],
                [
                    'user_id' => $customerBudi->id,
                    'trip_id' => $tripLampau->id,
                    'total_amount' => $tripLampau->price,
                    'status' => 'completed',
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order4->id, 'seat_id' => $seatLampau->id],
                [
                    'passenger_name' => 'Budi Santoso',
                    'passenger_identity' => '3320011504900001',
                    'price' => $tripLampau->price,
                ]
            );
        }

        // --- ORDER 5: Cancelled (Dewi Lestari) ---
        $tripYog = $trips['tomorrow_jkt_yog_night'];
        $busYog = $buses['CAN-ST03'];
        $seatYog = Seat::where('bus_id', $busYog->id)->where('seat_number', '4D')->first();

        if ($seatYog) {
            $order5 = Order::firstOrCreate(
                ['order_code' => 'PCT-' . $now->copy()->format('Ymd') . '-DWL005'],
                [
                    'user_id' => $customerDewi->id,
                    'trip_id' => $tripYog->id,
                    'total_amount' => $tripYog->price,
                    'status' => 'cancelled',
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order5->id, 'seat_id' => $seatYog->id],
                [
                    'passenger_name' => 'Dewi Lestari',
                    'passenger_identity' => '3471016011980006',
                    'price' => $tripYog->price,
                ]
            );
        }
    }
}

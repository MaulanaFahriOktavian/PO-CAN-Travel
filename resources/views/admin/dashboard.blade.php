@extends('layouts.app')

@section('title', 'Dasbor Operasional Admin - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-14 bg-[#FBFAF6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        {{-- Header Operasional --}}
        <div class="border-b pb-6 flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-4 border-[#D9D5CA]">
            <div>
                <span class="text-xs uppercase tracking-wider font-bold block mb-1 text-[#21483C]">
                    Konsol Operasional &bull; PO CAN Travel
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-[#1C2522]">
                    Dasbor Administrator
                </h1>
                <p class="mt-1 text-sm text-[#66716C]">
                    Monitoring verifikasi pesanan, jadwal keberangkatan bus, dan alokasi armada antarkota.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full border bg-[#F5F1E8] text-[#357A62] border-[#357A62]/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#357A62]"></span>
                    Sistem Operasional Aktif
                </span>
            </div>
        </div>

        {{-- Pintasan Navigasi Operasional Utama --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a
                href="{{ route('admin.orders.index') }}"
                class="bg-white border border-[#D9D5CA] rounded-xl p-4 transition-colors hover:border-[#21483C] shadow-xs group"
            >
                <span class="text-xs uppercase tracking-wider font-bold block text-[#66716C]">Manajemen</span>
                <div class="text-sm font-bold mt-1 text-[#1C2522] group-hover:text-[#21483C]">Kelola Pesanan</div>
                <p class="text-[11px] mt-0.5 text-[#66716C]">Verifikasi tiket &amp; pembayaran</p>
            </a>

            <a
                href="{{ route('admin.trips.index') }}"
                class="bg-white border border-[#D9D5CA] rounded-xl p-4 transition-colors hover:border-[#21483C] shadow-xs group"
            >
                <span class="text-xs uppercase tracking-wider font-bold block text-[#66716C]">Jadwal</span>
                <div class="text-sm font-bold mt-1 text-[#1C2522] group-hover:text-[#21483C]">Kelola Perjalanan</div>
                <p class="text-[11px] mt-0.5 text-[#66716C]">Atur jam berangkat &amp; tarif</p>
            </a>

            <a
                href="{{ route('admin.buses.index') }}"
                class="bg-white border border-[#D9D5CA] rounded-xl p-4 transition-colors hover:border-[#21483C] shadow-xs group"
            >
                <span class="text-xs uppercase tracking-wider font-bold block text-[#66716C]">Armada</span>
                <div class="text-sm font-bold mt-1 text-[#1C2522] group-hover:text-[#21483C]">Kelola Bus</div>
                <p class="text-[11px] mt-0.5 text-[#66716C]">Data kendaraan &amp; nomor kursi</p>
            </a>

            <a
                href="{{ route('admin.routes.index') }}"
                class="bg-white border border-[#D9D5CA] rounded-xl p-4 transition-colors hover:border-[#21483C] shadow-xs group"
            >
                <span class="text-xs uppercase tracking-wider font-bold block text-[#66716C]">Trayek</span>
                <div class="text-sm font-bold mt-1 text-[#1C2522] group-hover:text-[#21483C]">Kelola Rute</div>
                <p class="text-[11px] mt-0.5 text-[#66716C]">Asal, tujuan, &amp; durasi</p>
            </a>
        </div>

        {{-- 1. Pesanan Membutuhkan Perhatian (Pending Orders) --}}
        <section aria-labelledby="pending-orders-heading" class="bg-white border border-[#D9D5CA] rounded-2xl p-6 sm:p-7 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-[#D9D5CA] gap-2">
                <div class="flex items-center gap-2.5">
                    <h2 id="pending-orders-heading" class="text-base font-bold text-[#1C2522]">
                        Pesanan Membutuhkan Perhatian
                    </h2>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full border bg-[#F5F1E8] text-[#A87935] border-[#A87935]/30">
                        {{ $pendingOrders->count() }} Menunggu
                    </span>
                </div>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                    Buka Semua Pesanan Pending &rarr;
                </a>
            </div>

            @if ($pendingOrders->isEmpty())
                <div class="py-6 text-center text-xs text-[#66716C]">
                    Tidak ada pesanan pending saat ini. Seluruh transaksi telah terproses.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b bg-[#F5F1E8] border-[#D9D5CA] text-[#66716C]">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-bold">Kode Pesanan</th>
                                <th scope="col" class="px-4 py-3 font-bold">Nama Pelanggan</th>
                                <th scope="col" class="px-4 py-3 font-bold">Rute &amp; Bus</th>
                                <th scope="col" class="px-4 py-3 font-bold">Waktu Pemesanan</th>
                                <th scope="col" class="px-4 py-3 font-bold">Total Biaya</th>
                                <th scope="col" class="px-4 py-3 text-right font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#D9D5CA]/50">
                            @foreach ($pendingOrders as $order)
                                <tr class="hover:bg-[#F5F1E8]/50 transition-colors">
                                    <td class="px-4 py-3 font-mono font-bold text-[#1C2522]">{{ $order->order_code }}</td>
                                    <td class="px-4 py-3 font-bold text-[#1C2522]">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-[#1C2522]">{{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}</div>
                                        <div class="text-[11px] text-[#66716C]">{{ $order->trip->bus->name }} ({{ $order->trip->bus->code }})</div>
                                    </td>
                                    <td class="px-4 py-3 tabular-nums text-[#66716C]">{{ $order->created_at->translatedFormat('d M Y, H.i') }} WIB</td>
                                    <td class="px-4 py-3 font-bold tabular-nums text-[#1C2522]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a
                                            href="{{ route('admin.orders.show', $order) }}"
                                            class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-lg border bg-[#F5F1E8] text-[#21483C] border-[#D9D5CA] hover:bg-[#D9D5CA] transition-colors"
                                        >
                                            Verifikasi &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- 2. Monitoring Perjalanan: Hari Ini & Mendatang --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Perjalanan Hari Ini --}}
            <div class="bg-white border border-[#D9D5CA] rounded-2xl p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-baseline justify-between pb-3 mb-4 border-b border-[#D9D5CA]">
                        <div>
                            <h2 class="text-sm font-bold text-[#1C2522]">Jadwal Hari Ini</h2>
                            <p class="text-xs text-[#66716C]">{{ today()->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <a href="{{ route('admin.trips.index') }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                            Kelola Trip &rarr;
                        </a>
                    </div>

                    @if ($todayTrips->isEmpty())
                        <p class="py-8 text-center text-xs text-[#66716C]">Tidak ada jadwal keberangkatan untuk hari ini.</p>
                    @else
                        <div class="space-y-2.5">
                            @foreach ($todayTrips as $trip)
                                <div class="p-3 rounded-xl border border-[#D9D5CA] flex items-center justify-between text-xs bg-[#FBFAF6]">
                                    <div>
                                        <div class="font-bold text-sm text-[#1C2522]">
                                            {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                        </div>
                                        <div class="text-[11px] mt-0.5 text-[#66716C]">
                                            {{ $trip->bus->name }} &bull; Jam <strong class="text-[#1C2522]">{{ $trip->departure_at->format('H.i') }} WIB</strong>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-2 py-0.5 text-[11px] font-bold rounded border uppercase bg-[#F5F1E8] text-[#21483C] border-[#D9D5CA]">
                                            {{ $trip->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-4 mt-4 border-t border-[#D9D5CA] text-right">
                    <a href="{{ route('admin.trips.create') }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                        + Buat Jadwal Baru
                    </a>
                </div>
            </div>

            {{-- Perjalanan Mendatang --}}
            <div class="bg-white border border-[#D9D5CA] rounded-2xl p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-baseline justify-between pb-3 mb-4 border-b border-[#D9D5CA]">
                        <div>
                            <h2 class="text-sm font-bold text-[#1C2522]">Keberangkatan Terdekat</h2>
                            <p class="text-xs text-[#66716C]">5 jadwal aktif yang akan datang</p>
                        </div>
                        <a href="{{ route('admin.trips.index') }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                            Semua Jadwal &rarr;
                        </a>
                    </div>

                    @if ($upcomingTrips->isEmpty())
                        <p class="py-8 text-center text-xs text-[#66716C]">Belum ada perjalanan mendatang yang terjadwal.</p>
                    @else
                        <div class="space-y-2.5">
                            @foreach ($upcomingTrips as $trip)
                                <div class="p-3 rounded-xl border border-[#D9D5CA] flex items-center justify-between text-xs bg-[#FBFAF6]">
                                    <div>
                                        <div class="font-bold text-sm text-[#1C2522]">
                                            {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                        </div>
                                        <div class="text-[11px] mt-0.5 text-[#66716C]">
                                            {{ $trip->departure_at->translatedFormat('d M Y, H.i') }} WIB &bull; {{ $trip->bus->name }}
                                        </div>
                                    </div>
                                    <div class="font-mono font-bold text-[#1C2522]">
                                        Rp{{ number_format($trip->price, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-4 mt-4 border-t border-[#D9D5CA] text-right">
                    <a href="{{ route('admin.trips.index') }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                        Lihat Seluruh Daftar &rarr;
                    </a>
                </div>
            </div>

        </div>

        {{-- 3. Transaksi Pesanan Terkini --}}
        <section aria-labelledby="recent-orders-heading" class="bg-white border border-[#D9D5CA] rounded-2xl p-6 sm:p-7 shadow-xs">
            <div class="flex items-baseline justify-between pb-3 mb-4 border-b border-[#D9D5CA]">
                <div>
                    <h2 id="recent-orders-heading" class="text-base font-bold text-[#1C2522]">
                        Transaksi Pesanan Terkini
                    </h2>
                    <p class="text-xs text-[#66716C]">Daftar 5 pesanan terbaru yang masuk ke sistem</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                    Kelola Seluruh Pesanan &rarr;
                </a>
            </div>

            @if ($recentOrders->isEmpty())
                <p class="py-6 text-center text-xs text-[#66716C]">Belum ada transaksi pesanan di sistem.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b bg-[#F5F1E8] border-[#D9D5CA] text-[#66716C]">
                            <tr>
                                <th scope="col" class="px-4 py-2.5 font-bold">Kode Pesanan</th>
                                <th scope="col" class="px-4 py-2.5 font-bold">Pelanggan</th>
                                <th scope="col" class="px-4 py-2.5 font-bold">Rute</th>
                                <th scope="col" class="px-4 py-2.5 font-bold">Jumlah Penumpang</th>
                                <th scope="col" class="px-4 py-2.5 font-bold">Total Biaya</th>
                                <th scope="col" class="px-4 py-2.5 font-bold">Status</th>
                                <th scope="col" class="px-4 py-2.5 text-right font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#D9D5CA]/50">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-[#F5F1E8]/50 transition-colors">
                                    <td class="px-4 py-3 font-mono font-bold text-[#1C2522]">{{ $order->order_code }}</td>
                                    <td class="px-4 py-3 font-bold text-[#1C2522]">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3 text-[#1C2522]">{{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}</td>
                                    <td class="px-4 py-3 tabular-nums text-[#66716C]">{{ $order->order_items_count }} orang</td>
                                    <td class="px-4 py-3 font-bold tabular-nums text-[#1C2522]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        @if ($order->status === 'pending')
                                            <span class="inline-flex text-[11px] font-bold px-2 py-0.5 rounded border bg-[#F5F1E8] text-[#A87935] border-[#A87935]/30">Menunggu</span>
                                        @elseif ($order->status === 'confirmed')
                                            <span class="inline-flex text-[11px] font-bold px-2 py-0.5 rounded border bg-[#F5F1E8] text-[#357A62] border-[#357A62]/30">Dikonfirmasi</span>
                                        @elseif ($order->status === 'completed')
                                            <span class="inline-flex text-[11px] font-bold px-2 py-0.5 rounded border bg-[#F5F1E8] text-[#21483C] border-[#21483C]/30">Selesai</span>
                                        @elseif ($order->status === 'cancelled')
                                            <span class="inline-flex text-[11px] font-bold px-2 py-0.5 rounded border bg-[#F5F1E8] text-[#B94A48] border-[#B94A48]/30">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="font-bold text-[#21483C] hover:underline">
                                            Detail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

    </div>
</div>
@endsection

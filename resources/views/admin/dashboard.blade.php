@extends('layouts.app')

@section('title', 'Dasbor Operasional Admin - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div class="border-b border-slate-200 pb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 block mb-1">Operasional Bus Antarkota</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Dasbor Administrator</h1>
                <p class="mt-1 text-sm text-slate-600">Pusat pemantauan status pesanan, jadwal perjalanan aktif, dan armada PO CAN Travel.</p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-700 bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-200 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Sistem Operasional Aktif
                </span>
            </div>
        </div>

        <!-- 1. Pesanan Membutuhkan Perhatian (Pending Orders) -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-7 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                    <h2 class="text-base font-bold text-slate-900">Pesanan Membutuhkan Perhatian</h2>
                    <span class="text-xs bg-amber-100 text-amber-800 font-semibold px-2 py-0.5 rounded-full">
                        {{ $pendingOrders->count() }} Menunggu Konfirmasi
                    </span>
                </div>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                    Kelola Semua Pesanan Pending &rarr;
                </a>
            </div>

            @if ($pendingOrders->isEmpty())
                <div class="py-4 text-center text-sm text-slate-500">
                    Tidak ada pesanan pending saat ini. Seluruh pesanan telah terproses.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 font-medium border-b border-slate-200">
                            <tr>
                                <th scope="col" class="px-4 py-3">Kode</th>
                                <th scope="col" class="px-4 py-3">Pelanggan</th>
                                <th scope="col" class="px-4 py-3">Rute & Armada</th>
                                <th scope="col" class="px-4 py-3">Waktu Pemesanan</th>
                                <th scope="col" class="px-4 py-3">Total</th>
                                <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            @foreach ($pendingOrders as $order)
                                <tr class="hover:bg-slate-50/75 transition-colors">
                                    <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $order->order_code }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        <div class="font-medium text-slate-900">{{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}</div>
                                        <div class="text-slate-500">{{ $order->trip->bus->name }} ({{ $order->trip->bus->code }})</div>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-slate-500 tabular-nums">{{ $order->created_at->translatedFormat('d M Y, H.i') }} WIB</td>
                                    <td class="px-4 py-3 font-medium tabular-nums">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded border border-blue-200 transition-colors">
                                            Periksa & Konfirmasi
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- 2. Perjalanan Hari Ini & 3. Perjalanan Mendatang -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Perjalanan Hari Ini -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-900">Perjalanan Hari Ini</h2>
                        <span class="text-xs font-medium text-slate-500">{{ today()->translatedFormat('l, d M Y') }}</span>
                    </div>

                    @if ($todayTrips->isEmpty())
                        <p class="py-6 text-center text-sm text-slate-500">Tidak ada jadwal keberangkatan untuk hari ini.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($todayTrips as $trip)
                                <div class="p-3.5 bg-slate-50 rounded-lg border border-slate-100 flex items-center justify-between text-xs">
                                    <div>
                                        <div class="font-semibold text-slate-900 text-sm">
                                            {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                        </div>
                                        <div class="text-slate-500 mt-0.5">
                                            {{ $trip->bus->name }} &bull; Jam <strong class="text-slate-800">{{ $trip->departure_at->format('H.i') }} WIB</strong>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-2 py-0.5 text-xs font-medium rounded border {{ $trip->status === 'scheduled' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ ucfirst($trip->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 text-right">
                    <a href="{{ route('admin.trips.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                        Buka Semua Jadwal Perjalanan &rarr;
                    </a>
                </div>
            </div>

            <!-- Perjalanan Mendatang -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-900">Perjalanan Mendatang</h2>
                        <span class="text-xs font-medium text-slate-500">5 Jadwal Terdekat</span>
                    </div>

                    @if ($upcomingTrips->isEmpty())
                        <p class="py-6 text-center text-sm text-slate-500">Belum ada perjalanan mendatang yang terjadwal.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($upcomingTrips as $trip)
                                <div class="p-3.5 bg-slate-50 rounded-lg border border-slate-100 flex items-center justify-between text-xs">
                                    <div>
                                        <div class="font-semibold text-slate-900 text-sm">
                                            {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                        </div>
                                        <div class="text-slate-500 mt-0.5">
                                            {{ $trip->departure_at->translatedFormat('d M Y, H.i') }} WIB &bull; {{ $trip->bus->name }}
                                        </div>
                                    </div>
                                    <div class="font-mono font-medium text-slate-900">
                                        Rp{{ number_format($trip->price, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 text-right">
                    <a href="{{ route('admin.trips.create') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                        + Tambah Jadwal Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Pesanan Terbaru -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Transaksi Pesanan Terkini</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                    Buka Kelola Seluruh Pesanan &rarr;
                </a>
            </div>

            @if ($recentOrders->isEmpty())
                <p class="py-6 text-center text-sm text-slate-500">Belum ada transaksi pesanan di sistem.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 font-medium border-b border-slate-200">
                            <tr>
                                <th scope="col" class="px-4 py-2.5">Kode Pesanan</th>
                                <th scope="col" class="px-4 py-2.5">Pelanggan</th>
                                <th scope="col" class="px-4 py-2.5">Rute</th>
                                <th scope="col" class="px-4 py-2.5">Jumlah Penumpang</th>
                                <th scope="col" class="px-4 py-2.5">Total</th>
                                <th scope="col" class="px-4 py-2.5">Status</th>
                                <th scope="col" class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-slate-50/75 transition-colors">
                                    <td class="px-4 py-3 font-mono font-bold text-slate-900">{{ $order->order_code }}</td>
                                    <td class="px-4 py-3">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3">{{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}</td>
                                    <td class="px-4 py-3 tabular-nums">{{ $order->order_items_count }} orang</td>
                                    <td class="px-4 py-3 font-medium tabular-nums">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        @if ($order->status === 'pending')
                                            <span class="text-xs font-medium text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Menunggu</span>
                                        @elseif ($order->status === 'confirmed')
                                            <span class="text-xs font-medium text-blue-800 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">Dikonfirmasi</span>
                                        @elseif ($order->status === 'completed')
                                            <span class="text-xs font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Selesai</span>
                                        @elseif ($order->status === 'cancelled')
                                            <span class="text-xs font-medium text-rose-800 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                            Detail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Modul Operasional Grid & Informasi Akun -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 mb-4">Navigasi Modul Master Data</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <a href="{{ route('admin.buses.index') }}" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition-colors">
                        <span class="block text-xs font-semibold text-slate-900">Armada Bus</span>
                        <span class="text-[11px] text-slate-500">Data Bus & Kursi</span>
                    </a>
                    <a href="{{ route('admin.routes.index') }}" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition-colors">
                        <span class="block text-xs font-semibold text-slate-900">Rute Bus</span>
                        <span class="text-[11px] text-slate-500">Asal & Tujuan</span>
                    </a>
                    <a href="{{ route('admin.trips.index') }}" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition-colors">
                        <span class="block text-xs font-semibold text-slate-900">Jadwal Perjalanan</span>
                        <span class="text-[11px] text-slate-500">Tarif & Trip</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition-colors">
                        <span class="block text-xs font-semibold text-slate-900">Pesanan</span>
                        <span class="text-[11px] text-slate-500">Verifikasi Tiket</span>
                    </a>
                </div>
            </div>

            <!-- Akun Admin -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 mb-4">Akun Administrator</h2>
                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-slate-500 block">Nama:</span>
                        <span class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Email:</span>
                        <span class="font-mono text-slate-900">{{ auth()->user()->email }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Role:</span>
                        <span class="font-semibold text-blue-700">Administrator Operasional</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

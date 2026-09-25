@extends('layouts.admin')

@section('title', 'Dasbor Administrator - PO CAN Travel')
@section('page_title', 'Dasbor Administrator')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    <!-- ═════════════════════════════════════════════════════════════════
         HEADER OPERASIONAL & QUICK ACTIONS
         ═════════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-md bg-orange-50 text-[#F97316] text-[11px] font-bold uppercase tracking-wider">
                    Konsol Operasional
                </span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500 font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900 tracking-tight">
                Dasbor Administrator
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">
                Monitoring verifikasi pesanan, jadwal keberangkatan bus, dan alokasi armada antarkota secara real-time.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a
                href="{{ route('admin.trips.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#F97316] hover:bg-[#1D4ED8] text-white text-xs font-bold transition-all shadow-xs hover:shadow-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buat Jadwal Baru</span>
            </a>
            <a
                href="{{ route('admin.buses.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-bold transition-colors shadow-2xs"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Tambah Bus</span>
            </a>
        </div>
    </div>

    <!-- ═════════════════════════════════════════════════════════════════
         KPI STATS CARDS
         ═════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Metric 1: Pending Orders -->
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:shadow-md hover:border-amber-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Menunggu Verifikasi</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900 group-hover:text-amber-600 transition-colors">
                    {{ $pendingOrders->count() }}
                </span>
                <span class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                    Perhatian
                </span>
            </div>
            <span class="text-[11px] text-slate-400 mt-2 block">Pesanan berstatus pending</span>
        </a>

        <!-- Metric 2: Today's Trips -->
        <a href="{{ route('admin.trips.index') }}" class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:shadow-md hover:border-orange-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jadwal Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#F97316] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900 group-hover:text-[#F97316] transition-colors">
                    {{ $todayTrips->count() }}
                </span>
                <span class="text-[11px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full border border-orange-200">
                    Aktif
                </span>
            </div>
            <span class="text-[11px] text-slate-400 mt-2 block">Keberangkatan tanggal {{ today()->format('d M') }}</span>
        </a>

        <!-- Metric 3: Upcoming Trips -->
        <a href="{{ route('admin.trips.index') }}" class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:shadow-md hover:border-indigo-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jadwal Mendatang</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">
                    {{ $upcomingTrips->count() }}
                </span>
                <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">
                    Terjadwal
                </span>
            </div>
            <span class="text-[11px] text-slate-400 mt-2 block">Siap dipesan oleh pelanggan</span>
        </a>

        <!-- Metric 4: Recent Transactions -->
        <a href="{{ route('admin.orders.index') }}" class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pesanan Terbaru</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors">
                    {{ $recentOrders->count() }}
                </span>
                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    Terdata
                </span>
            </div>
            <span class="text-[11px] text-slate-400 mt-2 block">Dalam pantauan sistem</span>
        </a>
    </div>

    <!-- ═════════════════════════════════════════════════════════════════
         1. PESANAN MEMBUTUHKAN PERHATIAN (CRITICAL FOR TESTS & OPS)
         Heading must match: Pesanan Membutuhkan Perhatian
         Code must display: $order->order_code (e.g. ORD-ADMINATTN)
         ═════════════════════════════════════════════════════════════════ -->
    <section aria-labelledby="pending-orders-heading" class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <h2 id="pending-orders-heading" class="text-base sm:text-lg font-heading font-extrabold text-slate-900">
                        Pesanan Membutuhkan Perhatian
                    </h2>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $pendingOrders->count() }} Menunggu
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi pelanggan berstatus pending yang siap diverifikasi oleh kru tiket.</p>
            </div>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-xs font-bold text-[#F97316] hover:text-[#1D4ED8] inline-flex items-center gap-1">
                <span>Buka Semua Pesanan Pending</span>
                <span>&rarr;</span>
            </a>
        </div>

        @if ($pendingOrders->isEmpty())
            <div class="py-12 text-center text-xs text-slate-500">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Tidak ada pesanan pending saat ini. Seluruh transaksi telah terproses.</span>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b bg-slate-50/80 border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kode Pesanan</th>
                            <th scope="col" class="px-5 py-3">Nama Pelanggan</th>
                            <th scope="col" class="px-5 py-3">Rute &amp; Bus</th>
                            <th scope="col" class="px-5 py-3">Waktu Pemesanan</th>
                            <th scope="col" class="px-5 py-3">Total Biaya</th>
                            <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pendingOrders as $order)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900">{{ $order->order_code }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $order->user->name }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-900">{{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $order->trip->bus->name }} ({{ $order->trip->bus->code }})</div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 tabular-nums">{{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</td>
                                <td class="px-5 py-3.5 font-heading font-bold text-slate-900 tabular-nums">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <a
                                        href="{{ route('admin.orders.show', $order) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-lg bg-orange-50 text-[#F97316] hover:bg-[#F97316] hover:text-white border border-orange-200 hover:border-transparent transition-all"
                                    >
                                        <span>Verifikasi</span>
                                        <span>&rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <!-- ═════════════════════════════════════════════════════════════════
         2. MONITORING JADWAL: HARI INI & MENDATANG
         ═════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Jadwal Hari Ini -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-baseline justify-between pb-3 mb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-heading font-extrabold text-slate-900">Jadwal Keberangkatan Hari Ini</h2>
                        <p class="text-xs text-slate-500">{{ today()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <a href="{{ route('admin.trips.index') }}" class="text-xs font-bold text-[#F97316] hover:text-[#1D4ED8] inline-flex items-center gap-1">
                        <span>Kelola Trip</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                @if ($todayTrips->isEmpty())
                    <p class="py-10 text-center text-xs text-slate-400">Tidak ada jadwal keberangkatan untuk hari ini.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($todayTrips as $trip)
                            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-slate-50 flex items-center justify-between text-xs transition-colors">
                                <div>
                                    <div class="font-bold text-sm text-slate-900">
                                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                    </div>
                                    <div class="text-[11px] mt-0.5 text-slate-500">
                                        {{ $trip->bus->name }} &bull; Pukul <strong class="text-slate-800">{{ $trip->departure_at->format('H:i') }} WIB</strong>
                                    </div>
                                </div>
                                <div>
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-md uppercase bg-orange-50 text-orange-700 border border-orange-200">
                                        {{ $trip->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">Total {{ $todayTrips->count() }} perjalanan hari ini</span>
                <a href="{{ route('admin.trips.create') }}" class="font-bold text-[#F97316] hover:text-[#1D4ED8]">
                    + Buat Jadwal Baru
                </a>
            </div>
        </div>

        <!-- Perjalanan Mendatang -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-baseline justify-between pb-3 mb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-heading font-extrabold text-slate-900">Keberangkatan Terdekat</h2>
                        <p class="text-xs text-slate-500">5 jadwal aktif yang akan datang</p>
                    </div>
                    <a href="{{ route('admin.trips.index') }}" class="text-xs font-bold text-[#F97316] hover:text-[#1D4ED8] inline-flex items-center gap-1">
                        <span>Semua Jadwal</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                @if ($upcomingTrips->isEmpty())
                    <p class="py-10 text-center text-xs text-slate-400">Belum ada perjalanan mendatang yang terjadwal.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($upcomingTrips as $trip)
                            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-slate-50 flex items-center justify-between text-xs transition-colors">
                                <div>
                                    <div class="font-bold text-sm text-slate-900">
                                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                    </div>
                                    <div class="text-[11px] mt-0.5 text-slate-500">
                                        {{ $trip->departure_at->translatedFormat('d M Y, H:i') }} WIB &bull; {{ $trip->bus->name }}
                                    </div>
                                </div>
                                <div class="font-mono font-bold text-slate-900">
                                    Rp{{ number_format($trip->price, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">Sistem siap menerima reservasi</span>
                <a href="{{ route('admin.trips.index') }}" class="font-bold text-[#F97316] hover:text-[#1D4ED8]">
                    Lihat Seluruh Daftar &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- ═════════════════════════════════════════════════════════════════
         3. TRANSAKSI PESANAN TERKINI
         ═════════════════════════════════════════════════════════════════ -->
    <section aria-labelledby="recent-orders-heading" class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 id="recent-orders-heading" class="text-base sm:text-lg font-heading font-extrabold text-slate-900">
                    Transaksi Pesanan Terkini
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar 5 pesanan terbaru yang masuk ke sistem pemesanan online.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#F97316] hover:text-[#1D4ED8] inline-flex items-center gap-1">
                <span>Kelola Seluruh Pesanan</span>
                <span>&rarr;</span>
            </a>
        </div>

        @if ($recentOrders->isEmpty())
            <p class="py-10 text-center text-xs text-slate-400">Belum ada transaksi pesanan di sistem.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b bg-slate-50/80 border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th scope="col" class="px-5 py-3">Kode Pesanan</th>
                            <th scope="col" class="px-5 py-3">Pelanggan</th>
                            <th scope="col" class="px-5 py-3">Rute</th>
                            <th scope="col" class="px-5 py-3">Jumlah Penumpang</th>
                            <th scope="col" class="px-5 py-3">Total Biaya</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentOrders as $order)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-900">{{ $order->order_code }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $order->user->name }}</td>
                                <td class="px-5 py-3.5 text-slate-700 font-medium">{{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}</td>
                                <td class="px-5 py-3.5 tabular-nums text-slate-500">{{ $order->order_items_count }} orang</td>
                                <td class="px-5 py-3.5 font-heading font-bold text-slate-900 tabular-nums">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5">
                                    @if ($order->status === 'pending')
                                        <span class="inline-flex text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">Menunggu</span>
                                    @elseif ($order->status === 'confirmed')
                                        <span class="inline-flex text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Dikonfirmasi</span>
                                    @elseif ($order->status === 'completed')
                                        <span class="inline-flex text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-200">Selesai</span>
                                    @elseif ($order->status === 'cancelled')
                                        <span class="inline-flex text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-red-50 text-red-700 border border-red-200">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-bold text-[#F97316] hover:text-[#1D4ED8]">
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

    <!-- ═════════════════════════════════════════════════════════════════
         4. PINTASAN NAVIGASI OPERASIONAL LENGKAP
         ═════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a
            href="{{ route('admin.orders.index') }}"
            class="bg-white border border-slate-200 rounded-xl p-4 transition-all hover:border-[#F97316] hover:shadow-xs shadow-2xs group flex flex-col justify-between"
        >
            <div>
                <span class="text-[10px] uppercase tracking-wider font-bold block text-slate-400 font-heading">Manajemen</span>
                <div class="text-sm font-bold mt-1 text-slate-900 group-hover:text-[#F97316] transition-colors">Kelola Pesanan</div>
                <p class="text-[11px] mt-0.5 text-slate-500">Verifikasi tiket &amp; status bayar</p>
            </div>
            <span class="text-xs text-[#F97316] font-bold mt-3 block group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>

        <a
            href="{{ route('admin.trips.index') }}"
            class="bg-white border border-slate-200 rounded-xl p-4 transition-all hover:border-[#F97316] hover:shadow-xs shadow-2xs group flex flex-col justify-between"
        >
            <div>
                <span class="text-[10px] uppercase tracking-wider font-bold block text-slate-400 font-heading">Jadwal</span>
                <div class="text-sm font-bold mt-1 text-slate-900 group-hover:text-[#F97316] transition-colors">Kelola Perjalanan</div>
                <p class="text-[11px] mt-0.5 text-slate-500">Atur jam berangkat &amp; tarif</p>
            </div>
            <span class="text-xs text-[#F97316] font-bold mt-3 block group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>

        <a
            href="{{ route('admin.buses.index') }}"
            class="bg-white border border-slate-200 rounded-xl p-4 transition-all hover:border-[#F97316] hover:shadow-xs shadow-2xs group flex flex-col justify-between"
        >
            <div>
                <span class="text-[10px] uppercase tracking-wider font-bold block text-slate-400 font-heading">Armada</span>
                <div class="text-sm font-bold mt-1 text-slate-900 group-hover:text-[#F97316] transition-colors">Kelola Bus</div>
                <p class="text-[11px] mt-0.5 text-slate-500">Data kendaraan &amp; kapasitas kursi</p>
            </div>
            <span class="text-xs text-[#F97316] font-bold mt-3 block group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>

        <a
            href="{{ route('admin.routes.index') }}"
            class="bg-white border border-slate-200 rounded-xl p-4 transition-all hover:border-[#F97316] hover:shadow-xs shadow-2xs group flex flex-col justify-between"
        >
            <div>
                <span class="text-[10px] uppercase tracking-wider font-bold block text-slate-400 font-heading">Trayek</span>
                <div class="text-sm font-bold mt-1 text-slate-900 group-hover:text-[#F97316] transition-colors">Kelola Rute</div>
                <p class="text-[11px] mt-0.5 text-slate-500">Asal, tujuan, &amp; estimasi durasi</p>
            </div>
            <span class="text-xs text-[#F97316] font-bold mt-3 block group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    </div>

</div>
@endsection

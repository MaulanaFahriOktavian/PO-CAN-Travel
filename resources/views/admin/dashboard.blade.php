@extends('layouts.admin')

@section('title', 'Dasbor Administrator - PO CAN Travel')
@section('page_title', 'Dasbor Administrator')

@section('content')
<div class="space-y-6 max-w-[1400px] mx-auto" x-data="dashboardApp()">

    {{-- ═══════════════════════════════════════════════════════════════
         HEADER
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-orange-50 text-orange-600 text-[11px] font-bold uppercase tracking-wider border border-orange-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                    Konsol Operasional
                </span>
                <span class="text-xs text-slate-400">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-heading font-extrabold text-slate-900 tracking-tight">
                Selamat Datang, {{ auth()->user()->name }} 👋
            </h1>
            <p class="mt-0.5 text-xs text-slate-500">
                Pantau operasional bus antarkota PO CAN Travel secara real-time.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.trips.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Buat Jadwal Baru
            </a>
            <a href="{{ route('admin.buses.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Bus
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         ROW 1: 4 KPI CARDS + REVENUE HIGHLIGHT
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Pendapatan Bulan Ini --}}
        <div class="col-span-2 p-5 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-500 text-white shadow-lg shadow-orange-200 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full"></div>
            <div class="absolute right-8 bottom-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-orange-100">Pendapatan Bulan Ini</span>
                    <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 font-heading font-extrabold text-2xl sm:text-3xl tabular-nums">
                    Rp{{ number_format($revenueThisMonth, 0, ',', '.') }}
                </div>
                <div class="mt-1 flex items-center gap-2 text-xs">
                    @if($revenueLastMonth > 0)
                        @php $growth = (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100; @endphp
                        <span class="flex items-center gap-0.5 font-bold {{ $growth >= 0 ? 'text-emerald-200' : 'text-red-200' }}">
                            @if($growth >= 0)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                            @else
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            @endif
                            {{ number_format(abs($growth), 1) }}%
                        </span>
                        <span class="text-orange-100">vs bulan lalu</span>
                    @else
                        <span class="text-orange-100">Bulan ini</span>
                    @endif
                </div>
            </div>
            <div class="relative mt-4 pt-3 border-t border-white/20 flex items-center justify-between text-xs">
                <span class="text-orange-100">Total pendapatan keseluruhan</span>
                <span class="font-bold text-white tabular-nums">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Pesanan Pending --}}
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
           class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-amber-300 hover:shadow-md transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Menunggu Verifikasi</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-heading font-extrabold text-slate-900 group-hover:text-amber-600 transition-colors tabular-nums">
                    {{ $pendingOrders->count() }}
                </div>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-[11px] text-slate-400">Pesanan pending</span>
                    @if($pendingOrders->count() > 0)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">Perhatian!</span>
                    @endif
                </div>
            </div>
        </a>

        {{-- Total Pelanggan --}}
        <a href="{{ route('admin.users.index') }}"
           class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-emerald-300 hover:shadow-md transition-all flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Pelanggan</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-heading font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors tabular-nums">
                    {{ number_format($totalCustomers) }}
                </div>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-[11px] text-slate-400">Terdaftar</span>
                    @if($newCustomersMonth > 0)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">+{{ $newCustomersMonth }} bulan ini</span>
                    @endif
                </div>
            </div>
        </a>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         ROW 2: 3 STAT PILLS
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="text-xl font-heading font-extrabold text-slate-900 tabular-nums">{{ $todayTrips->count() }}</div>
                    <div class="text-[11px] text-slate-500">Jadwal Hari Ini</div>
                </div>
            </div>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <div class="text-xl font-heading font-extrabold text-slate-900 tabular-nums">{{ $upcomingTrips->count() }}</div>
                    <div class="text-[11px] text-slate-500">Jadwal Mendatang</div>
                </div>
            </div>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="text-xl font-heading font-extrabold text-slate-900 tabular-nums">{{ $totalBuses }}</div>
                    <div class="text-[11px] text-slate-500">Total Armada Bus</div>
                </div>
            </div>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <div>
                    <div class="text-xl font-heading font-extrabold text-slate-900 tabular-nums">{{ $totalRoutes }}</div>
                    <div class="text-[11px] text-slate-500">Total Rute Trayek</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         ROW 3: CHART + STATUS DONUT
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Bar Chart: Pesanan Bulanan --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-heading font-extrabold text-slate-900">Riwayat Pesanan Bulanan</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pesanan terkonfirmasi & selesai – 12 bulan terakhir</p>
                </div>
                <div class="flex items-center gap-3 text-[11px]">
                    <span class="flex items-center gap-1.5 text-slate-500">
                        <span class="w-3 h-3 rounded-sm bg-orange-500"></span>Pesanan
                    </span>
                </div>
            </div>
            <div class="relative" style="height: 200px;">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        {{-- Donut: Distribusi Status --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col">
            <div class="mb-4">
                <h2 class="text-sm font-heading font-extrabold text-slate-900">Status Pesanan</h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Distribusi keseluruhan</p>
            </div>
            <div class="flex-1 flex items-center justify-center" style="height: 160px;">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="mt-4 space-y-2">
                @php
                    $statusItems = [
                        ['label' => 'Menunggu',    'key' => 'pending',   'color' => 'bg-amber-400'],
                        ['label' => 'Dikonfirmasi','key' => 'confirmed', 'color' => 'bg-emerald-400'],
                        ['label' => 'Selesai',     'key' => 'completed', 'color' => 'bg-orange-500'],
                        ['label' => 'Dibatalkan',  'key' => 'cancelled', 'color' => 'bg-red-400'],
                    ];
                    $totalDist = array_sum($orderStatusDist);
                @endphp
                @foreach($statusItems as $item)
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $item['color'] }}"></span>
                            <span class="text-slate-600">{{ $item['label'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900 tabular-nums">{{ $orderStatusDist[$item['key']] }}</span>
                            @if($totalDist > 0)
                                <span class="text-slate-400 w-8 text-right">{{ number_format(($orderStatusDist[$item['key']] / $totalDist) * 100, 0) }}%</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         ROW 4: PESANAN PENDING + TOP RUTE
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pesanan Membutuhkan Perhatian --}}
        <section aria-labelledby="pending-orders-heading" class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <h2 id="pending-orders-heading" class="text-sm font-heading font-extrabold text-slate-900">Pesanan Membutuhkan Perhatian</h2>
                    @if($pendingOrders->count() > 0)
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                            {{ $pendingOrders->count() }} Menunggu
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                   class="text-[11px] font-bold text-orange-500 hover:text-orange-700 flex items-center gap-1">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($pendingOrders->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center py-12">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs text-slate-500 font-medium">Semua pesanan telah diproses</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Tidak ada pesanan pending saat ini</p>
                </div>
            @else
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b bg-slate-50/60 border-slate-100 text-slate-400 uppercase tracking-wider font-bold text-[10px]">
                            <tr>
                                <th class="px-5 py-3">Kode Pesanan</th>
                                <th class="px-5 py-3">Pelanggan</th>
                                <th class="px-5 py-3">Rute</th>
                                <th class="px-5 py-3">Total</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($pendingOrders as $order)
                                <tr class="hover:bg-orange-50/30 transition-colors">
                                    <td class="px-5 py-3.5 font-mono font-bold text-slate-800 text-[11px]">{{ $order->order_code }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-orange-400 to-amber-400 text-white font-bold text-[10px] flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-slate-800 truncate max-w-[100px]">{{ $order->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-slate-800">{{ $order->trip->route->origin }} → {{ $order->trip->route->destination }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $order->trip->bus->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 font-heading font-bold text-slate-900 tabular-nums">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-[11px] font-bold rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-500 hover:text-white border border-orange-200 hover:border-transparent transition-all">
                                            Verifikasi →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Top Rute --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-heading font-extrabold text-slate-900">Rute Terpopuler</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Berdasarkan jumlah pesanan</p>
                </div>
                <a href="{{ route('admin.routes.index') }}" class="text-[11px] font-bold text-orange-500 hover:text-orange-700">
                    Kelola →
                </a>
            </div>
            <div class="space-y-3 flex-1">
                @forelse($topRoutes as $index => $topRoute)
                    @php
                        $maxCount = $topRoutes->first()->order_count ?? 1;
                        $barWidth = ($topRoute->order_count / $maxCount) * 100;
                        $labels   = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="shrink-0">{{ $labels[$index] ?? ($index+1) }}</span>
                                <span class="font-semibold text-slate-800 truncate">
                                    {{ $topRoute->trip->route->origin ?? '?' }} → {{ $topRoute->trip->route->destination ?? '?' }}
                                </span>
                            </div>
                            <span class="font-bold text-slate-700 tabular-nums shrink-0 ml-2">{{ $topRoute->order_count }}×</span>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-orange-400 to-amber-400 transition-all duration-700"
                                 style="width: {{ $barWidth }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="flex-1 flex items-center justify-center py-8">
                        <p class="text-xs text-slate-400 text-center">Belum ada data rute</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         ROW 5: JADWAL HARI INI + MENDATANG
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Jadwal Hari Ini --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-heading font-extrabold text-slate-900">Jadwal Keberangkatan Hari Ini</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ today()->translatedFormat('l, d F Y') }}</p>
                </div>
                <a href="{{ route('admin.trips.index') }}" class="text-[11px] font-bold text-orange-500 hover:text-orange-700">
                    Kelola →
                </a>
            </div>
            @if($todayTrips->isEmpty())
                <div class="py-10 text-center">
                    <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-xs text-slate-400">Tidak ada jadwal hari ini</p>
                </div>
            @else
                <div class="divide-y divide-slate-50">
                    @foreach($todayTrips as $trip)
                        <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 text-xs font-bold tabular-nums">
                                    {{ $trip->departure_at->format('H:i') }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-900">{{ $trip->route->origin }} → {{ $trip->route->destination }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $trip->bus->name }}</div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase {{ $trip->status === 'scheduled' ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'bg-slate-100 text-slate-500' }}">
                                {{ $trip->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="p-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">{{ $todayTrips->count() }} perjalanan hari ini</span>
                <a href="{{ route('admin.trips.create') }}" class="font-bold text-orange-500 hover:text-orange-700">
                    + Buat Jadwal Baru
                </a>
            </div>
        </div>

        {{-- Keberangkatan Mendatang --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-heading font-extrabold text-slate-900">Keberangkatan Terdekat</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $upcomingTrips->count() }} jadwal aktif mendatang</p>
                </div>
                <a href="{{ route('admin.trips.index') }}" class="text-[11px] font-bold text-orange-500 hover:text-orange-700">
                    Semua Jadwal →
                </a>
            </div>
            @if($upcomingTrips->isEmpty())
                <div class="py-10 text-center">
                    <p class="text-xs text-slate-400">Belum ada perjalanan mendatang</p>
                </div>
            @else
                <div class="divide-y divide-slate-50">
                    @foreach($upcomingTrips as $trip)
                        <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0 text-center">
                                    <div>
                                        <div class="text-[9px] font-bold leading-none">{{ $trip->departure_at->format('M') }}</div>
                                        <div class="text-sm font-extrabold leading-none">{{ $trip->departure_at->format('d') }}</div>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-slate-900">{{ $trip->route->origin }} → {{ $trip->route->destination }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $trip->departure_at->format('H:i') }} WIB · {{ $trip->bus->name }}</div>
                                </div>
                            </div>
                            <div class="text-xs font-heading font-bold text-slate-900 tabular-nums">
                                Rp{{ number_format($trip->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="p-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">Sistem siap menerima reservasi</span>
                <a href="{{ route('admin.trips.index') }}" class="font-bold text-orange-500 hover:text-orange-700">
                    Lihat Semua →
                </a>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         ROW 6: TRANSAKSI TERKINI FULL TABLE
         ═══════════════════════════════════════════════════════════════ --}}
    <section aria-labelledby="recent-orders-heading" class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 id="recent-orders-heading" class="text-sm font-heading font-extrabold text-slate-900">Transaksi Pesanan Terkini</h2>
                <p class="text-[11px] text-slate-400 mt-0.5">8 pesanan terbaru yang masuk ke sistem</p>
            </div>
            <a href="{{ route('admin.orders.index') }}"
               class="text-[11px] font-bold text-orange-500 hover:text-orange-700 flex items-center gap-1">
                Kelola Seluruh Pesanan →
            </a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="py-12 text-center">
                <p class="text-xs text-slate-400">Belum ada transaksi pesanan di sistem.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b bg-slate-50/60 border-slate-100 text-slate-400 uppercase tracking-wider font-bold text-[10px]">
                        <tr>
                            <th class="px-5 py-3">Kode Pesanan</th>
                            <th class="px-5 py-3">Pelanggan</th>
                            <th class="px-5 py-3">Rute</th>
                            <th class="px-5 py-3">Penumpang</th>
                            <th class="px-5 py-3">Total Biaya</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-orange-50/20 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-800 text-[11px]">{{ $order->order_code }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-orange-400 to-amber-400 text-white font-bold text-[10px] flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800 truncate max-w-[110px]">{{ $order->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-medium text-slate-700">
                                    {{ $order->trip->route->origin }} → {{ $order->trip->route->destination }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 tabular-nums">{{ $order->order_items_count }} orang</td>
                                <td class="px-5 py-3.5 font-heading font-bold text-slate-900 tabular-nums">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $statusMap = [
                                            'pending'   => ['label' => 'Menunggu',    'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                            'confirmed' => ['label' => 'Dikonfirmasi','class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                            'completed' => ['label' => 'Selesai',     'class' => 'bg-orange-50 text-orange-700 border-orange-200'],
                                            'cancelled' => ['label' => 'Dibatalkan',  'class' => 'bg-red-50 text-red-700 border-red-200'],
                                        ];
                                        $st = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-slate-100 text-slate-600 border-slate-200'];
                                    @endphp
                                    <span class="inline-flex text-[10px] font-bold px-2.5 py-0.5 rounded-full border {{ $st['class'] }}">
                                        {{ $st['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="font-bold text-orange-500 hover:text-orange-700 text-[11px]">
                                        Detail →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         ROW 7: QUICK ACCESS CARDS
         ═══════════════════════════════════════════════════════════════ --}}
    <div>
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Akses Cepat</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $quickLinks = [
                    ['href' => route('admin.orders.index'),  'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z', 'label' => 'Kelola Pesanan',   'desc' => 'Verifikasi tiket & status', 'cat' => 'Manajemen'],
                    ['href' => route('admin.trips.index'),   'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',                             'label' => 'Jadwal Perjalanan', 'desc' => 'Atur jam & tarif trip',    'cat' => 'Jadwal'],
                    ['href' => route('admin.buses.index'),   'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z',                                                    'label' => 'Armada Bus',        'desc' => 'Data kendaraan & kursi',  'cat' => 'Armada'],
                    ['href' => route('admin.routes.index'),  'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'label' => 'Trayek & Rute', 'desc' => 'Asal, tujuan & durasi', 'cat' => 'Trayek'],
                ];
            @endphp
            @foreach($quickLinks as $link)
                <a href="{{ $link['href'] }}"
                   class="bg-white border border-slate-200 rounded-2xl p-4 transition-all hover:border-orange-300 hover:shadow-md shadow-sm group">
                    <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400 font-heading">{{ $link['cat'] }}</span>
                    <div class="mt-2.5 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 group-hover:bg-orange-500 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-900 group-hover:text-orange-600 transition-colors">{{ $link['label'] }}</div>
                            <div class="text-[10px] text-slate-400">{{ $link['desc'] }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Bar Chart: Monthly Orders ─────────────────────────────────
    const ordersCtx = document.getElementById('ordersChart');
    if (ordersCtx) {
        new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pesanan',
                    data: @json($chartOrders),
                    backgroundColor: function(ctx) {
                        const max = Math.max(...@json($chartOrders), 1);
                        const val = ctx.raw;
                        const alpha = 0.3 + (val / max) * 0.7;
                        return `rgba(249, 115, 22, ${alpha})`;
                    },
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 24,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f8fafc',
                        bodyColor: '#94a3b8',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ` ${ctx.raw} pesanan`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11, family: 'Inter' } }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        border: { display: false, dash: [4, 4] },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            stepSize: 1,
                            callback: v => Number.isInteger(v) ? v : null
                        }
                    }
                }
            }
        });
    }

    // ─── Donut Chart: Status Distribution ──────────────────────────
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'],
                datasets: [{
                    data: [
                        {{ $orderStatusDist['pending'] }},
                        {{ $orderStatusDist['confirmed'] }},
                        {{ $orderStatusDist['completed'] }},
                        {{ $orderStatusDist['cancelled'] }}
                    ],
                    backgroundColor: ['#fbbf24', '#34d399', '#f97316', '#f87171'],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f8fafc',
                        bodyColor: '#94a3b8',
                        padding: 10,
                        cornerRadius: 8,
                    }
                }
            }
        });
    }
});

function dashboardApp() {
    return {};
}
</script>
@endpush
@endsection

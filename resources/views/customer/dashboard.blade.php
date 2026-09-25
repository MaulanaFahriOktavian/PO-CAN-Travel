@extends('layouts.app')

@section('title', 'Dasbor Pelanggan — PO CAN Travel')
@section('meta_description', 'Kelola akun tiket bus PO CAN Travel, pantau pesanan aktif, dan akses riwayat pemesanan perjalanan Anda.')

@section('content')
<div class="py-10 sm:py-14 bg-[#F8FAFC] min-h-[85vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="p-4 rounded-xl border text-xs sm:text-sm font-semibold bg-emerald-50 text-emerald-800 border-emerald-200/80 flex items-center gap-2.5 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Welcome Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200 gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-[11px] font-bold uppercase tracking-wider font-heading border border-orange-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                        <span>Area Pelanggan</span>
                    </span>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs text-slate-500 font-medium">Akun Terverifikasi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight">
                    Selamat datang, {{ auth()->user()->name }}
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Pantau tiket perjalanan aktif dan kelola seluruh transaksi pemesanan bus antarkota Anda.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a
                    href="{{ route('customer.orders.index') }}"
                    class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-800 font-heading font-bold text-xs shadow-2xs hover:shadow-xs transition-all"
                >
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>Riwayat Pesanan</span>
                </a>
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-xs shadow-md shadow-orange-500/20 transition-all active:scale-[0.99]"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Cari Tiket Perjalanan &rarr;</span>
                </a>
            </div>
        </div>

        {{-- Overview Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block font-heading">Tiket Aktif</span>
                    <span class="text-2xl font-heading font-black text-slate-900 mt-1 block">
                        {{ $activeOrders->count() }}
                    </span>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">Menunggu jalan / bayar</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-200/80">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block font-heading">Pesanan Terakhir</span>
                    <span class="text-2xl font-heading font-black text-slate-900 mt-1 block">
                        {{ $recentOrders->count() }}
                    </span>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">Catatan perjalanan Anda</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block font-heading">Status Layanan</span>
                    <span class="text-sm font-heading font-bold text-emerald-600 mt-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Operasional Aktif
                    </span>
                    <span class="text-[11px] text-slate-400 mt-0.5 block">Jadwal resmi terverifikasi</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- 1. Tiket Perjalanan Aktif --}}
        <section aria-labelledby="active-orders-heading" class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 id="active-orders-heading" class="text-lg font-heading font-black text-slate-900 tracking-tight">
                        Pesanan Aktif
                    </h2>
                    <p class="text-xs text-slate-500">Tiket perjalanan yang sedang menunggu konfirmasi atau jadwal keberangkatan</p>
                </div>
                @if ($activeOrders->isNotEmpty())
                    <a href="{{ route('customer.orders.index') }}" class="text-xs font-heading font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                        <span>Semua Pesanan</span>
                        <span>&rarr;</span>
                    </a>
                @endif
            </div>

            @if ($activeOrders->isEmpty())
                <div class="bg-white border border-slate-200/90 rounded-3xl p-8 sm:p-10 text-center shadow-2xs">
                    <div class="max-w-md mx-auto space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 mx-auto flex items-center justify-center mb-2 border border-orange-200/80">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-heading font-bold text-slate-900">Tidak ada tiket perjalanan aktif</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Anda belum memiliki jadwal perjalanan yang sedang menunggu konfirmasi atau keberangkatan. Temukan rute tujuan Anda dan pesan kursi sekarang.
                        </p>
                        <div class="pt-2">
                            <a
                                href="{{ route('customer.trips.index') }}"
                                class="inline-flex items-center gap-1.5 px-6 py-2.5 text-xs font-heading font-bold rounded-full bg-gradient-to-r from-orange-500 to-amber-500 text-white hover:from-orange-600 hover:to-amber-600 transition-all shadow-md shadow-orange-500/20"
                            >
                                <span>Cari Jadwal Perjalanan &rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-3.5">
                    @foreach ($activeOrders as $order)
                        <div class="bg-white border border-slate-200/90 rounded-3xl p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-2xs hover:shadow-md hover:border-orange-200 transition-all">
                            <div class="space-y-2 flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono font-bold text-xs sm:text-sm text-slate-900 bg-slate-100 px-3 py-1 rounded-full border border-slate-200/70">
                                        {{ $order->order_code }}
                                    </span>

                                    @if ($order->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu Pembayaran
                                        </span>
                                    @elseif ($order->status === 'confirmed')
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Dikonfirmasi
                                        </span>
                                    @elseif ($order->status === 'completed')
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </div>

                                <div class="text-base sm:text-lg font-heading font-black text-slate-900 flex items-center gap-2">
                                    <span>{{ $order->trip->route->origin }}</span>
                                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                    <span>{{ $order->trip->route->destination }}</span>
                                </div>

                                <div class="text-xs flex flex-wrap items-center gap-x-3 gap-y-1 text-slate-500 font-medium">
                                    <span class="flex items-center gap-1 text-slate-700 font-semibold">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $order->trip->departure_at->translatedFormat('d M Y') }}, {{ $order->trip->departure_at->format('H:i') }} WIB
                                    </span>
                                    <span>&bull;</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16V9a2 2 0 012-2h12a2 2 0 012 2v7M4 16h16M6 16v2m12-2v2"/>
                                        </svg>
                                        {{ $order->trip->bus->name }}
                                    </span>
                                    <span>&bull;</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $order->orderItems->count() }} Penumpang
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between md:flex-col md:items-end gap-2 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                                <div class="text-left md:text-right">
                                    <span class="text-[11px] block text-slate-400 font-medium">Total Tarif</span>
                                    <div class="font-heading font-black text-base sm:text-lg tabular-nums text-orange-600">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </div>
                                </div>

                                <a
                                    href="{{ route('customer.orders.show', $order) }}"
                                    class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-heading font-bold rounded-full border border-orange-200/80 bg-orange-50 hover:bg-orange-100 text-orange-600 transition-colors shadow-2xs"
                                >
                                    <span>Buka Tiket</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- 2. 5 Pesanan Terakhir (Recent History) --}}
        @if ($recentOrders->isNotEmpty())
            <section aria-labelledby="recent-orders-heading" class="space-y-4 pt-4 border-t border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 id="recent-orders-heading" class="text-base sm:text-lg font-heading font-black text-slate-900 tracking-tight">
                            Pesanan Terbaru
                        </h2>
                        <p class="text-xs text-slate-500">Ringkasan transaksi dan perjalanan terakhir Anda</p>
                    </div>
                    <a href="{{ route('customer.orders.index') }}" class="text-xs font-heading font-bold text-orange-600 hover:text-orange-700">
                        Lihat Seluruh Riwayat &rarr;
                    </a>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-heading font-bold text-[11px]">
                                    <th class="py-3 px-4 sm:px-6">Kode Pesanan</th>
                                    <th class="py-3 px-4">Rute &amp; Armada</th>
                                    <th class="py-3 px-4">Keberangkatan</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Total</th>
                                    <th class="py-3 px-4 sm:px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($recentOrders as $ro)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-3.5 px-4 sm:px-6 font-mono font-bold text-slate-800">
                                            {{ $ro->order_code }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="font-heading font-bold text-slate-900">
                                                {{ $ro->trip->route->origin }} &rarr; {{ $ro->trip->route->destination }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 font-medium">
                                                {{ $ro->trip->bus->name }} ({{ $ro->order_items_count }} kursi)
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-600 font-medium">
                                            {{ $ro->trip->departure_at->translatedFormat('d M Y') }}<br>
                                            <span class="text-slate-400 text-[11px]">{{ $ro->trip->departure_at->format('H:i') }} WIB</span>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if ($ro->status === 'pending')
                                                <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                                    Menunggu Bayar
                                                </span>
                                            @elseif ($ro->status === 'confirmed')
                                                <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Dikonfirmasi
                                                </span>
                                            @elseif ($ro->status === 'completed')
                                                <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                                    Dibatalkan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-heading font-bold text-slate-900 tabular-nums">
                                            Rp{{ number_format($ro->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6 text-right">
                                            <a
                                                href="{{ route('customer.orders.show', $ro) }}"
                                                class="font-heading font-bold text-orange-600 hover:text-orange-700"
                                            >
                                                Detail &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif

    </div>
</div>
@endsection

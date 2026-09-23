@extends('layouts.app')

@section('title', 'Riwayat Pesanan - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-6 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    Riwayat Pesanan
                </h1>
                <p class="mt-1.5 text-sm text-slate-600">
                    Daftar seluruh riwayat pemesanan tiket perjalanan yang telah Anda lakukan.
                </p>
            </div>

            <div>
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors"
                >
                    Pesan Tiket Baru
                </a>
            </div>
        </div>

        <!-- Filter Status & Search Bar -->
        <div class="space-y-4 mb-6">
            <!-- Filter Status Tab Navigation -->
            <div class="overflow-x-auto">
                <nav class="flex space-x-2 border-b border-slate-200 pb-3 text-sm font-medium whitespace-nowrap" aria-label="Filter Status Pesanan">
                    <a
                        href="{{ route('customer.orders.index', array_filter(['search' => request('search')])) }}"
                        class="px-3.5 py-1.5 rounded-lg transition-colors {{ empty($selectedStatus) ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        Semua
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'pending', 'search' => request('search')])) }}"
                        class="px-3.5 py-1.5 rounded-lg transition-colors {{ $selectedStatus === 'pending' ? 'bg-amber-600 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        Menunggu Pembayaran
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'confirmed', 'search' => request('search')])) }}"
                        class="px-3.5 py-1.5 rounded-lg transition-colors {{ $selectedStatus === 'confirmed' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        Dikonfirmasi
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'completed', 'search' => request('search')])) }}"
                        class="px-3.5 py-1.5 rounded-lg transition-colors {{ $selectedStatus === 'completed' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        Selesai
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'cancelled', 'search' => request('search')])) }}"
                        class="px-3.5 py-1.5 rounded-lg transition-colors {{ $selectedStatus === 'cancelled' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        Dibatalkan
                    </a>
                </nav>
            </div>

            <!-- Search Form by Order Code -->
            <form method="GET" action="{{ route('customer.orders.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan kode pesanan (misal: ORD-XXXXXXXX)..."
                        class="w-full pl-9 pr-3.5 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-medium text-sm rounded-lg transition-colors"
                    >
                        Cari
                    </button>
                    @if(request()->filled('search'))
                        <a
                            href="{{ route('customer.orders.index', array_filter(['status' => request('status')])) }}"
                            class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm rounded-lg transition-colors"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Daftar Pesanan / Empty State -->
        @if ($orders->isEmpty())
            <div class="bg-white border border-slate-200 rounded-xl p-10 sm:p-12 text-center">
                <div class="max-w-md mx-auto space-y-3">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-500 mb-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Belum ada pesanan</h3>
                    <p class="text-sm text-slate-600">
                        Pesanan tiket yang Anda buat akan muncul di sini.
                    </p>
                    <div class="pt-3">
                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors"
                        >
                            Cari Perjalanan
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-7 transition-shadow hover:border-slate-300">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-3">
                            <div>
                                <span class="text-xs font-mono font-medium text-slate-500 block mb-0.5">
                                    Dipesan pada {{ $order->created_at->translatedFormat('d M Y, H.i') }} WIB
                                </span>
                                <span class="text-base font-bold font-mono text-slate-900">
                                    {{ $order->order_code }}
                                </span>
                            </div>

                            <div>
                                @if ($order->status === 'pending')
                                    <span class="inline-block text-xs font-medium text-amber-800 bg-amber-50 px-3 py-1 rounded border border-amber-200">
                                        Menunggu Pembayaran
                                    </span>
                                @elseif ($order->status === 'confirmed')
                                    <span class="inline-block text-xs font-medium text-blue-800 bg-blue-50 px-3 py-1 rounded border border-blue-200">
                                        Dikonfirmasi
                                    </span>
                                @elseif ($order->status === 'completed')
                                    <span class="inline-block text-xs font-medium text-emerald-800 bg-emerald-50 px-3 py-1 rounded border border-emerald-200">
                                        Selesai
                                    </span>
                                @elseif ($order->status === 'cancelled')
                                    <span class="inline-block text-xs font-medium text-rose-800 bg-rose-50 px-3 py-1 rounded border border-rose-200">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-block text-xs font-medium text-slate-800 bg-slate-100 px-3 py-1 rounded border border-slate-200 capitalize">
                                        {{ $order->status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-4 text-sm">
                            <div class="md:col-span-2">
                                <span class="text-xs text-slate-500 block mb-0.5">Rute Perjalanan</span>
                                <span class="font-semibold text-slate-900 text-base">
                                    {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                                </span>
                                <span class="text-xs text-slate-500 block mt-1">
                                    {{ $order->trip->bus->name }} <span class="font-mono">({{ $order->trip->bus->code }})</span>
                                </span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-500 block mb-0.5">Keberangkatan</span>
                                <span class="font-medium text-slate-900 block">
                                    {{ $order->trip->departure_at->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-xs text-slate-600 font-mono tabular-nums">
                                    {{ $order->trip->departure_at->format('H.i') }} WIB
                                </span>
                            </div>

                            <div>
                                <span class="text-xs text-slate-500 block mb-0.5">Penumpang</span>
                                <span class="font-medium text-slate-900 block tabular-nums">
                                    {{ $order->order_items_count }} Orang
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <span class="text-xs text-slate-500 block">Total Pesanan</span>
                                <span class="text-lg font-bold font-mono text-slate-900 tabular-nums">
                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div>
                                <a
                                    href="{{ route('customer.orders.show', $order) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-medium rounded-lg transition-colors w-full sm:w-auto"
                                >
                                    Lihat Detail &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

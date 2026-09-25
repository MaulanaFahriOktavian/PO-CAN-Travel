@extends('layouts.app')

@section('title', 'Riwayat Pesanan Tiket Bus — PO CAN Travel')
@section('meta_description', 'Kelola dan pantau seluruh status pemesanan tiket perjalanan bus antarkota Anda di PO CAN Travel.')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] text-[#0F172A] py-8 lg:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200 gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-[11px] font-bold uppercase tracking-wider font-heading border border-orange-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                        <span>Dasbor Pelanggan</span>
                    </span>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs text-slate-500 font-medium">Transaksi Tiket</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight">
                    Riwayat Pesanan
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">
                    Daftar seluruh riwayat pemesanan tiket perjalanan yang telah Anda lakukan secara resmi.
                </p>
            </div>

            <div>
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-xs sm:text-sm rounded-full transition-all shadow-md shadow-orange-500/20"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Pesan Tiket Baru</span>
                </a>
            </div>
        </div>

        <!-- Filter Status & Search Bar -->
        <div class="space-y-4">
            <!-- Filter Status Tab Navigation -->
            <div class="overflow-x-auto pb-1">
                <nav class="flex space-x-2 border-b border-slate-200 pb-3 text-xs sm:text-sm font-semibold whitespace-nowrap" aria-label="Filter Status Pesanan">
                    <a
                        href="{{ route('customer.orders.index', array_filter(['search' => request('search')])) }}"
                        class="px-4 py-2 rounded-full transition-all font-heading {{ empty($selectedStatus) ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        Semua
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'pending', 'search' => request('search')])) }}"
                        class="px-4 py-2 rounded-full transition-all font-heading {{ $selectedStatus === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        Menunggu Pembayaran
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'confirmed', 'search' => request('search')])) }}"
                        class="px-4 py-2 rounded-full transition-all font-heading {{ $selectedStatus === 'confirmed' ? 'bg-sky-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        Dikonfirmasi
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'completed', 'search' => request('search')])) }}"
                        class="px-4 py-2 rounded-full transition-all font-heading {{ $selectedStatus === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
                    >
                        Selesai
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'cancelled', 'search' => request('search')])) }}"
                        class="px-4 py-2 rounded-full transition-all font-heading {{ $selectedStatus === 'cancelled' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}"
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
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kode pesanan (contoh: ORD-XXXXXXXX)..."
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200/90 rounded-full text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 shadow-2xs font-medium"
                    >
                </div>
                <button
                    type="submit"
                    class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-heading font-bold rounded-full transition-colors cursor-pointer shadow-2xs shrink-0"
                >
                    Cari Pesanan
                </button>
                @if(request('search') || request('status'))
                    <a
                        href="{{ route('customer.orders.index') }}"
                        class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 hover:text-slate-900 text-xs sm:text-sm font-heading font-semibold rounded-full transition-colors text-center shadow-2xs shrink-0"
                    >
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- Orders List Content -->
        @if ($orders->isEmpty())
            <div class="bg-white border border-slate-200/90 rounded-2xl p-10 sm:p-14 text-center shadow-2xs">
                <div class="max-w-md mx-auto space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 mx-auto flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-heading font-bold text-[#0F172A]">
                        @if (request('search'))
                            Pesanan dengan kode "{{ request('search') }}" tidak ditemukan
                        @else
                            Belum ada pesanan
                        @endif
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        @if (request('search') || request('status'))
                            Coba periksa kembali kata kunci pencarian Anda atau reset filter status.
                        @else
                            Pesanan tiket yang Anda buat akan muncul di sini.
                        @endif
                    </p>
                    <div class="pt-3">
                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-xs sm:text-sm font-heading font-bold rounded-full transition-all shadow-md shadow-orange-500/20"
                        >
                            <span>Cari Perjalanan &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="bg-white border border-slate-200/90 rounded-3xl p-5 sm:p-6 transition-all hover:shadow-md hover:border-orange-200 shadow-2xs">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-3">
                            <div>
                                <span class="text-[11px] font-mono text-slate-400 block mb-0.5">
                                    Dipesan pada {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                                <span class="text-base sm:text-lg font-bold font-mono text-slate-900">
                                    {{ $order->order_code }}
                                </span>
                            </div>

                            <div>
                                @if ($order->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu Pembayaran
                                    </span>
                                @elseif ($order->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Dikonfirmasi
                                    </span>
                                @elseif ($order->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                                        Selesai
                                    </span>
                                @elseif ($order->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 bg-rose-50 px-3 py-1 rounded-full border border-rose-200">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-block text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-full border border-slate-200 capitalize">
                                        {{ $order->status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-4 text-xs sm:text-sm">
                            <div class="md:col-span-2">
                                <span class="text-[11px] text-slate-400 font-medium block mb-1">Rute Perjalanan</span>
                                <div class="font-heading font-black text-slate-900 text-base flex items-center gap-2">
                                    <span>{{ $order->trip->route->origin }}</span>
                                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                    <span>{{ $order->trip->route->destination }}</span>
                                </div>
                                <span class="text-xs text-slate-500 block mt-1">
                                    {{ $order->trip->bus->name }} <span class="font-mono text-orange-600 font-bold">({{ $order->trip->bus->code }})</span>
                                </span>
                            </div>

                            <div>
                                <span class="text-[11px] text-slate-400 font-medium block mb-1">Keberangkatan</span>
                                <span class="font-heading font-bold text-slate-900 block">
                                    {{ $order->trip->departure_at->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-xs text-slate-500 font-mono tabular-nums">
                                    {{ $order->trip->departure_at->format('H:i') }} WIB
                                </span>
                            </div>

                            <div>
                                <span class="text-[11px] text-slate-400 font-medium block mb-1">Jumlah Tiket</span>
                                <span class="font-heading font-bold text-slate-900 block tabular-nums">
                                    {{ $order->order_items_count }} Kursi Penumpang
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <span class="text-[11px] text-slate-400 font-medium block">Total Pembayaran</span>
                                <span class="text-lg font-heading font-black text-orange-600 tabular-nums">
                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div>
                                <a
                                    href="{{ route('customer.orders.show', $order) }}"
                                    class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-orange-50 hover:bg-orange-100 text-orange-600 text-xs font-heading font-bold rounded-full transition-colors w-full sm:w-auto border border-orange-200/80 shadow-xs"
                                >
                                    <span>Buka Tiket &amp; Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
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

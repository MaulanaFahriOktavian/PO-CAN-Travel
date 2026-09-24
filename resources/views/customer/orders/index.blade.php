@extends('layouts.app')

@section('title', 'Riwayat Pesanan Tiket Bus — PO CAN Travel')
@section('meta_description', 'Kelola dan pantau seluruh status pemesanan tiket perjalanan bus antarkota Anda di PO CAN Travel.')

@section('content')
<div class="min-h-screen bg-[#FBFAF6] text-[#1C2522] py-8 lg:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-[#D9D5CA] gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] uppercase tracking-wider font-extrabold text-[#21483C]">Dasbor Pelanggan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1C2522] tracking-tight mt-0.5">
                    Riwayat Pesanan
                </h1>
                <p class="text-xs sm:text-sm text-[#66716C] mt-1">
                    Daftar seluruh riwayat pemesanan tiket perjalanan yang telah Anda lakukan.
                </p>
            </div>

            <div>
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#21483C] hover:bg-[#2F6252] text-white font-bold text-xs sm:text-sm rounded-xl transition-all"
                >
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Pesan Tiket Baru</span>
                </a>
            </div>
        </div>

        <!-- Filter Status & Search Bar -->
        <div class="space-y-4">
            <!-- Filter Status Tab Navigation -->
            <div class="overflow-x-auto pb-1">
                <nav class="flex space-x-2 border-b border-[#D9D5CA] pb-3 text-xs sm:text-sm font-semibold whitespace-nowrap" aria-label="Filter Status Pesanan">
                    <a
                        href="{{ route('customer.orders.index', array_filter(['search' => request('search')])) }}"
                        class="px-4 py-2 rounded-xl transition-all {{ empty($selectedStatus) ? 'bg-[#21483C] text-white shadow-xs' : 'text-[#66716C] hover:text-[#1C2522] hover:bg-[#F5F1E8]' }}"
                    >
                        Semua
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'pending', 'search' => request('search')])) }}"
                        class="px-4 py-2 rounded-xl transition-all {{ $selectedStatus === 'pending' ? 'bg-[#A87935] text-white shadow-xs' : 'text-[#66716C] hover:text-[#1C2522] hover:bg-[#F5F1E8]' }}"
                    >
                        Menunggu Pembayaran
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'confirmed', 'search' => request('search')])) }}"
                        class="px-4 py-2 rounded-xl transition-all {{ $selectedStatus === 'confirmed' ? 'bg-[#357A62] text-white shadow-xs' : 'text-[#66716C] hover:text-[#1C2522] hover:bg-[#F5F1E8]' }}"
                    >
                        Dikonfirmasi
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'completed', 'search' => request('search')])) }}"
                        class="px-4 py-2 rounded-xl transition-all {{ $selectedStatus === 'completed' ? 'bg-[#21483C] text-white shadow-xs' : 'text-[#66716C] hover:text-[#1C2522] hover:bg-[#F5F1E8]' }}"
                    >
                        Selesai
                    </a>
                    <a
                        href="{{ route('customer.orders.index', array_filter(['status' => 'cancelled', 'search' => request('search')])) }}"
                        class="px-4 py-2 rounded-xl transition-all {{ $selectedStatus === 'cancelled' ? 'bg-[#B94A48] text-white shadow-xs' : 'text-[#66716C] hover:text-[#1C2522] hover:bg-[#F5F1E8]' }}"
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
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#D9D5CA] rounded-xl text-xs sm:text-sm text-[#1C2522] focus:outline-none focus:ring-1 focus:ring-[#21483C] focus:border-[#21483C]"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#66716C]">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-[#1C2522] hover:bg-[#2F6252] text-[#F5F1E8] font-bold text-xs sm:text-sm rounded-xl transition-colors cursor-pointer"
                    >
                        Cari
                    </button>
                    @if(request()->filled('search'))
                        <a
                            href="{{ route('customer.orders.index', array_filter(['status' => request('status')])) }}"
                            class="px-4 py-2.5 bg-white hover:bg-[#F5F1E8] text-[#66716C] text-xs sm:text-sm font-semibold rounded-xl transition-colors border border-[#D9D5CA]"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Daftar Pesanan / Empty State -->
        @if ($orders->isEmpty())
            <div class="bg-white border border-[#D9D5CA] rounded-2xl p-10 sm:p-14 text-center">
                <div class="max-w-md mx-auto space-y-3">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA] mb-1">
                        <span class="material-symbols-outlined text-[28px]">receipt_long</span>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-[#1C2522]">Belum ada pesanan</h3>
                    <p class="text-xs sm:text-sm text-[#66716C]">
                        Pesanan tiket yang Anda buat akan muncul di sini.
                    </p>
                    <div class="pt-3">
                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-[#21483C] hover:bg-[#2F6252] text-white text-xs sm:text-sm font-bold rounded-xl transition-colors"
                        >
                            Cari Perjalanan
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="bg-white border border-[#D9D5CA] rounded-2xl p-5 sm:p-6 transition-all hover:border-[#21483C]">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3.5 border-b border-[#D9D5CA] gap-3">
                            <div>
                                <span class="text-[11px] font-mono font-medium text-[#66716C] block mb-0.5">
                                    Dipesan pada {{ $order->created_at->translatedFormat('d M Y, H.i') }} WIB
                                </span>
                                <span class="text-base font-black font-mono text-[#1C2522]">
                                    {{ $order->order_code }}
                                </span>
                            </div>

                            <div>
                                @if ($order->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[#A87935] bg-[#F5F1E8] px-3 py-1 rounded-full border border-[#A87935]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#A87935] animate-pulse"></span>
                                        Menunggu Pembayaran
                                    </span>
                                @elseif ($order->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[#357A62] bg-[#F5F1E8] px-3 py-1 rounded-full border border-[#357A62]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#357A62]"></span>
                                        Dikonfirmasi
                                    </span>
                                @elseif ($order->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[#21483C] bg-[#F5F1E8] px-3 py-1 rounded-full border border-[#21483C]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#21483C]"></span>
                                        Selesai
                                    </span>
                                @elseif ($order->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[#B94A48] bg-[#F5F1E8] px-3 py-1 rounded-full border border-[#B94A48]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#B94A48]"></span>
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-block text-xs font-bold text-[#1C2522] bg-[#F5F1E8] px-3 py-1 rounded-full border border-[#D9D5CA] capitalize">
                                        {{ $order->status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-4 text-xs sm:text-sm">
                            <div class="md:col-span-2">
                                <span class="text-[11px] text-[#66716C] font-medium block mb-0.5">Rute Perjalanan</span>
                                <span class="font-extrabold text-[#1C2522] text-base">
                                    {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                                </span>
                                <span class="text-xs text-[#66716C] block mt-1">
                                    {{ $order->trip->bus->name }} <span class="font-mono text-[#21483C] font-bold">({{ $order->trip->bus->code }})</span>
                                </span>
                            </div>

                            <div>
                                <span class="text-[11px] text-[#66716C] font-medium block mb-0.5">Keberangkatan</span>
                                <span class="font-bold text-[#1C2522] block">
                                    {{ $order->trip->departure_at->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-xs text-[#66716C] font-mono tabular-nums">
                                    {{ $order->trip->departure_at->format('H.i') }} WIB
                                </span>
                            </div>

                            <div>
                                <span class="text-[11px] text-[#66716C] font-medium block mb-0.5">Penumpang</span>
                                <span class="font-bold text-[#1C2522] block tabular-nums">
                                    {{ $order->order_items_count }} Orang
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#D9D5CA] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <span class="text-[11px] text-[#66716C] font-medium block">Total Pesanan</span>
                                <span class="text-lg font-black font-mono text-[#1C2522] tabular-nums">
                                    Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div>
                                <a
                                    href="{{ route('customer.orders.show', $order) }}"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-[#F5F1E8] hover:bg-[#D9D5CA] text-[#21483C] text-xs font-bold rounded-xl transition-colors w-full sm:w-auto border border-[#D9D5CA]"
                                >
                                    <span>Lihat Detail</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
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

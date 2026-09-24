@extends('layouts.app')

@section('title', 'Dasbor Pelanggan - PO CAN Travel')
@section('meta_description', 'Kelola akun tiket bus PO CAN Travel, pantau pesanan aktif, dan akses riwayat pemesanan perjalanan Anda.')

@section('content')
<div class="py-10 sm:py-14 bg-[#FBFAF6]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="p-4 rounded-xl border text-xs font-bold bg-[#F5F1E8] text-[#21483C] border-[#D9D5CA]">
                {{ session('success') }}
            </div>
        @endif

        {{-- Welcome Header --}}
        <div class="border-b pb-6 flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-4 border-[#D9D5CA]">
            <div>
                <span class="text-xs uppercase tracking-wider font-bold block mb-1 text-[#21483C]">Area Pelanggan</span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-[#1C2522]">
                    Selamat datang, {{ auth()->user()->name }}
                </h1>
                <p class="mt-1 text-sm text-[#66716C]">
                    Pantau tiket perjalanan aktif dan kelola pemesanan bus PO CAN Travel Anda.
                </p>
            </div>

            <div>
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="inline-flex items-center px-5 py-2.5 text-xs font-bold text-white rounded-xl bg-[#21483C] hover:bg-[#2F6252] transition-colors"
                >
                    Cari Tiket Perjalanan &rarr;
                </a>
            </div>
        </div>

        {{-- 1. Tiket Perjalanan Aktif --}}
        <section aria-labelledby="active-orders-heading" class="space-y-4">
            <div class="flex items-baseline justify-between">
                <div>
                    <h2 id="active-orders-heading" class="text-base font-bold text-[#1C2522]">
                        Pesanan Aktif
                    </h2>
                    <p class="text-xs text-[#66716C]">Tiket yang sedang menunggu keberangkatan atau pembayaran</p>
                </div>
                @if ($activeOrders->isNotEmpty())
                    <a href="{{ route('customer.orders.index') }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                        Semua Pesanan &rarr;
                    </a>
                @endif
            </div>

            @if ($activeOrders->isEmpty())
                <div class="bg-white border border-[#D9D5CA] rounded-2xl p-8 text-center">
                    <div class="max-w-md mx-auto space-y-3">
                        <p class="text-sm font-bold text-[#1C2522]">Tidak ada tiket perjalanan aktif</p>
                        <p class="text-xs leading-relaxed text-[#66716C]">
                            Anda belum memiliki jadwal perjalanan yang sedang menunggu konfirmasi atau keberangkatan.
                        </p>
                        <div class="pt-2">
                            <a
                                href="{{ route('customer.trips.index') }}"
                                class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg border border-[#D9D5CA] bg-white text-[#21483C] hover:bg-[#F5F1E8] transition-colors"
                            >
                                Cari Perjalanan &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($activeOrders as $order)
                        <div class="bg-white border border-[#D9D5CA] rounded-xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1.5 flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono font-bold text-sm text-[#1C2522]">{{ $order->order_code }}</span>
                                    @if ($order->status === 'pending')
                                        <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-[#F5F1E8] text-[#A87935] border-[#A87935]/30">
                                            Menunggu Pembayaran
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border bg-[#F5F1E8] text-[#357A62] border-[#357A62]/30">
                                            Dikonfirmasi
                                        </span>
                                    @endif
                                </div>

                                <div class="text-base font-bold text-[#1C2522]">
                                    {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                                </div>

                                <div class="text-xs flex flex-wrap items-center gap-x-3 gap-y-1 text-[#66716C]">
                                    <span>{{ $order->trip->departure_at->translatedFormat('d M Y') }}, {{ $order->trip->departure_at->format('H.i') }} WIB</span>
                                    <span>&bull;</span>
                                    <span>{{ $order->trip->bus->name }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $order->orderItems->count() }} Penumpang</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between md:flex-col md:items-end gap-2 pt-3 md:pt-0 border-t md:border-t-0 border-[#D9D5CA]">
                                <div class="text-right">
                                    <span class="text-[11px] block text-[#66716C]">Total Biaya</span>
                                    <div class="font-mono font-bold text-base tabular-nums text-[#1C2522]">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </div>
                                </div>

                                <a
                                    href="{{ route('customer.orders.show', $order) }}"
                                    class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold rounded-lg border border-[#D9D5CA] text-[#1C2522] bg-white hover:bg-[#F5F1E8] transition-colors"
                                >
                                    Buka Tiket &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- 2. Riwayat Pesanan Terbaru --}}
        <section aria-labelledby="recent-orders-heading" class="space-y-4">
            <div class="flex items-baseline justify-between">
                <div>
                    <h2 id="recent-orders-heading" class="text-base font-bold text-[#1C2522]">
                        Riwayat Transaksi Terakhir
                    </h2>
                    <p class="text-xs text-[#66716C]">Ringkasan 5 transaksi tiket terbaru</p>
                </div>
                <a href="{{ route('customer.orders.index') }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                    Lihat Semua Riwayat &rarr;
                </a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="bg-white border border-[#D9D5CA] rounded-xl p-6 text-center text-xs text-[#66716C]">
                    Belum ada riwayat pesanan yang tercatat.
                </div>
            @else
                <div class="bg-white border border-[#D9D5CA] rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="border-b bg-[#F5F1E8] border-[#D9D5CA] text-[#66716C]">
                                <tr>
                                    <th scope="col" class="px-5 py-3 font-bold">Kode</th>
                                    <th scope="col" class="px-5 py-3 font-bold">Rute Perjalanan</th>
                                    <th scope="col" class="px-5 py-3 font-bold">Keberangkatan</th>
                                    <th scope="col" class="px-5 py-3 font-bold">Total</th>
                                    <th scope="col" class="px-5 py-3 font-bold">Status</th>
                                    <th scope="col" class="px-5 py-3 text-right font-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#D9D5CA]/50">
                                @foreach ($recentOrders as $order)
                                    <tr class="hover:bg-[#F5F1E8]/50 transition-colors">
                                        <td class="px-5 py-3.5 font-mono font-bold text-[#1C2522]">
                                            {{ $order->order_code }}
                                        </td>
                                        <td class="px-5 py-3.5 font-bold text-[#1C2522]">
                                            {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                                        </td>
                                        <td class="px-5 py-3.5 text-[#66716C]">
                                            {{ $order->trip->departure_at->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-5 py-3.5 font-bold tabular-nums text-[#1C2522]">
                                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 py-3.5">
                                            @if ($order->status === 'pending')
                                                <span class="inline-flex text-[11px] font-bold px-2 py-0.5 rounded border bg-[#F5F1E8] text-[#A87935] border-[#A87935]/30">
                                                    Menunggu
                                                </span>
                                            @elseif ($order->status === 'confirmed')
                                                <span class="inline-flex text-[11px] font-bold px-2 py-0.5 rounded border bg-[#F5F1E8] text-[#357A62] border-[#357A62]/30">
                                                    Dikonfirmasi
                                                </span>
                                            @elseif ($order->status === 'completed')
                                                <span class="inline-flex text-[11px] font-bold px-2 py-0.5 rounded border bg-[#F5F1E8] text-[#21483C] border-[#21483C]/30">
                                                    Selesai
                                                </span>
                                            @elseif ($order->status === 'cancelled')
                                                <span class="inline-flex text-[11px] font-bold px-2 py-0.5 rounded border bg-[#F5F1E8] text-[#B94A48] border-[#B94A48]/30">
                                                    Dibatalkan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <a
                                                href="{{ route('customer.orders.show', $order) }}"
                                                class="font-bold text-[#21483C] hover:underline"
                                            >
                                                Rincian &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>

        {{-- 3. Akses Cepat & Profil Ringkas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            <div class="bg-white border border-[#D9D5CA] rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold mb-1 text-[#1C2522]">Pintasan Layanan</h3>
                    <p class="text-xs leading-relaxed text-[#66716C]">
                        Navigasi cepat untuk mengecek jaringan rute, jadwal bus antarkota, atau panduan pemesanan.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 pt-4">
                    <a
                        href="{{ route('customer.trips.index') }}"
                        class="px-3.5 py-2 text-xs font-bold rounded-lg border border-[#D9D5CA] text-[#1C2522] bg-white hover:bg-[#F5F1E8] transition-colors"
                    >
                        Cari Tiket
                    </a>
                    <a
                        href="{{ route('customer.orders.index') }}"
                        class="px-3.5 py-2 text-xs font-bold rounded-lg border border-[#D9D5CA] text-[#1C2522] bg-white hover:bg-[#F5F1E8] transition-colors"
                    >
                        Riwayat Pesanan
                    </a>
                    <a
                        href="{{ route('routes.index') }}"
                        class="px-3.5 py-2 text-xs font-bold rounded-lg border border-[#D9D5CA] text-[#1C2522] bg-white hover:bg-[#F5F1E8] transition-colors"
                    >
                        Daftar Rute
                    </a>
                </div>
            </div>

            <div class="bg-white border border-[#D9D5CA] rounded-2xl p-6">
                <h3 class="text-sm font-bold pb-2 border-b border-[#D9D5CA] mb-3 text-[#1C2522]">
                    Profil Akun
                </h3>
                <dl class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <dt class="text-[#66716C]">Nama Lengkap</dt>
                        <dd class="font-bold mt-0.5 truncate text-[#1C2522]">{{ auth()->user()->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#66716C]">Alamat Email</dt>
                        <dd class="font-mono font-medium mt-0.5 truncate text-[#1C2522]">{{ auth()->user()->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#66716C]">Tipe Akun</dt>
                        <dd class="mt-0.5 capitalize text-[#1C2522]">Pelanggan</dd>
                    </div>
                    <div>
                        <dt class="text-[#66716C]">Status Akun</dt>
                        <dd class="mt-0.5 font-bold text-[#357A62]">Aktif &bull; Terverifikasi</dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>
</div>
@endsection

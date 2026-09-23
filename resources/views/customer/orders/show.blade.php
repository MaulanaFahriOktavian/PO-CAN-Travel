@extends('layouts.app')

@section('title', 'Tiket Pesanan ' . $order->order_code . ' - PO CAN Travel')
@section('meta_description', 'Detail tiket resmi PO CAN Travel kode ' . $order->order_code . '. Rute ' . $order->trip->route->origin . ' ke ' . $order->trip->route->destination . '.')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation (Hidden on Print) -->
        <div class="mb-6 flex items-center justify-between no-print">
            <a
                href="{{ route('customer.orders.index') }}"
                class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
            >
                <span class="mr-1.5">&larr;</span> Kembali ke Riwayat Pesanan
            </a>

            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-lg shadow-sm transition-colors"
                title="Cetak Tiket Digital"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Tiket</span>
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800 no-print">
                {{ session('success') }}
            </div>
        @endif

        <!-- Digital Ticket Card -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <!-- Ticket Top Header -->
            <div class="p-6 sm:p-8 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mb-1">
                        Tiket Resmi PO CAN Travel
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight font-mono text-white">
                        {{ $order->order_code }}
                    </h1>
                    <p class="text-xs text-slate-400 mt-1">
                        Dibuat pada {{ $order->created_at->translatedFormat('d M Y, H.i') }} WIB
                    </p>
                </div>

                <div>
                    @if ($order->status === 'pending')
                        <span class="inline-block text-xs font-semibold text-amber-900 bg-amber-100 px-3.5 py-1.5 rounded-full border border-amber-300">
                            Menunggu Pembayaran
                        </span>
                    @elseif ($order->status === 'confirmed')
                        <span class="inline-block text-xs font-semibold text-blue-900 bg-blue-100 px-3.5 py-1.5 rounded-full border border-blue-300">
                            Dikonfirmasi
                        </span>
                    @elseif ($order->status === 'completed')
                        <span class="inline-block text-xs font-semibold text-emerald-900 bg-emerald-100 px-3.5 py-1.5 rounded-full border border-emerald-300">
                            Selesai
                        </span>
                    @elseif ($order->status === 'cancelled')
                        <span class="inline-block text-xs font-semibold text-rose-900 bg-rose-100 px-3.5 py-1.5 rounded-full border border-rose-300">
                            Dibatalkan
                        </span>
                    @else
                        <span class="inline-block text-xs font-semibold text-slate-900 bg-slate-100 px-3.5 py-1.5 rounded-full border border-slate-300 capitalize">
                            {{ $order->status }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Visual Status Timeline (Real Data) -->
            <div class="p-6 sm:p-8 bg-slate-50 border-b border-slate-200">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-4">
                    Progres Status Pesanan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Step 1: Pesanan Dibuat -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-900 block">Pesanan Dibuat</span>
                            <span class="text-xs text-slate-500 tabular-nums">
                                {{ $order->created_at->translatedFormat('d M Y, H.i') }} WIB
                            </span>
                        </div>
                    </div>

                    <!-- Step 2: Konfirmasi / Pembayaran -->
                    <div class="flex items-start gap-3">
                        @if ($order->status === 'confirmed' || $order->status === 'completed')
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-900 block">Dikonfirmasi</span>
                                <span class="text-xs text-slate-500 tabular-nums">
                                    {{ $order->updated_at->translatedFormat('d M Y, H.i') }} WIB
                                </span>
                            </div>
                        @elseif ($order->status === 'cancelled')
                            <div class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-rose-700 block">Dibatalkan</span>
                                <span class="text-xs text-slate-500 tabular-nums">
                                    {{ $order->updated_at->translatedFormat('d M Y, H.i') }} WIB
                                </span>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-amber-100 border border-amber-300 text-amber-700 flex items-center justify-center shrink-0 font-bold text-xs">
                                2
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-amber-800 block">Menunggu Konfirmasi</span>
                                <span class="text-xs text-slate-500">Dalam antrean verifikasi</span>
                            </div>
                        @endif
                    </div>

                    <!-- Step 3: Perjalanan -->
                    <div class="flex items-start gap-3">
                        @if ($order->status === 'completed')
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-emerald-800 block">Perjalanan Selesai</span>
                                <span class="text-xs text-slate-500">Tiba di tujuan</span>
                            </div>
                        @elseif ($order->status === 'cancelled')
                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center shrink-0 font-bold text-xs">
                                3
                            </div>
                            <div>
                                <span class="text-xs font-medium text-slate-400 block">Keberangkatan</span>
                                <span class="text-xs text-slate-400">Tidak terlaksana</span>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-300 text-slate-600 flex items-center justify-center shrink-0 font-bold text-xs">
                                3
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-800 block">Jadwal Keberangkatan</span>
                                <span class="text-xs text-slate-500 tabular-nums">
                                    {{ $order->trip->departure_at->translatedFormat('d M Y, H.i') }} WIB
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Schedule & Bus Details -->
            <div class="p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                        Rincian Perjalanan
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-slate-500 text-xs">Rute Perjalanan</span>
                            <span class="font-bold text-slate-900 mt-1 block">
                                {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                            </span>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-slate-500 text-xs">Waktu Berangkat</span>
                            <span class="font-bold text-slate-900 mt-1 block">
                                {{ $order->trip->departure_at->translatedFormat('d M Y') }}
                            </span>
                            <span class="text-xs text-slate-500 tabular-nums">{{ $order->trip->departure_at->format('H.i') }} WIB</span>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-slate-500 text-xs">Estimasi Tiba</span>
                            <span class="font-bold text-slate-900 mt-1 block">
                                {{ $order->trip->arrival_at->translatedFormat('d M Y') }}
                            </span>
                            <span class="text-xs text-slate-500 tabular-nums">{{ $order->trip->arrival_at->format('H.i') }} WIB</span>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="block text-slate-500 text-xs">Armada Bus</span>
                            <span class="font-bold text-slate-900 mt-1 block">
                                {{ $order->trip->bus->name }}
                            </span>
                            <span class="text-xs text-slate-500 font-mono">{{ $order->trip->bus->code }}</span>
                        </div>
                    </div>
                </div>

                <!-- Daftar Penumpang & Kursi -->
                <div>
                    <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                        Daftar Penumpang ({{ $order->orderItems->count() }} Orang)
                    </h2>

                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-medium">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-center w-12">No</th>
                                    <th scope="col" class="px-4 py-3">Kursi</th>
                                    <th scope="col" class="px-4 py-3">Nama Lengkap</th>
                                    <th scope="col" class="px-4 py-3">Nomor Identitas</th>
                                    <th scope="col" class="px-4 py-3 text-right">Tarif</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-800">
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-3.5 text-center text-slate-500 text-xs tabular-nums">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3.5 font-mono font-bold text-blue-700">{{ $item->seat->seat_number }}</td>
                                        <td class="px-4 py-3.5 font-medium text-slate-900">{{ $item->passenger_name }}</td>
                                        <td class="px-4 py-3.5 text-slate-600 font-mono text-xs tabular-nums">{{ $item->passenger_identity }}</td>
                                        <td class="px-4 py-3.5 text-right font-medium text-slate-900 tabular-nums">
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Pembayaran -->
                    <div class="pt-5 mt-5 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="text-base font-bold text-slate-900">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-slate-900 font-mono tabular-nums">
                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Petunjuk & Navigasi -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 space-y-4 no-print">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Informasi Penting Penumpang</h3>
                        <p class="mt-1 text-xs sm:text-sm text-slate-600 leading-relaxed">
                            @if ($order->status === 'pending')
                                Pesanan Anda telah tercatat dengan status menunggu pembayaran. Silakan hubungi operasional atau tunggu verifikasi admin untuk konfirmasi tiket Anda.
                            @elseif ($order->status === 'confirmed')
                                Tiket Anda telah dikonfirmasi dan kursi Anda telah diamankan. Harap tiba di terminal minimal 30 menit sebelum jadwal keberangkatan untuk boarding.
                            @elseif ($order->status === 'completed')
                                Perjalanan ini telah selesai dilaksanakan. Terima kasih telah bepergian bersama armada PO CAN Travel.
                            @elseif ($order->status === 'cancelled')
                                Pesanan ini telah dibatalkan dan alokasi kursi telah dilepaskan kembali ke sistem ketersediaan umum.
                            @else
                                Status pesanan saat ini: {{ $order->status }}.
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                        <a
                            href="{{ route('customer.orders.index') }}"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
                        >
                            Lihat Riwayat Pesanan
                        </a>
                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg text-center transition-colors"
                        >
                            Dasbor Pelanggan
                        </a>
                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg text-center transition-colors"
                        >
                            Cari Jadwal Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_code . ' - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-6 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-mono font-medium text-slate-500 uppercase tracking-wider block mb-1">
                    Pesanan Tiket Bus
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight font-mono">
                    {{ $order->order_code }}
                </h1>
            </div>

            <div>
                <span class="inline-block text-xs font-medium text-amber-800 bg-amber-50 px-3 py-1.5 rounded border border-amber-200">
                    Menunggu Pembayaran (Pending)
                </span>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Informasi Jadwal Perjalanan -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                    Rincian Perjalanan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="block text-slate-500 text-xs">Rute Perjalanan</span>
                        <span class="font-medium text-slate-900">
                            {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-slate-500 text-xs">Waktu Berangkat</span>
                        <span class="font-medium text-slate-900">
                            {{ $order->trip->departure_at->translatedFormat('d M Y') }}, {{ $order->trip->departure_at->format('H.i') }} WIB
                        </span>
                    </div>

                    <div>
                        <span class="block text-slate-500 text-xs">Estimasi Tiba</span>
                        <span class="font-medium text-slate-900">
                            {{ $order->trip->arrival_at->translatedFormat('d M Y') }}, {{ $order->trip->arrival_at->format('H.i') }} WIB
                        </span>
                    </div>

                    <div>
                        <span class="block text-slate-500 text-xs">Armada Bus</span>
                        <span class="font-medium text-slate-900">
                            {{ $order->trip->bus->name }} <span class="text-xs text-slate-500 font-mono">({{ $order->trip->bus->code }})</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Daftar Penumpang & Kursi -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                    Daftar Penumpang ({{ $order->orderItems->count() }} Orang)
                </h2>

                <div class="overflow-x-auto">
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
                                    <td class="px-4 py-3.5 text-center text-slate-500 text-xs">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3.5 font-mono font-semibold text-blue-700">{{ $item->seat->seat_number }}</td>
                                    <td class="px-4 py-3.5 font-medium text-slate-900">{{ $item->passenger_name }}</td>
                                    <td class="px-4 py-3.5 text-slate-600 font-mono text-xs">{{ $item->passenger_identity }}</td>
                                    <td class="px-4 py-3.5 text-right font-medium text-slate-900">
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
                    <span class="text-2xl font-bold text-slate-900 font-mono">
                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Petunjuk & Navigasi -->
            <div class="bg-slate-100 border border-slate-200 rounded-xl p-6 sm:p-8 space-y-4">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Status Pembayaran</h3>
                    <p class="mt-1 text-sm text-slate-600">
                        Pesanan Anda telah tercatat dengan status menunggu pembayaran. Fitur pembayaran tiket dan penerbitan tiket final akan tersedia pada tahap berikutnya.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                    <a
                        href="{{ route('customer.dashboard') }}"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
                    >
                        Kembali ke Dasbor Saya
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
@endsection

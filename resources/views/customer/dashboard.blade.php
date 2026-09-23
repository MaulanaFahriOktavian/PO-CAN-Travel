@extends('layouts.app')

@section('title', 'Dasbor Pelanggan - PO CAN Travel')
@section('meta_description', 'Kelola akun tiket bus PO CAN Travel, pantau pesanan aktif, dan akses riwayat pemesanan perjalanan Anda.')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        @if (session('success'))
            <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Welcome Header -->
        <div class="border-b border-slate-200 pb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 block mb-1">Area Pelanggan</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Dasbor Pelanggan</h1>
                <p class="mt-1 text-sm text-slate-600">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Pantau tiket aktif dan kelola pesanan bus Anda.</p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
                >
                    Cari Tiket Baru &rarr;
                </a>
            </div>
        </div>

        <!-- 1. Pesanan Aktif (Pending / Confirmed) -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-900">
                    Pesanan Aktif
                </h2>
                <a
                    href="{{ route('customer.orders.index') }}"
                    class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors"
                >
                    Buka Riwayat Pesanan &rarr;
                </a>
            </div>

            @if ($activeOrders->isEmpty())
                <div class="bg-white border border-slate-200 rounded-xl p-6 text-center text-sm text-slate-500">
                    <p class="font-medium text-slate-700">Tidak ada tiket bus yang sedang aktif atau menunggu pembayaran.</p>
                    <p class="text-xs text-slate-500 mt-1">Gunakan tombol di bawah untuk memesan tiket perjalanan bus antarkota Anda.</p>
                    <div class="mt-4">
                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium text-xs rounded-lg transition-colors"
                        >
                            Cari Perjalanan &rarr;
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($activeOrders as $order)
                        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4 hover:border-slate-300 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-xs font-mono text-slate-500 block">Kode Pesanan</span>
                                    <span class="text-base font-bold font-mono text-slate-900">{{ $order->order_code }}</span>
                                </div>
                                <div>
                                    @if ($order->status === 'pending')
                                        <span class="text-xs font-semibold text-amber-800 bg-amber-50 px-2.5 py-1 rounded border border-amber-200">
                                            Menunggu Pembayaran
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold text-blue-800 bg-blue-50 px-2.5 py-1 rounded border border-blue-200">
                                            Dikonfirmasi
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 text-sm">
                                <div class="font-semibold text-slate-900">
                                    {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    Keberangkatan: {{ $order->trip->departure_at->translatedFormat('d M Y') }}, {{ $order->trip->departure_at->format('H.i') }} WIB
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    Armada: {{ $order->trip->bus->name }} ({{ $order->trip->bus->code }})
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-xs text-slate-400 block">Total</span>
                                    <span class="text-base font-bold font-mono text-slate-900 tabular-nums">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                <a
                                    href="{{ route('customer.orders.show', $order) }}"
                                    class="inline-flex items-center px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition-colors"
                                >
                                    Lihat Tiket &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 2. Pesanan Terbaru -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-900">
                    Riwayat Pesanan Terbaru
                </h2>
                <a
                    href="{{ route('customer.orders.index') }}"
                    class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors"
                >
                    Lihat Semua Riwayat &rarr;
                </a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="bg-white border border-slate-200 rounded-xl p-6 text-center text-sm text-slate-500">
                    Belum ada riwayat pesanan yang tercatat di akun ini.
                </div>
            @else
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-medium">
                                <tr>
                                    <th scope="col" class="px-5 py-3">Kode</th>
                                    <th scope="col" class="px-5 py-3">Rute</th>
                                    <th scope="col" class="px-5 py-3">Keberangkatan</th>
                                    <th scope="col" class="px-5 py-3">Total</th>
                                    <th scope="col" class="px-5 py-3">Status</th>
                                    <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-800">
                                @foreach ($recentOrders as $order)
                                    <tr class="hover:bg-slate-50/75 transition-colors">
                                        <td class="px-5 py-3.5 font-mono font-semibold text-slate-900">
                                            {{ $order->order_code }}
                                        </td>
                                        <td class="px-5 py-3.5">
                                            {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-slate-600">
                                            {{ $order->trip->departure_at->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-5 py-3.5 font-medium tabular-nums">
                                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 py-3.5">
                                            @if ($order->status === 'pending')
                                                <span class="text-xs font-medium text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                                    Menunggu
                                                </span>
                                            @elseif ($order->status === 'confirmed')
                                                <span class="text-xs font-medium text-blue-800 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                                    Dikonfirmasi
                                                </span>
                                            @elseif ($order->status === 'completed')
                                                <span class="text-xs font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                    Selesai
                                                </span>
                                            @elseif ($order->status === 'cancelled')
                                                <span class="text-xs font-medium text-rose-800 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                                    Dibatalkan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <a
                                                href="{{ route('customer.orders.show', $order) }}"
                                                class="text-xs font-medium text-blue-600 hover:text-blue-800"
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
        </div>

        <!-- 3. Shortcut & 4. Informasi Akun -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Shortcut Layanan -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 flex flex-col justify-between shadow-sm">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 mb-2">Akses Layanan Cepat</h3>
                    <p class="text-sm text-slate-600 mb-4 leading-relaxed">
                        Cari jadwal perjalanan bus antarkota atau periksa riwayat lengkap pesanan tiket yang pernah Anda buat.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a
                        href="{{ route('customer.trips.index') }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs rounded-lg transition-colors text-center"
                    >
                        Cari Jadwal Perjalanan
                    </a>
                    <a
                        href="{{ route('customer.orders.index') }}"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-medium text-xs rounded-lg transition-colors text-center"
                    >
                        Riwayat Pesanan
                    </a>
                </div>
            </div>

            <!-- Informasi Akun Pelanggan -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">Informasi Akun</h3>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-slate-500 text-xs mb-0.5">Nama</span>
                        <span class="font-medium text-slate-900">{{ auth()->user()->name }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 text-xs mb-0.5">Email</span>
                        <span class="font-medium font-mono text-slate-900 truncate block">{{ auth()->user()->email }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 text-xs mb-0.5">Tipe Akun</span>
                        <span class="text-slate-800 capitalize">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Pelanggan' }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 text-xs mb-0.5">Status Akun</span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

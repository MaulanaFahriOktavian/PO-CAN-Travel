@extends('layouts.app')

@section('title', 'Kelola Pesanan - Admin PO CAN Travel')

@section('content')
    <div class="py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div
                class="border-b border-slate-200 pb-6 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                        Kelola Pesanan
                    </h1>
                    <p class="mt-1.5 text-sm text-slate-600">
                        Daftar seluruh transaksi pemesanan tiket bus oleh pelanggan.
                    </p>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 mb-6 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex-1 flex gap-2">
                        @if ($selectedStatus)
                            <input type="hidden" name="status" value="{{ $selectedStatus }}">
                        @endif
                        <div class="relative flex-1 max-w-md">
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Cari kode pesanan, nama, atau email..."
                                class="w-full pl-3.5 pr-10 py-2 border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 placeholder-slate-400">
                        </div>
                        <button type="submit"
                            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium text-sm rounded-lg transition-colors">
                            Cari
                        </button>
                        @if ($search || $selectedStatus)
                            <a href="{{ route('admin.orders.index') }}"
                                class="px-3.5 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium text-sm rounded-lg transition-colors text-center">
                                Reset
                            </a>
                        @endif
                    </form>

                    <!-- Filter Status Pills -->
                    <div class="flex flex-wrap gap-1.5 text-xs font-medium">
                        <a href="{{ route('admin.orders.index', array_filter(['search' => $search])) }}"
                            class="px-3 py-1.5 rounded-lg transition-colors {{ empty($selectedStatus) ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                            Semua
                        </a>
                        <a href="{{ route('admin.orders.index', array_filter(['status' => 'pending', 'search' => $search])) }}"
                            class="px-3 py-1.5 rounded-lg transition-colors {{ $selectedStatus === 'pending' ? 'bg-amber-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                            Menunggu Pembayaran
                        </a>
                        <a href="{{ route('admin.orders.index', array_filter(['status' => 'confirmed', 'search' => $search])) }}"
                            class="px-3 py-1.5 rounded-lg transition-colors {{ $selectedStatus === 'confirmed' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                            Dikonfirmasi
                        </a>
                        <a href="{{ route('admin.orders.index', array_filter(['status' => 'completed', 'search' => $search])) }}"
                            class="px-3 py-1.5 rounded-lg transition-colors {{ $selectedStatus === 'completed' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                            Selesai
                        </a>
                        <a href="{{ route('admin.orders.index', array_filter(['status' => 'cancelled', 'search' => $search])) }}"
                            class="px-3 py-1.5 rounded-lg transition-colors {{ $selectedStatus === 'cancelled' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                            Dibatalkan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Table of Orders -->
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-medium">
                            <tr>
                                <th scope="col" class="px-5 py-3.5">Kode Pesanan</th>
                                <th scope="col" class="px-5 py-3.5">Pelanggan</th>
                                <th scope="col" class="px-5 py-3.5">Perjalanan</th>
                                <th scope="col" class="px-5 py-3.5">Keberangkatan</th>
                                <th scope="col" class="px-5 py-3.5 text-center">Kursi</th>
                                <th scope="col" class="px-5 py-3.5 text-right">Total</th>
                                <th scope="col" class="px-5 py-3.5 text-center">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-slate-800">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-slate-50/75 transition-colors">
                                    <td class="px-5 py-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                                        {{ $order->order_code }}
                                        <span class="block text-xs font-normal text-slate-500 mt-0.5">
                                            {{ $order->created_at->translatedFormat('d M Y, H.i') }} WIB
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-slate-900">{{ $order->user->name }}</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $order->user->email }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">
                                            {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $order->trip->bus->name }} <span
                                                class="font-mono">({{ $order->trip->bus->code }})</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <div class="font-medium text-slate-900">
                                            {{ $order->trip->departure_at->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-xs text-slate-500 font-mono">
                                            {{ $order->trip->departure_at->format('H.i') }} WIB
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center font-medium">
                                        {{ $order->order_items_count }}
                                    </td>
                                    <td class="px-5 py-4 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-4 text-center whitespace-nowrap">
                                        @if ($order->status === 'pending')
                                            <span
                                                class="inline-block text-xs font-medium text-amber-800 bg-amber-50 px-2.5 py-1 rounded border border-amber-200">
                                                Menunggu Pembayaran
                                            </span>
                                        @elseif ($order->status === 'confirmed')
                                            <span
                                                class="inline-block text-xs font-medium text-blue-800 bg-blue-50 px-2.5 py-1 rounded border border-blue-200">
                                                Dikonfirmasi
                                            </span>
                                        @elseif ($order->status === 'completed')
                                            <span
                                                class="inline-block text-xs font-medium text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded border border-emerald-200">
                                                Selesai
                                            </span>
                                        @elseif ($order->status === 'cancelled')
                                            <span
                                                class="inline-block text-xs font-medium text-rose-800 bg-rose-50 px-2.5 py-1 rounded border border-rose-200">
                                                Dibatalkan
                                            </span>
                                        @else
                                            <span
                                                class="inline-block text-xs font-medium text-slate-800 bg-slate-100 px-2.5 py-1 rounded border border-slate-200 capitalize">
                                                {{ $order->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-medium rounded-lg transition-colors">
                                            Detail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-12 text-center text-slate-500">
                                        <p class="text-base font-semibold text-slate-700">Tidak ada pesanan ditemukan.</p>
                                        @if ($search || $selectedStatus)
                                            <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian atau filter
                                                status Anda.</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($orders->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
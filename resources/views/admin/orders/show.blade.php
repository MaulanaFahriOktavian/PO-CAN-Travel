@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_code . ' - Admin PO CAN Travel')

@section('content')
    <div class="py-10 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Navigation -->
            <div class="mb-6">
                <a href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                    <span class="mr-1.5">&larr;</span> Kembali ke Kelola Pesanan
                </a>
            </div>

            <!-- Flash Messages & Validation Errors -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-sm text-rose-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Page Header -->
            <div
                class="border-b border-slate-200 pb-6 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="text-xs font-mono font-medium text-slate-500 uppercase tracking-wider block mb-1">
                        Pesanan Tiket &bull; Dibuat pada {{ $order->created_at->translatedFormat('d M Y, H.i') }} WIB
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight font-mono">
                        {{ $order->order_code }}
                    </h1>
                </div>

                <div>
                    @if ($order->status === 'pending')
                        <span
                            class="inline-block text-xs font-medium text-amber-800 bg-amber-50 px-3 py-1.5 rounded border border-amber-200">
                            Menunggu Pembayaran
                        </span>
                    @elseif ($order->status === 'confirmed')
                        <span
                            class="inline-block text-xs font-medium text-blue-800 bg-blue-50 px-3 py-1.5 rounded border border-blue-200">
                            Dikonfirmasi
                        </span>
                    @elseif ($order->status === 'completed')
                        <span
                            class="inline-block text-xs font-medium text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded border border-emerald-200">
                            Selesai
                        </span>
                    @elseif ($order->status === 'cancelled')
                        <span
                            class="inline-block text-xs font-medium text-rose-800 bg-rose-50 px-3 py-1.5 rounded border border-rose-200">
                            Dibatalkan
                        </span>
                    @else
                        <span
                            class="inline-block text-xs font-medium text-slate-800 bg-slate-100 px-3 py-1.5 rounded border border-slate-200 capitalize">
                            {{ $order->status }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <!-- Informasi Pelanggan & Perjalanan (2 Kolom) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Pemesan -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-7">
                        <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                            Data Pelanggan
                        </h2>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Nama Pelanggan</dt>
                                <dd class="font-medium text-slate-900">{{ $order->user->name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Alamat Email</dt>
                                <dd class="font-mono text-slate-900">{{ $order->user->email }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Peran Akun</dt>
                                <dd class="capitalize text-slate-700">{{ $order->user->role }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Data Perjalanan -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-7">
                        <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                            Data Perjalanan
                        </h2>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Rute</dt>
                                <dd class="font-semibold text-slate-900">
                                    {{ $order->trip->route->origin }} &rarr; {{ $order->trip->route->destination }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Armada Bus</dt>
                                <dd class="font-medium text-slate-900">
                                    {{ $order->trip->bus->name }} <span
                                        class="text-xs text-slate-500 font-mono">({{ $order->trip->bus->code }})</span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Keberangkatan</dt>
                                <dd class="font-medium text-slate-900">
                                    {{ $order->trip->departure_at->translatedFormat('d M Y, H.i') }} WIB
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-500">Estimasi Tiba</dt>
                                <dd class="font-medium text-slate-900">
                                    {{ $order->trip->arrival_at->translatedFormat('d M Y, H.i') }} WIB
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Daftar Penumpang & Kursi -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-7">
                    <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                        Daftar Penumpang ({{ $order->orderItems->count() }} Orang)
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-medium">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-center w-12">No</th>
                                    <th scope="col" class="px-4 py-3">Kursi</th>
                                    <th scope="col" class="px-4 py-3">Nama Penumpang</th>
                                    <th scope="col" class="px-4 py-3">Nomor Identitas</th>
                                    <th scope="col" class="px-4 py-3 text-right">Tarif</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-800">
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-3.5 text-center text-slate-500 text-xs">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3.5 font-mono font-semibold text-blue-700">
                                            {{ $item->seat->seat_number }}</td>
                                        <td class="px-4 py-3.5 font-medium text-slate-900">{{ $item->passenger_name }}</td>
                                        <td class="px-4 py-3.5 text-slate-600 font-mono text-xs">{{ $item->passenger_identity }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-medium text-slate-900">
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-5 mt-5 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="text-base font-bold text-slate-900">Total Pembayaran</span>
                        <span class="text-2xl font-bold font-mono text-slate-900">
                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Panel Pengelolaan Status (State Transition) -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-7">
                    <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                        Kelola Status Pesanan
                    </h2>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-500">Status Saat Ini:</span>
                            @if ($order->status === 'pending')
                                <span
                                    class="inline-block text-xs font-medium text-amber-800 bg-amber-50 px-3 py-1 rounded border border-amber-200">
                                    Menunggu Pembayaran (pending)
                                </span>
                            @elseif ($order->status === 'confirmed')
                                <span
                                    class="inline-block text-xs font-medium text-blue-800 bg-blue-50 px-3 py-1 rounded border border-blue-200">
                                    Dikonfirmasi (confirmed)
                                </span>
                            @elseif ($order->status === 'completed')
                                <span
                                    class="inline-block text-xs font-medium text-emerald-800 bg-emerald-50 px-3 py-1 rounded border border-emerald-200">
                                    Selesai (completed)
                                </span>
                            @elseif ($order->status === 'cancelled')
                                <span
                                    class="inline-block text-xs font-medium text-rose-800 bg-rose-50 px-3 py-1 rounded border border-rose-200">
                                    Dibatalkan (cancelled)
                                </span>
                            @endif
                        </div>

                        @if ($order->status === 'pending')
                            <p class="text-xs text-slate-500">
                                Pesanan ini menunggu pembayaran. Anda dapat mengonfirmasi jika pembayaran telah diterima, atau
                                membatalkan pesanan untuk melepaskan kursi.
                            </p>
                            <div class="flex flex-wrap gap-3 pt-2">
                                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">
                                        Konfirmasi Pesanan
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.orders.status', $order) }}"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit"
                                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            </div>
                        @elseif ($order->status === 'confirmed')
                            <p class="text-xs text-slate-500">
                                Pesanan telah dikonfirmasi. Anda dapat menandai pesanan selesai setelah perjalanan usai, atau
                                membatalkan pesanan jika terjadi kendala.
                            </p>
                            <div class="flex flex-wrap gap-3 pt-2">
                                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit"
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">
                                        Selesaikan Pesanan
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.orders.status', $order) }}"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit"
                                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            </div>
                        @elseif ($order->status === 'completed' || $order->status === 'cancelled')
                            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600">
                                Status pesanan ini bersifat final dan tidak dapat diubah lagi.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
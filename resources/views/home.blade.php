@extends('layouts.app')

@section('title', 'PO CAN Travel - Pemesanan Tiket Bus Online')

@section('content')
@php
    $origins = \App\Models\Route::select('origin')->distinct()->orderBy('origin')->pluck('origin');
    $destinations = \App\Models\Route::select('destination')->distinct()->orderBy('destination')->pluck('destination');
    $availableTrips = \App\Models\Trip::with(['bus', 'route'])
        ->where('status', 'scheduled')
        ->orderBy('departure_at', 'asc')
        ->take(4)
        ->get();
@endphp

<!-- Hero & Search Section -->
<section class="bg-white border-b border-slate-200 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">
                Pemesanan Tiket Bus PO CAN Travel
            </h1>
            <p class="mt-3 text-base sm:text-lg text-slate-600 leading-relaxed">
                Temukan jadwal perjalanan bus antarkota, pilih nomor kursi yang tersedia, dan pesan tiket perjalanan Anda.
            </p>
        </div>

        <!-- Integrated Search Form -->
        <div id="cari-tiket" class="bg-slate-50 border border-slate-200 rounded-xl p-5 sm:p-7">
            <form method="GET" action="{{ route('customer.trips.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Kota Asal -->
                    <div>
                        <label for="search_origin" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kota Asal
                        </label>
                        <select
                            name="origin"
                            id="search_origin"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                            <option value="">Semua Kota Asal</option>
                            @foreach ($origins as $origin)
                                <option value="{{ $origin }}">{{ $origin }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kota Tujuan -->
                    <div>
                        <label for="search_destination" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kota Tujuan
                        </label>
                        <select
                            name="destination"
                            id="search_destination"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                            <option value="">Semua Kota Tujuan</option>
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination }}">{{ $destination }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Keberangkatan -->
                    <div>
                        <label for="search_date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tanggal Keberangkatan
                        </label>
                        <input
                            type="date"
                            name="departure_date"
                            id="search_date"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                    </div>
                </div>

                <div class="mt-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 border-t border-slate-200">
                    <p class="text-xs text-slate-500">
                        Pilih kota asal dan tujuan untuk melihat seluruh ketersediaan armada.
                    </p>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                    >
                        Cari Jadwal Perjalanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Available Trip Information Section -->
<section class="py-12 sm:py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Jadwal Perjalanan Tersedia
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Jadwal bus antarkota dengan status aktif yang siap dipesan.
                </p>
            </div>
            <div>
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1"
                >
                    Lihat Semua Jadwal &rarr;
                </a>
            </div>
        </div>

        @if ($availableTrips->isEmpty())
            <div class="bg-white border border-slate-200 rounded-xl p-8 text-center">
                <p class="text-sm text-slate-600">Belum ada jadwal perjalanan aktif saat ini.</p>
                <p class="text-xs text-slate-500 mt-1">Silakan periksa kembali nanti atau hubungi petugas kami.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                @foreach ($availableTrips as $trip)
                    <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 flex flex-col justify-between hover:border-slate-300 transition-colors">
                        <div>
                            <div class="flex items-start justify-between gap-3 pb-3 mb-3 border-b border-slate-100">
                                <div>
                                    <div class="text-base font-semibold text-slate-900">
                                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ $trip->bus->name }} &bull; <span class="font-mono">{{ $trip->bus->code }}</span>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 shrink-0">
                                    Terjadwal
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs text-slate-600 py-1">
                                <div>
                                    <span class="block text-slate-400">Keberangkatan:</span>
                                    <span class="font-medium text-slate-900 text-sm">
                                        {{ $trip->departure_at->format('H.i') }} WIB
                                    </span>
                                    <span class="block text-slate-500">
                                        {{ $trip->departure_at->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-slate-400">Estimasi Tiba:</span>
                                    <span class="font-medium text-slate-900 text-sm">
                                        {{ $trip->arrival_at->format('H.i') }} WIB
                                    </span>
                                    <span class="block text-slate-500">
                                        {{ $trip->arrival_at->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 block">Tarif per Kursi</span>
                                <span class="text-lg font-bold text-slate-900 tabular-nums">
                                    Rp{{ number_format($trip->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <a
                                href="{{ route('customer.trips.show', $trip) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs rounded-lg transition-colors"
                            >
                                Lihat Detail &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

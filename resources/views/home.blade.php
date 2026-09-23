@extends('layouts.app')

@section('title', 'PO CAN Travel - Pemesanan Tiket Bus Online')

@section('content')
<!-- Hero & Search Section -->
<section class="bg-white border-b border-slate-200 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">
                Pesan Tiket Bus Antarkota dengan Mudah
            </h1>
            <p class="mt-3 text-base sm:text-lg text-slate-600 leading-relaxed">
                Temukan jadwal perjalanan bus, pilih nomor kursi Anda sendiri, dan lakukan pemesanan secara transparan.
            </p>
        </div>

        <!-- Integrated Search Form -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 sm:p-7">
            <form method="GET" action="{{ route('customer.trips.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Kota Asal -->
                    <div>
                        <label for="search_origin" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Dari (Kota Asal)
                        </label>
                        <select
                            name="origin"
                            id="search_origin"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                            <option value="">Semua Kota Asal</option>
                            @foreach ($origins as $origin)
                                <option value="{{ $origin }}" {{ request('origin') === $origin ? 'selected' : '' }}>
                                    {{ $origin }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kota Tujuan -->
                    <div>
                        <label for="search_destination" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Ke (Kota Tujuan)
                        </label>
                        <select
                            name="destination"
                            id="search_destination"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                            <option value="">Semua Kota Tujuan</option>
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination }}" {{ request('destination') === $destination ? 'selected' : '' }}>
                                    {{ $destination }}
                                </option>
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
                            value="{{ request('departure_date') }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                    </div>
                </div>

                <div class="mt-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 border-t border-slate-200">
                    <p class="text-xs text-slate-500">
                        Pilih rute dan tanggal keberangkatan untuk melihat ketersediaan kursi bus.
                    </p>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                    >
                        Cari Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Rute yang Tersedia Section -->
<section class="py-12 sm:py-16 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Rute yang Tersedia
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Jalur perjalanan antarkota aktif yang dilayani oleh armada bus PO CAN Travel.
                </p>
            </div>
            <div>
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1"
                >
                    Lihat Seluruh Jadwal &rarr;
                </a>
            </div>
        </div>

        @if ($routes->isEmpty())
            <div class="bg-white border border-slate-200 rounded-xl p-8 text-center text-sm text-slate-500">
                Belum ada rute perjalanan yang aktif saat ini.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-5 mb-10">
                @foreach ($routes as $route)
                    <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 flex flex-col justify-between hover:border-slate-300 transition-colors">
                        <div>
                            <div class="text-base font-semibold text-slate-900 mb-1">
                                {{ $route->origin }} &rarr; {{ $route->destination }}
                            </div>
                            <p class="text-xs text-slate-500">
                                Estimasi durasi: {{ floor($route->duration / 60) }} jam {{ $route->duration % 60 > 0 ? ($route->duration % 60) . ' menit' : '' }}
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">
                                {{ $route->trips_count }} jadwal aktif
                            </span>
                            <a
                                href="{{ route('customer.trips.index', ['origin' => $route->origin, 'destination' => $route->destination]) }}"
                                class="text-blue-600 hover:text-blue-800 font-medium"
                            >
                                Cari Tiket &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Jadwal Perjalanan Terdekat -->
        @if ($availableTrips->isNotEmpty())
            <div class="pt-6 border-t border-slate-200">
                <h3 class="text-base font-bold text-slate-900 mb-4">
                    Jadwal Keberangkatan Terdekat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                    @foreach ($availableTrips as $trip)
                        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between hover:border-slate-300 transition-colors">
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
                                    Pilih Kursi &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Cara Memesan Section -->
<section class="py-12 sm:py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mb-10">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Cara Memesan Tiket
            </h2>
            <p class="mt-1.5 text-sm text-slate-600">
                Empat langkah mudah untuk memesan tiket perjalanan bus antarkota Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <span class="text-3xl font-bold font-mono text-blue-600 block mb-2">01</span>
                <h3 class="text-base font-semibold text-slate-900 mb-1.5">Cari Perjalanan</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Tentukan kota asal, kota tujuan, dan tanggal keberangkatan yang sesuai rencana.
                </p>
            </div>

            <div>
                <span class="text-3xl font-bold font-mono text-blue-600 block mb-2">02</span>
                <h3 class="text-base font-semibold text-slate-900 mb-1.5">Pilih Kursi</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Pilih nomor kursi yang masih tersedia langsung pada denah bus 2+2 interaktif.
                </p>
            </div>

            <div>
                <span class="text-3xl font-bold font-mono text-blue-600 block mb-2">03</span>
                <h3 class="text-base font-semibold text-slate-900 mb-1.5">Isi Data Penumpang</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Lengkapi nama lengkap dan nomor identitas resmi untuk tiap penumpang.
                </p>
            </div>

            <div>
                <span class="text-3xl font-bold font-mono text-blue-600 block mb-2">04</span>
                <h3 class="text-base font-semibold text-slate-900 mb-1.5">Selesaikan Pesanan</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Pesanan Anda tersimpan resmi dengan kode unik dan dapat dipantau kapan saja.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Tentang PO CAN Travel Section -->
<section class="py-12 sm:py-16 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 block mb-2">Platform Pemesanan Resmi</span>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-4">
                Tentang PO CAN Travel
            </h2>
            <p class="text-sm sm:text-base text-slate-600 leading-relaxed mb-4">
                PO CAN Travel menyediakan platform digital untuk membantu masyarakat mencari jadwal perjalanan bus antarkota, memilih nomor kursi yang diinginkan, dan mengelola pesanan tiket secara mandiri tanpa perantara.
            </p>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                Seluruh data perjalanan, tarif tiket, dan status ketersediaan kursi disajikan secara transparan dan diperbarui secara berkala sesuai operasional armada bus kami.
            </p>
            <div>
                <a
                    href="{{ route('about') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1"
                >
                    Baca Selengkapnya Tentang Platform &rarr;
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-12 sm:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 text-white rounded-2xl p-8 sm:p-12 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="max-w-xl">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white mb-2">
                    Siap Memulai Perjalanan Anda?
                </h2>
                <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                    Cari jadwal keberangkatan bus dan amankan kursi perjalanan Anda sekarang juga.
                </p>
            </div>
            <div class="shrink-0">
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors text-center"
                >
                    Cari Jadwal Perjalanan &rarr;
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

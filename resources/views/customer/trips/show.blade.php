@extends('layouts.app')

@section('title', 'Detail Perjalanan ' . $trip->route->origin . ' ke ' . $trip->route->destination . ' - PO CAN Travel')
@section('meta_description', 'Detail jadwal bus ' . $trip->route->origin . ' menuju ' . $trip->route->destination . ' tanggal ' . $trip->departure_at->translatedFormat('d F Y') . '. Cek ketersediaan kursi dan pesan langsung.')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a
                href="{{ route('customer.trips.index') }}"
                class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
            >
                <span class="mr-1.5">&larr;</span> Kembali ke Daftar Perjalanan
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <!-- Header Section -->
            <div class="p-6 sm:p-8 border-b border-slate-200 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-semibold text-slate-500">Rute Perjalanan Resmi</span>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-1">
                            {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                        </h1>
                        <p class="mt-1 text-sm text-slate-600">
                            {{ $trip->departure_at->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    <div>
                        @if(($availableSeatsCount ?? 1) > 0)
                            <span class="inline-flex items-center text-xs font-semibold text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                                {{ $availableSeatsCount ?? 0 }} Kursi Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center text-xs font-semibold text-rose-800 bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-2"></span>
                                Kursi Habis
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Schedule & Details Grid -->
            <div class="p-6 sm:p-8 space-y-8">
                <!-- Timeline Keberangkatan & Kedatangan -->
                <div>
                    <h2 class="text-base font-semibold text-slate-900 pb-2 border-b border-slate-100 mb-6">
                        Informasi Perjalanan
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <!-- Keberangkatan -->
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                <span class="font-semibold uppercase tracking-wider">Keberangkatan</span>
                                <span class="text-blue-600 font-medium">Titik Awal</span>
                            </div>
                            <div class="text-2xl font-bold text-slate-900 tabular-nums">
                                {{ $trip->departure_at->format('H.i') }} <span class="text-sm font-normal text-slate-500">WIB</span>
                            </div>
                            <div class="font-semibold text-slate-800 mt-1.5">
                                Terminal {{ $trip->route->origin }}
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                {{ $trip->departure_at->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        <!-- Kedatangan -->
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                <span class="font-semibold uppercase tracking-wider">Estimasi Tiba</span>
                                <span class="text-emerald-600 font-medium">Titik Akhir</span>
                            </div>
                            <div class="text-2xl font-bold text-slate-900 tabular-nums">
                                {{ $trip->arrival_at->format('H.i') }} <span class="text-sm font-normal text-slate-500">WIB</span>
                            </div>
                            <div class="font-semibold text-slate-800 mt-1.5">
                                Terminal {{ $trip->route->destination }}
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                {{ $trip->arrival_at->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Spesifikasi Armada & Rute -->
                <div>
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-4">
                        Informasi Bus & Fasilitas
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                        <div class="p-3.5 rounded-lg border border-slate-100 bg-white">
                            <span class="text-xs text-slate-500 block">Armada Bus</span>
                            <span class="font-semibold text-slate-900 mt-0.5 block">{{ $trip->bus->name }}</span>
                        </div>
                        <div class="p-3.5 rounded-lg border border-slate-100 bg-white">
                            <span class="text-xs text-slate-500 block">Kode Bus</span>
                            <span class="font-semibold font-mono text-slate-900 mt-0.5 block">{{ $trip->bus->code }}</span>
                        </div>
                        <div class="p-3.5 rounded-lg border border-slate-100 bg-white">
                            <span class="text-xs text-slate-500 block">Kapasitas Kursi</span>
                            <span class="font-semibold text-slate-900 mt-0.5 block">{{ $trip->bus->total_seats }} Kursi</span>
                        </div>
                        <div class="p-3.5 rounded-lg border border-slate-100 bg-white">
                            <span class="text-xs text-slate-500 block">Durasi Perjalanan</span>
                            <span class="font-semibold text-slate-900 mt-0.5 block">
                                @php
                                    $hours = floor($trip->route->duration / 60);
                                    $minutes = $trip->route->duration % 60;
                                @endphp
                                {{ $hours }} jam {{ $minutes > 0 ? $minutes . ' menit' : '' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Petunjuk Pemesanan -->
                <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5 text-sm text-slate-700">
                    <h3 class="font-semibold text-slate-900 mb-1">Ketentuan Pemesanan</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pemesanan kursi dibuka hingga jadwal keberangkatan. Setiap penumpang berhak memilih kursi secara langsung pada denah bus. Harap tiba di terminal keberangkatan minimal 30 menit sebelum jam keberangkatan.
                    </p>
                </div>

                <!-- Pricing & Action Panel -->
                <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-slate-500 block">Tarif Resmi per Penumpang</span>
                        <div class="text-3xl font-bold text-slate-900 tabular-nums">
                            Rp{{ number_format($trip->price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="px-4 py-2.5 text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition-colors text-center"
                        >
                            Cari Jadwal Lain
                        </a>

                        @if(($availableSeatsCount ?? 1) > 0)
                            <a
                                href="{{ route('customer.trips.seats', $trip) }}"
                                class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
                            >
                                Lanjut Pilih Kursi &rarr;
                            </a>
                        @else
                            <button
                                disabled
                                class="px-6 py-2.5 bg-slate-200 text-slate-500 font-medium text-sm rounded-lg cursor-not-allowed text-center"
                            >
                                Kursi Penuh
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

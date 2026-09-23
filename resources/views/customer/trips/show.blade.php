@extends('layouts.app')

@section('title', 'Detail Perjalanan ' . $trip->route->origin . ' ke ' . $trip->route->destination . ' - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
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
            <div class="p-6 sm:p-8 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-semibold text-slate-400">Rute Perjalanan</span>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-0.5">
                            {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                        </h1>
                        <p class="mt-1 text-sm text-slate-600">
                            {{ $trip->departure_at->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    <div>
                        <span class="inline-block text-xs font-medium text-emerald-800 bg-emerald-50 px-3 py-1 rounded border border-emerald-200">
                            Terjadwal
                        </span>
                    </div>
                </div>
            </div>

            <!-- Schedule & Details Grid -->
            <div class="p-6 sm:p-8 space-y-6">
                <h2 class="text-base font-semibold text-slate-900 pb-2 border-b border-slate-100">
                    Informasi Perjalanan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <!-- Keberangkatan -->
                    <div class="bg-slate-50 border border-slate-100 rounded-lg p-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-1">Keberangkatan</span>
                        <div class="text-xl font-bold text-slate-900 tabular-nums">
                            {{ $trip->departure_at->format('H.i') }} WIB
                        </div>
                        <div class="font-medium text-slate-800 mt-1">
                            {{ $trip->route->origin }}
                        </div>
                        <div class="text-xs text-slate-500 mt-0.5">
                            {{ $trip->departure_at->translatedFormat('d F Y') }}
                        </div>
                    </div>

                    <!-- Kedatangan -->
                    <div class="bg-slate-50 border border-slate-100 rounded-lg p-4">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-1">Estimasi Tiba</span>
                        <div class="text-xl font-bold text-slate-900 tabular-nums">
                            {{ $trip->arrival_at->format('H.i') }} WIB
                        </div>
                        <div class="font-medium text-slate-800 mt-1">
                            {{ $trip->route->destination }}
                        </div>
                        <div class="text-xs text-slate-500 mt-0.5">
                            {{ $trip->arrival_at->translatedFormat('d F Y') }}
                        </div>
                    </div>
                </div>

                <!-- Detail Tambahan -->
                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 text-sm border-t border-slate-100">
                    <div>
                        <dt class="text-xs text-slate-500">Armada Bus</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">{{ $trip->bus->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Kode Bus</dt>
                        <dd class="mt-0.5 font-medium font-mono text-slate-900">{{ $trip->bus->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500">Estimasi Durasi</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">
                            @php
                                $hours = floor($trip->route->duration / 60);
                                $minutes = $trip->route->duration % 60;
                            @endphp
                            {{ $hours }} jam {{ $minutes > 0 ? $minutes . ' menit' : '' }}
                        </dd>
                    </div>
                </dl>

                <!-- Pricing & Action Panel -->
                <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-slate-500 block">Tarif per Kursi</span>
                        <div class="text-2xl font-bold text-slate-900 tabular-nums">
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
                        <a
                            href="{{ route('customer.trips.seats', $trip) }}"
                            class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
                        >
                            Lanjut Pilih Kursi &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Perjalanan - PO CAN Travel')

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

        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Detail jadwal perjalanan bus antarkota.
                    </p>
                </div>
                <div>
                    <span class="inline-block text-xs font-medium text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded border border-emerald-200">
                        Terjadwal
                    </span>
                </div>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 space-y-6 mb-8">
            <h2 class="text-lg font-semibold text-slate-900 pb-3 border-b border-slate-100">
                Informasi Perjalanan
            </h2>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 text-sm">
                <!-- Rute -->
                <div>
                    <dt class="text-slate-500">Kota Asal</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $trip->route->origin }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Kota Tujuan</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $trip->route->destination }}</dd>
                </div>

                <!-- Armada Bus -->
                <div>
                    <dt class="text-slate-500">Armada Bus</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $trip->bus->name }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Kode Bus</dt>
                    <dd class="mt-0.5 font-medium text-slate-900 font-mono">{{ $trip->bus->code }}</dd>
                </div>

                <!-- Waktu Keberangkatan & Kedatangan -->
                <div>
                    <dt class="text-slate-500">Waktu Berangkat</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">
                        {{ $trip->departure_at->translatedFormat('l, d F Y') }}
                        <span class="block text-slate-600 text-xs mt-0.5">{{ $trip->departure_at->format('H.i') }} WIB</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-slate-500">Estimasi Tiba</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">
                        {{ $trip->arrival_at->translatedFormat('l, d F Y') }}
                        <span class="block text-slate-600 text-xs mt-0.5">{{ $trip->arrival_at->format('H.i') }} WIB</span>
                    </dd>
                </div>

                <!-- Durasi & Tarif -->
                <div>
                    <dt class="text-slate-500">Estimasi Durasi Perjalanan</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">
                        @php
                            $hours = floor($trip->route->duration / 60);
                            $minutes = $trip->route->duration % 60;
                        @endphp
                        {{ $hours }} jam {{ $minutes > 0 ? $minutes . ' menit' : '' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-slate-500">Tarif per Kursi</dt>
                    <dd class="mt-0.5 text-lg font-bold text-slate-900">
                        Rp{{ number_format($trip->price, 0, ',', '.') }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Next Step Notice & Action -->
        <div class="bg-slate-100 border border-slate-200 rounded-xl p-6 sm:p-8 space-y-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Tahap Pemesanan Berikutnya</h3>
                <p class="mt-1 text-sm text-slate-600">
                    Pemilihan nomor kursi dan konfirmasi pemesanan tiket akan dilakukan pada tahap berikutnya.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                <a
                    href="{{ route('customer.trips.seats', $trip) }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
                >
                    Lanjut Pilih Kursi
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
@endsection

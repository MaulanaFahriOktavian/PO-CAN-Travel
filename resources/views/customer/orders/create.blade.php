@extends('layouts.app')

@section('title', 'Data Penumpang - ' . $trip->route->origin . ' ke ' . $trip->route->destination . ' - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a
                href="{{ route('customer.trips.seats', $trip) }}"
                class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
            >
                <span class="mr-1.5">&larr;</span> Kembali ke Pilih Kursi
            </a>
        </div>

        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-6 mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                Pengisian Data Penumpang
            </h1>
            <p class="mt-1.5 text-sm text-slate-600">
                Silakan lengkapi nama dan identitas resmi untuk setiap penumpang pada kursi yang telah Anda pilih.
            </p>
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

        <!-- Ringkasan Jadwal Perjalanan -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 mb-8">
            <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                Informasi Perjalanan
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="block text-slate-500 text-xs">Rute Perjalanan</span>
                    <span class="font-medium text-slate-900">
                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                    </span>
                </div>

                <div>
                    <span class="block text-slate-500 text-xs">Waktu Keberangkatan</span>
                    <span class="font-medium text-slate-900">
                        {{ $trip->departure_at->translatedFormat('d M Y') }}, {{ $trip->departure_at->format('H.i') }} WIB
                    </span>
                </div>

                <div>
                    <span class="block text-slate-500 text-xs">Estimasi Tiba</span>
                    <span class="font-medium text-slate-900">
                        {{ $trip->arrival_at->translatedFormat('d M Y') }}, {{ $trip->arrival_at->format('H.i') }} WIB
                    </span>
                </div>

                <div>
                    <span class="block text-slate-500 text-xs">Armada Bus</span>
                    <span class="font-medium text-slate-900">
                        {{ $trip->bus->name }} <span class="text-xs text-slate-500 font-mono">({{ $trip->bus->code }})</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulir Data Penumpang -->
        <form method="POST" action="{{ route('customer.trips.booking.store', $trip) }}" class="space-y-6">
            @csrf

            <div class="space-y-6">
                @foreach ($seats as $seat)
                    <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                        <div class="flex items-center justify-between pb-3 mb-5 border-b border-slate-100">
                            <h3 class="text-base font-semibold text-slate-900">
                                Data Penumpang &mdash; Kursi {{ $seat->seat_number }}
                            </h3>
                            <span class="inline-block text-xs font-mono font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded">
                                Kursi {{ $seat->seat_number }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label
                                    for="passenger_name_{{ $seat->id }}"
                                    class="block text-sm font-medium text-slate-700 mb-1.5"
                                >
                                    Nama Lengkap Penumpang <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="passenger_name_{{ $seat->id }}"
                                    name="passengers[{{ $seat->id }}][name]"
                                    value="{{ old("passengers.{$seat->id}.name") }}"
                                    required
                                    maxlength="100"
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full px-3.5 py-2.5 bg-white border {{ $errors->has("passengers.{$seat->id}.name") ? 'border-rose-300' : 'border-slate-300' }} rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                                >
                                @if ($errors->has("passengers.{$seat->id}.name"))
                                    <p class="mt-1 text-xs text-rose-600">{{ $errors->first("passengers.{$seat->id}.name") }}</p>
                                @endif
                            </div>

                            <div>
                                <label
                                    for="passenger_identity_{{ $seat->id }}"
                                    class="block text-sm font-medium text-slate-700 mb-1.5"
                                >
                                    Nomor Identitas (KTP / SIM / Paspor) <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="passenger_identity_{{ $seat->id }}"
                                    name="passengers[{{ $seat->id }}][identity]"
                                    value="{{ old("passengers.{$seat->id}.identity") }}"
                                    required
                                    maxlength="50"
                                    placeholder="Contoh: 3301234567890001"
                                    class="w-full px-3.5 py-2.5 bg-white border {{ $errors->has("passengers.{$seat->id}.identity") ? 'border-rose-300' : 'border-slate-300' }} rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                                >
                                @if ($errors->has("passengers.{$seat->id}.identity"))
                                    <p class="mt-1 text-xs text-rose-600">{{ $errors->first("passengers.{$seat->id}.identity") }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Panel Rincian Pembayaran & Submit -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 space-y-5">
                <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100">
                    Rincian Pembayaran
                </h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Nomor Kursi</dt>
                        <dd class="font-medium text-slate-900 font-mono">{{ $seats->pluck('seat_number')->implode(', ') }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-slate-500">Jumlah Kursi</dt>
                        <dd class="font-medium text-slate-900">{{ $seats->count() }} kursi</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-slate-500">Tarif per Kursi</dt>
                        <dd class="font-medium text-slate-900 tabular-nums">Rp{{ number_format($trip->price, 0, ',', '.') }}</dd>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <dt class="text-base font-bold text-slate-900">Total Pesanan</dt>
                        <dd class="text-xl font-bold text-slate-900 tabular-nums">
                            Rp{{ number_format($seats->count() * $trip->price, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>

                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
                    <a
                        href="{{ route('customer.trips.seats', $trip) }}"
                        class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg text-center transition-colors"
                    >
                        Ubah Pilihan Kursi
                    </a>

                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
                    >
                        Konfirmasi & Buat Pesanan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

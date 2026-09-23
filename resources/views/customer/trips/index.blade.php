@extends('layouts.app')

@section('title', 'Cari Perjalanan - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-6 mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Cari Jadwal Perjalanan</h1>
            <p class="mt-1.5 text-sm text-slate-600">Temukan jadwal keberangkatan bus antarkota sesuai tanggal dan rute pilihan Anda.</p>
        </div>

        <!-- Form Pencarian -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 mb-8">
            <form method="GET" action="{{ route('customer.trips.index') }}" class="space-y-4">
                @if ($errors->any())
                    <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-sm text-rose-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Kota Asal -->
                    <div>
                        <label for="origin" class="block text-sm font-medium text-slate-700 mb-1.5">Kota Asal</label>
                        <select
                            name="origin"
                            id="origin"
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
                        <label for="destination" class="block text-sm font-medium text-slate-700 mb-1.5">Kota Tujuan</label>
                        <select
                            name="destination"
                            id="destination"
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
                        <label for="departure_date" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Keberangkatan</label>
                        <input
                            type="date"
                            name="departure_date"
                            id="departure_date"
                            value="{{ request('departure_date') }}"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors"
                    >
                        Cari Perjalanan
                    </button>
                    @if (request()->hasAny(['origin', 'destination', 'departure_date']))
                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg transition-colors"
                        >
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Hasil Pencarian -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-900">
                    Jadwal Perjalanan
                    <span class="text-sm font-normal text-slate-500">({{ $trips->count() }} perjalanan ditemukan)</span>
                </h2>
            </div>

            @if ($trips->isEmpty())
                <div class="bg-white border border-slate-200 rounded-xl p-8 text-center">
                    <p class="text-sm text-slate-600">Belum ada perjalanan yang sesuai dengan pencarian.</p>
                </div>
            @else
                <!-- Desktop Table View -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Rute Perjalanan</th>
                                    <th scope="col" class="px-6 py-3.5">Armada Bus</th>
                                    <th scope="col" class="px-6 py-3.5">Waktu Berangkat</th>
                                    <th scope="col" class="px-6 py-3.5">Waktu Tiba</th>
                                    <th scope="col" class="px-6 py-3.5">Durasi</th>
                                    <th scope="col" class="px-6 py-3.5">Tarif</th>
                                    <th scope="col" class="px-6 py-3.5">Status</th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-800">
                                @foreach ($trips as $trip)
                                    <tr class="hover:bg-slate-50/75 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900">
                                                {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900">{{ $trip->bus->name }}</div>
                                            <div class="text-xs text-slate-500 font-mono">{{ $trip->bus->code }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900">
                                                {{ $trip->departure_at->format('H.i') }} WIB
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ $trip->departure_at->translatedFormat('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900">
                                                {{ $trip->arrival_at->format('H.i') }} WIB
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ $trip->arrival_at->translatedFormat('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            @php
                                                $hours = floor($trip->route->duration / 60);
                                                $minutes = $trip->route->duration % 60;
                                            @endphp
                                            {{ $hours }} jam {{ $minutes > 0 ? $minutes . ' mnt' : '' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-slate-900">
                                                Rp{{ number_format($trip->price, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block text-xs font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                Terjadwal
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a
                                                href="{{ route('customer.trips.show', $trip) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium text-xs rounded border border-blue-200 transition-colors"
                                            >
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card List View -->
                <div class="space-y-4 md:hidden">
                    @foreach ($trips as $trip)
                        <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="font-semibold text-base text-slate-900">
                                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ $trip->bus->name }} ({{ $trip->bus->code }})
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                    Terjadwal
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs py-2 border-y border-slate-100">
                                <div>
                                    <span class="text-slate-500 block">Berangkat:</span>
                                    <span class="font-medium text-slate-900">
                                        {{ $trip->departure_at->translatedFormat('d M Y') }}, {{ $trip->departure_at->format('H.i') }} WIB
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Tiba:</span>
                                    <span class="font-medium text-slate-900">
                                        {{ $trip->arrival_at->translatedFormat('d M Y') }}, {{ $trip->arrival_at->format('H.i') }} WIB
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div>
                                    <span class="text-xs text-slate-500 block">Tarif:</span>
                                    <span class="text-base font-bold text-slate-900">
                                        Rp{{ number_format($trip->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <a
                                    href="{{ route('customer.trips.show', $trip) }}"
                                    class="inline-flex items-center px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs rounded-lg transition-colors"
                                >
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Cari Perjalanan Bus - PO CAN Travel')
@section('meta_description', 'Temukan jadwal keberangkatan bus antarkota resmi PO CAN Travel. Cek tarif, durasi, dan sisa kursi secara real-time.')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-6 mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Cari Jadwal Perjalanan</h1>
            <p class="mt-1.5 text-sm text-slate-600">Temukan jadwal keberangkatan bus antarkota resmi sesuai tanggal, rute, dan preferensi Anda.</p>
        </div>

        <!-- Form Pencarian dengan Alpine.js -->
        <div
            class="bg-white border border-slate-200 rounded-xl p-6 mb-8 shadow-sm"
            x-data="{
                origin: '{{ request('origin') }}',
                destination: '{{ request('destination') }}',
                swap() {
                    let temp = this.origin;
                    this.origin = this.destination;
                    this.destination = temp;
                }
            }"
        >
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

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Kota Asal -->
                    <div class="md:col-span-4">
                        <label for="origin" class="block text-sm font-medium text-slate-700 mb-1.5">Kota Asal</label>
                        <select
                            name="origin"
                            id="origin"
                            x-model="origin"
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

                    <!-- Tombol Tukar Asal/Tujuan -->
                    <div class="md:col-span-1 flex items-center justify-center">
                        <button
                            type="button"
                            @click="swap()"
                            title="Tukar Kota Asal dan Tujuan"
                            aria-label="Tukar Kota Asal dan Tujuan"
                            class="w-10 h-10 flex items-center justify-center rounded-lg border border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </button>
                    </div>

                    <!-- Kota Tujuan -->
                    <div class="md:col-span-4">
                        <label for="destination" class="block text-sm font-medium text-slate-700 mb-1.5">Kota Tujuan</label>
                        <select
                            name="destination"
                            id="destination"
                            x-model="destination"
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
                    <div class="md:col-span-3">
                        <label for="departure_date" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Keberangkatan</label>
                        <input
                            type="date"
                            name="departure_date"
                            id="departure_date"
                            min="{{ date('Y-m-d') }}"
                            value="{{ request('departure_date') }}"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-xs font-medium text-slate-600">Urutkan:</label>
                        <select
                            name="sort"
                            id="sort"
                            onchange="this.form.submit()"
                            class="text-xs bg-slate-50 border border-slate-300 rounded-md px-2.5 py-1.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                            <option value="departure_asc" {{ request('sort', 'departure_asc') === 'departure_asc' ? 'selected' : '' }}>Keberangkatan Terawal</option>
                            <option value="departure_desc" {{ request('sort') === 'departure_desc' ? 'selected' : '' }}>Keberangkatan Terakhir</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Tarif Terendah</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Tarif Tertinggi</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors"
                        >
                            Cari Perjalanan
                        </button>
                        @if (request()->hasAny(['origin', 'destination', 'departure_date', 'sort']))
                            <a
                                href="{{ route('customer.trips.index') }}"
                                class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg transition-colors"
                            >
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Hasil Pencarian -->
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <h2 class="text-lg font-semibold text-slate-900">
                    Jadwal Perjalanan
                    <span class="text-sm font-normal text-slate-500">({{ $trips->count() }} perjalanan tersedia)</span>
                </h2>
                @if (request()->filled('origin') || request()->filled('destination') || request()->filled('departure_date'))
                    <div class="text-xs text-slate-500">
                        Filter aktif:
                        @if(request('origin')) <span class="font-medium text-slate-700">Asal {{ request('origin') }}</span> @endif
                        @if(request('destination')) <span class="font-medium text-slate-700">&bull; Tujuan {{ request('destination') }}</span> @endif
                        @if(request('departure_date')) <span class="font-medium text-slate-700">&bull; {{ \Carbon\Carbon::parse(request('departure_date'))->translatedFormat('d M Y') }}</span> @endif
                    </div>
                @endif
            </div>

            @if ($trips->isEmpty())
                <div class="bg-white border border-slate-200 rounded-xl p-8 sm:p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-base font-semibold text-slate-900">Belum ada perjalanan yang sesuai dengan pencarian.</p>
                        <p class="text-sm text-slate-500 mt-1.5">Silakan ganti tanggal atau rute asal/tujuan untuk melihat ketersediaan jadwal lainnya.</p>
                        <div class="mt-5">
                            <a
                                href="{{ route('customer.trips.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                            >
                                &larr; Tampilkan Semua Jadwal Tersedia
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Desktop Table View -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden hidden md:block shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-medium">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Rute Perjalanan</th>
                                    <th scope="col" class="px-6 py-3.5">Armada Bus</th>
                                    <th scope="col" class="px-6 py-3.5">Waktu Berangkat</th>
                                    <th scope="col" class="px-6 py-3.5">Waktu Tiba</th>
                                    <th scope="col" class="px-6 py-3.5">Durasi</th>
                                    <th scope="col" class="px-6 py-3.5">Ketersediaan</th>
                                    <th scope="col" class="px-6 py-3.5">Tarif</th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-800">
                                @foreach ($trips as $trip)
                                    @php
                                        $remainingSeats = max(0, $trip->bus->total_seats - ($trip->booked_seats_count ?? 0));
                                    @endphp
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
                                            <div class="font-medium text-slate-900 tabular-nums">
                                                {{ $trip->departure_at->format('H.i') }} WIB
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ $trip->departure_at->translatedFormat('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900 tabular-nums">
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
                                            @if($remainingSeats > 5)
                                                <span class="inline-flex items-center text-xs font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                    Sisa {{ $remainingSeats }} kursi
                                                </span>
                                            @elseif($remainingSeats > 0)
                                                <span class="inline-flex items-center text-xs font-medium text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                                    Sisa {{ $remainingSeats }} kursi
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-xs font-medium text-rose-800 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                                    Habis
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-slate-900 tabular-nums">
                                                Rp{{ number_format($trip->price, 0, ',', '.') }}
                                            </div>
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
                        @php
                            $remainingSeats = max(0, $trip->bus->total_seats - ($trip->booked_seats_count ?? 0));
                        @endphp
                        <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-3 shadow-sm">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-semibold text-base text-slate-900">
                                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ $trip->bus->name }} &bull; {{ $trip->bus->code }}
                                    </div>
                                </div>
                                <div>
                                    @if($remainingSeats > 5)
                                        <span class="text-xs font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 whitespace-nowrap">
                                            Sisa {{ $remainingSeats }} kursi
                                        </span>
                                    @elseif($remainingSeats > 0)
                                        <span class="text-xs font-medium text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 whitespace-nowrap">
                                            Sisa {{ $remainingSeats }} kursi
                                        </span>
                                    @else
                                        <span class="text-xs font-medium text-rose-800 bg-rose-50 px-2 py-0.5 rounded border border-rose-200 whitespace-nowrap">
                                            Habis
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs py-2 border-y border-slate-100">
                                <div>
                                    <span class="text-slate-500 block">Berangkat:</span>
                                    <span class="font-medium text-slate-900 tabular-nums">
                                        {{ $trip->departure_at->translatedFormat('d M Y') }}, {{ $trip->departure_at->format('H.i') }} WIB
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Tiba:</span>
                                    <span class="font-medium text-slate-900 tabular-nums">
                                        {{ $trip->arrival_at->translatedFormat('d M Y') }}, {{ $trip->arrival_at->format('H.i') }} WIB
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div>
                                    <span class="text-xs text-slate-500 block">Tarif per Kursi:</span>
                                    <span class="text-base font-bold text-slate-900 tabular-nums">
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
    </div>
</div>
@endsection

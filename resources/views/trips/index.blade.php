@extends('layouts.app')

@section('title', 'Jadwal & Tiket Bus Antarkota — PO CAN Travel')
@section('meta_description', 'Cari dan lihat jadwal perjalanan bus antarkota resmi PO CAN Travel. Pilih rute, cek ketersediaan kursi mandiri, dan pesan tiket langsung.')

@section('content')
<div x-data="{
    modifyOpen: false,
    origin: '{{ request('origin', '') }}',
    destination: '{{ request('destination', '') }}',
    departureDate: '{{ request('departure_date', request('date', '')) }}',
    sort: '{{ request('sort', 'departure_asc') }}',
    swap() {
        let temp = this.origin;
        this.origin = this.destination;
        this.destination = temp;
    },
    applySort(val) {
        let url = new URL(window.location.href);
        url.searchParams.set('sort', val);
        window.location.href = url.toString();
    }
}" class="min-h-screen bg-[#FAFBFD]">

    <!-- ═════════════════════════════════════════════════════════════════
         01. TOP SEARCH SUMMARY & MODIFICATION BAR (STICKY)
         ═════════════════════════════════════════════════════════════════ -->
    <section class="w-full bg-white border-b border-slate-200/80 sticky top-[76px] z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-wrap items-center justify-between gap-3">

                <!-- Left: Route & Date Summary Pills -->
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm">
                    <div class="inline-flex items-center gap-2 bg-orange-50/60 border border-orange-200/80 px-4 py-1.5 rounded-full font-heading font-bold text-slate-900">
                        <span>{{ request('origin') ?: 'Semua Kota Asal' }}</span>
                        <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <span>{{ request('destination') ?: 'Semua Tujuan' }}</span>
                    </div>

                    <span class="text-slate-300 font-medium hidden sm:inline-block">•</span>

                    <div class="inline-flex items-center gap-1.5 text-slate-500 text-xs sm:text-sm font-medium">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>
                            @if(request('departure_date') || request('date'))
                                {{ \Carbon\Carbon::parse(request('departure_date', request('date')))->translatedFormat('l, d M Y') }}
                            @else
                                Seluruh Jadwal Keberangkatan
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Right: Toggle Modify Search Button -->
                <div class="flex items-center gap-2">
                    <button
                        @click="modifyOpen = !modifyOpen"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-slate-50 hover:bg-orange-50 text-slate-700 hover:text-orange-600 border border-slate-200 hover:border-orange-300 font-heading font-semibold text-xs transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500"
                    >
                        <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        <span x-text="modifyOpen ? 'Tutup Form' : 'Ubah Pencarian'">Ubah Pencarian</span>
                    </button>
                </div>
            </div>

            <!-- Error Alert Banner -->
            @if($errors->any())
                <div class="pt-3 pb-1">
                    <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex items-center gap-2.5 shadow-xs">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="font-heading font-semibold">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <!-- Expandable Search Form Modification Panel -->
            <div
                x-show="modifyOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="pt-4 mt-3 pb-2 border-t border-slate-200/80"
                style="display: none;"
            >
                <form method="GET" action="{{ route('trips.index') }}" @submit="if(origin && destination && origin === destination) { $event.preventDefault(); alert('Kota tujuan tidak boleh sama dengan kota asal.'); }" class="p-4 sm:p-6 bg-white border border-slate-200/80 rounded-3xl shadow-sm">
                    <input type="hidden" name="sort" :value="sort">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-center">
                        <!-- Kota Asal -->
                        <div class="lg:col-span-3 flex flex-col justify-center bg-slate-50 hover:bg-orange-50/30 focus-within:bg-white focus-within:ring-2 focus-within:ring-orange-500 rounded-2xl p-3 border border-slate-200/80 transition-all">
                            <label for="filter_origin" class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-0.5 block cursor-pointer">Kota Asal</label>
                            <select id="filter_origin" name="origin" x-model="origin" class="bg-transparent w-full font-heading font-bold text-sm text-slate-900 focus:outline-none cursor-pointer py-0.5 truncate">
                                <option value="">Semua Kota Asal</option>
                                @foreach($origins as $o)
                                    <option value="{{ $o }}" {{ request('origin') === $o ? 'selected' : '' }}>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Swap Button -->
                        <div class="hidden lg:flex lg:col-span-1 items-center justify-center">
                            <button
                                type="button"
                                @click="swap()"
                                title="Tukar Kota Asal dan Tujuan"
                                class="w-10 h-10 rounded-full border border-slate-200 bg-white hover:bg-orange-50 text-slate-600 hover:text-orange-600 hover:border-orange-300 flex items-center justify-center transition-all cursor-pointer shadow-xs"
                            >
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Kota Tujuan -->
                        <div class="lg:col-span-3 flex flex-col justify-center bg-slate-50 hover:bg-orange-50/30 focus-within:bg-white focus-within:ring-2 focus-within:ring-orange-500 rounded-2xl p-3 border border-slate-200/80 transition-all">
                            <label for="filter_destination" class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-0.5 block cursor-pointer">Kota Tujuan</label>
                            <select id="filter_destination" name="destination" x-model="destination" class="bg-transparent w-full font-heading font-bold text-sm text-slate-900 focus:outline-none cursor-pointer py-0.5 truncate">
                                <option value="">Semua Kota Tujuan</option>
                                @foreach($destinations as $d)
                                    <option value="{{ $d }}" {{ request('destination') === $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanggal Keberangkatan -->
                        <div class="lg:col-span-3 flex flex-col justify-center bg-slate-50 hover:bg-orange-50/30 focus-within:bg-white focus-within:ring-2 focus-within:ring-orange-500 rounded-2xl p-3 border border-slate-200/80 transition-all">
                            <label for="filter_date" class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-0.5 block cursor-pointer">Tanggal Keberangkatan</label>
                            <input
                                id="filter_date"
                                type="date"
                                name="departure_date"
                                x-model="departureDate"
                                min="{{ date('Y-m-d') }}"
                                class="bg-transparent w-full font-heading font-bold text-sm text-slate-900 focus:outline-none cursor-pointer py-0.5"
                            />
                        </div>

                        <!-- Submit & Reset Actions -->
                        <div class="lg:col-span-2 flex items-center gap-2">
                            <button
                                type="submit"
                                class="flex-1 min-h-[46px] bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white py-2.5 px-4 rounded-full font-heading font-bold text-xs sm:text-sm transition-all shadow-md shadow-orange-500/20 flex items-center justify-center gap-1.5 cursor-pointer focus:ring-2 focus:ring-orange-500"
                            >
                                <span>Cari Tiket</span>
                            </button>
                            @if(request()->hasAny(['origin', 'destination', 'departure_date', 'date', 'sort']))
                                <a
                                    href="{{ route('trips.index') }}"
                                    class="min-h-[46px] px-4 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 font-semibold text-xs rounded-full border border-slate-200 flex items-center justify-center transition-colors"
                                    title="Reset Filter"
                                >
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- ═════════════════════════════════════════════════════════════════
         02. HORIZONTAL DATE RIBBON STRIP (QUICK DATE SELECTION)
         ═════════════════════════════════════════════════════════════════ -->
    <section class="w-full bg-[#FAFBFD] border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5">
                @php
                    $rawDate = request('departure_date', request('date'));
                    $baseDate = $rawDate ? \Carbon\Carbon::parse($rawDate) : now();
                    $dates = [
                        $baseDate->copy()->subDays(2),
                        $baseDate->copy()->subDay(),
                        $baseDate->copy(),
                        $baseDate->copy()->addDay(),
                        $baseDate->copy()->addDays(2),
                    ];
                @endphp

                @foreach($dates as $d)
                    @php
                        $isCurrent = $d->isSameDay($baseDate);
                        $dateStr = $d->format('Y-m-d');
                    @endphp
                    <a
                        href="{{ request()->fullUrlWithQuery(['departure_date' => $dateStr]) }}"
                        class="flex-1 min-w-[130px] px-4 py-2.5 rounded-2xl text-left transition-all {{ $isCurrent ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md shadow-orange-500/20' : 'bg-white hover:bg-orange-50/50 border border-slate-200/80 text-slate-800' }}"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold block {{ $isCurrent ? 'text-white/80' : 'text-slate-500' }}">
                                {{ $d->translatedFormat('D, d M') }}
                            </span>
                            @if($isCurrent)
                                <span class="inline-block w-2 h-2 rounded-full bg-white"></span>
                            @endif
                        </div>
                        <span class="text-xs sm:text-sm font-heading font-bold block mt-0.5 {{ $isCurrent ? 'text-white' : 'text-slate-900' }}">
                            {{ $isCurrent ? 'Tanggal Dipilih' : 'Pilih Tanggal' }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═════════════════════════════════════════════════════════════════
         03. MAIN CATALOG GRID: SIDEBAR & TIMETABLE RESULTS
         ═════════════════════════════════════════════════════════════════ -->
    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- LEFT SIDEBAR: SORTING & INFORMASI -->
            <aside class="lg:col-span-3 flex flex-col gap-5 sticky top-36">
                <!-- Sorting Box -->
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col gap-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="font-heading font-bold text-sm text-slate-900">Urutkan Jadwal</span>
                        <a href="{{ route('trips.index') }}" class="text-xs text-orange-600 hover:text-orange-700 font-semibold transition-colors">Reset</a>
                    </div>

                    <div class="flex flex-col gap-1.5 text-xs">
                        <label class="flex items-center justify-between py-2 px-2.5 rounded-xl hover:bg-orange-50/40 cursor-pointer transition-colors">
                            <span class="font-medium text-slate-800">Keberangkatan Terawal</span>
                            <input
                                type="radio"
                                name="sortOption"
                                value="departure_asc"
                                :checked="sort === 'departure_asc'"
                                @change="applySort('departure_asc')"
                                class="accent-orange-500 w-4 h-4 cursor-pointer"
                            />
                        </label>
                        <label class="flex items-center justify-between py-2 px-2.5 rounded-xl hover:bg-orange-50/40 cursor-pointer transition-colors">
                            <span class="font-medium text-slate-800">Keberangkatan Terakhir</span>
                            <input
                                type="radio"
                                name="sortOption"
                                value="departure_desc"
                                :checked="sort === 'departure_desc'"
                                @change="applySort('departure_desc')"
                                class="accent-orange-500 w-4 h-4 cursor-pointer"
                            />
                        </label>
                        <label class="flex items-center justify-between py-2 px-2.5 rounded-xl hover:bg-orange-50/40 cursor-pointer transition-colors">
                            <span class="font-medium text-slate-800">Tarif Terendah</span>
                            <input
                                type="radio"
                                name="sortOption"
                                value="price_asc"
                                :checked="sort === 'price_asc'"
                                @change="applySort('price_asc')"
                                class="accent-orange-500 w-4 h-4 cursor-pointer"
                            />
                        </label>
                        <label class="flex items-center justify-between py-2 px-2.5 rounded-xl hover:bg-orange-50/40 cursor-pointer transition-colors">
                            <span class="font-medium text-slate-800">Tarif Tertinggi</span>
                            <input
                                type="radio"
                                name="sortOption"
                                value="price_desc"
                                :checked="sort === 'price_desc'"
                                @change="applySort('price_desc')"
                                class="accent-orange-500 w-4 h-4 cursor-pointer"
                            />
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 leading-relaxed">
                        <p>Harga dan ketersediaan kursi diperbarui secara langsung sesuai data sistem operasional real-time.</p>
                    </div>
                </div>

                <!-- Bantuan Layanan Card -->
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col gap-2">
                    <span class="font-heading font-bold text-sm text-slate-900">Pusat Bantuan</span>
                    <p class="text-xs text-slate-500 leading-relaxed">Panduan lengkap mengenai tata cara pemesanan tiket dan denah kursi tersedia di halaman bantuan.</p>
                    <a href="{{ route('faq') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700 mt-1 inline-flex items-center gap-1">
                        <span>Pusat Bantuan &amp; FAQ &rarr;</span>
                    </a>
                </div>
            </aside>

            <!-- RIGHT COLUMN: SEARCH RESULTS TIMETABLE -->
            <section class="lg:col-span-9 flex flex-col gap-4">
                <!-- Header Summary Count -->
                <div class="flex flex-wrap items-center justify-between gap-2 pb-1">
                    <div class="flex items-center gap-2.5">
                        <h1 class="font-heading font-extrabold text-xl text-slate-900">Jadwal Perjalanan Bus</h1>
                        <span class="px-3 py-1 rounded-full bg-orange-50 text-orange-600 font-heading font-bold text-xs border border-orange-200/80">
                            {{ $trips->total() }} Perjalanan Tersedia
                        </span>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">Jadwal Keberangkatan Terjadwal</span>
                </div>

                @if($trips->isEmpty())
                    <!-- 14.4 EMPTY STATE -->
                    <div class="text-center py-16 px-6 bg-white rounded-3xl border border-slate-200/80 shadow-xs">
                        <div class="w-14 h-14 rounded-full bg-orange-50 border border-orange-200/80 text-orange-500 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <h2 class="font-heading font-bold text-lg text-slate-900">Belum ada perjalanan yang sesuai dengan pencarian.</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                            @if(request('origin') || request('destination') || request('departure_date'))
                                Tidak ada jadwal aktif untuk rute
                                <strong>{{ request('origin', 'Semua Kota') }}</strong> menuju <strong>{{ request('destination', 'Semua Kota') }}</strong>
                                @if(request('departure_date'))
                                    pada tanggal <strong>{{ \Carbon\Carbon::parse(request('departure_date'))->translatedFormat('d M Y') }}</strong>.
                                @endif
                                Silakan pilih tanggal lain atau atur ulang kota asal dan tujuan Anda.
                            @else
                                Saat ini belum ada jadwal perjalanan antarkota yang dibuka untuk rute ini.
                            @endif
                        </p>
                        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('trips.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-xs font-heading font-bold rounded-full hover:from-orange-600 hover:to-amber-600 transition-all shadow-md shadow-orange-500/20">
                                <span>Lihat Semua Jadwal</span>
                            </a>
                            <button @click="modifyOpen = true" type="button" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-full border border-slate-200 transition-colors">
                                <span>Ubah Pencarian</span>
                            </button>
                        </div>
                    </div>
                @else
                    <!-- 14.2 SEARCH RESULTS TIMETABLE -->
                    <div class="flex flex-col gap-4">
                        @foreach($trips as $trip)
                            @php
                                $availableSeats = max(0, $trip->bus->total_seats - ($trip->booked_seats_count ?? 0));
                                $durationHours = floor($trip->route->duration / 60);
                                $durationMinutes = $trip->route->duration % 60;
                                $isNextDay = $trip->arrival_at->day > $trip->departure_at->day;
                            @endphp

                            <article class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 hover:border-orange-300 shadow-xs hover:shadow-md transition-all flex flex-col gap-4">
                                <!-- Card Header: Bus Code & Class -->
                                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <span class="font-heading font-bold text-sm text-slate-900">
                                            {{ $trip->bus->name }}
                                        </span>
                                        <span class="font-mono text-slate-600 text-xs px-2.5 py-0.5 rounded-full bg-slate-50 border border-slate-200/80">
                                            {{ $trip->bus->code }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-500 font-medium">
                                        Kapasitas {{ $trip->bus->total_seats }} Kursi
                                    </div>
                                </div>

                                <!-- Timeline Grid: Departure, Duration, Arrival -->
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                    <!-- Departure Origin -->
                                    <div class="md:col-span-3 flex flex-col">
                                        <span class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight tabular-nums">
                                            {{ $trip->departure_at->format('H:i') }}
                                        </span>
                                        <span class="font-heading font-bold text-sm text-slate-900 mt-0.5">
                                            {{ $trip->route->origin }}
                                        </span>
                                        <span class="text-[11px] text-slate-400">
                                            {{ $trip->departure_at->translatedFormat('d M Y') }}
                                        </span>
                                    </div>

                                    <!-- Duration & Route Path -->
                                    <div class="md:col-span-6 flex flex-col items-center justify-center px-2">
                                        <div class="flex items-center justify-between w-full text-xs text-slate-500 mb-1">
                                            <span class="text-[11px]">Durasi</span>
                                            <span class="font-heading font-bold text-slate-800 text-xs">
                                                {{ $durationHours }}j {{ $durationMinutes > 0 ? $durationMinutes . 'm' : '' }}
                                            </span>
                                        </div>

                                        <div class="relative w-full flex items-center my-1">
                                            <div class="w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0"></div>
                                            <div class="flex-1 h-0.5 bg-gradient-to-r from-orange-400 to-sky-400"></div>
                                            <svg class="w-3.5 h-3.5 text-orange-500 shrink-0 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1 h-0.5 bg-gradient-to-r from-orange-400 to-sky-400"></div>
                                            <div class="w-2.5 h-2.5 rounded-full bg-sky-500 shrink-0"></div>
                                        </div>

                                        <span class="text-[10px] text-slate-400 mt-0.5 text-center">
                                            Rute Antarkota Langsung
                                        </span>
                                    </div>

                                    <!-- Arrival Destination -->
                                    <div class="md:col-span-3 flex flex-col md:text-right">
                                        <div class="flex items-center md:justify-end gap-1.5">
                                            <span class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight tabular-nums">
                                                {{ $trip->arrival_at->format('H:i') }}
                                            </span>
                                            @if($isNextDay)
                                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold border border-amber-200">
                                                    +1 Hari
                                                </span>
                                            @endif
                                        </div>
                                        <span class="font-heading font-bold text-sm text-slate-900 mt-0.5">
                                            {{ $trip->route->destination }}
                                        </span>
                                        <span class="text-[11px] text-slate-400">
                                            {{ $trip->arrival_at->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Amenities Strip & Facilities -->
                                <div class="flex flex-wrap items-center justify-between gap-2 bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        @if($trip->bus->facilities && $trip->bus->facilities->isNotEmpty())
                                            @foreach($trip->bus->facilities->take(3) as $fac)
                                                <span class="inline-flex items-center gap-1 bg-white px-2.5 py-1 rounded-full border border-slate-200/80 text-xs text-slate-700">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    <span>{{ $fac->name }}</span>
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-slate-500 text-xs">Fasilitas Standar Armada AC</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('trips.show', $trip) }}" class="text-orange-600 hover:text-orange-700 font-semibold text-xs inline-flex items-center gap-1">
                                        <span>Detail Jadwal</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>

                                <!-- Seats Availability, Price & CTA Action -->
                                <div class="flex flex-wrap items-center justify-between gap-4 pt-1">
                                    <!-- Seat Status Badge -->
                                    <div>
                                        @if($availableSeats > 5)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200/80">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                {{ $availableSeats }} kursi tersedia
                                            </span>
                                        @elseif($availableSeats > 0)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-xs border border-amber-200/80">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                Sisa {{ $availableSeats }} kursi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 font-bold text-xs border border-rose-200/80">
                                                Kursi Penuh
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Price & CTA -->
                                    <div class="flex items-center gap-4 sm:gap-6">
                                        <div class="flex flex-col text-right">
                                            <span class="text-[11px] text-slate-500">Tarif per penumpang</span>
                                            <span class="font-heading font-extrabold text-xl sm:text-2xl text-orange-600 tabular-nums">
                                                Rp{{ number_format($trip->price, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        @auth
                                            @if(auth()->user()->role === 'customer')
                                                <a
                                                    href="{{ route('customer.trips.seats', $trip) }}"
                                                    class="px-6 py-2.5 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-xs sm:text-sm transition-all shadow-md shadow-orange-500/20 inline-flex items-center gap-1.5 focus:ring-2 focus:ring-orange-500"
                                                >
                                                    <span>Pilih Kursi</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('trips.show', $trip) }}"
                                                    class="px-5 py-2.5 rounded-full bg-slate-800 hover:bg-slate-900 text-white font-heading font-bold text-xs sm:text-sm transition-all shadow-xs inline-flex items-center gap-1.5"
                                                >
                                                    <span>Detail Jadwal</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('login') }}"
                                                class="px-6 py-2.5 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-xs sm:text-sm transition-all shadow-md shadow-orange-500/20 inline-flex items-center gap-1.5 focus:ring-2 focus:ring-orange-500"
                                            >
                                                <span>Pilih Kursi</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $trips->links() }}
                    </div>
                @endif

                <!-- Travel Guidelines Notice Strip -->
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0 border border-orange-200/80">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-sm text-slate-900">Informasi Keberangkatan PO CAN Travel</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Penumpang diimbau tiba di titik keberangkatan minimal 30 menit sebelum jadwal bus berangkat.</p>
                        </div>
                    </div>
                    <a href="{{ route('departure-info') }}" class="shrink-0 px-5 py-2.5 rounded-full bg-slate-50 hover:bg-orange-50 border border-slate-200 hover:border-orange-300 text-slate-700 hover:text-orange-600 text-xs font-semibold transition-colors">
                        Panduan Keberangkatan &rarr;
                    </a>
                </div>
            </section>

        </div>
    </main>
</div>
@endsection

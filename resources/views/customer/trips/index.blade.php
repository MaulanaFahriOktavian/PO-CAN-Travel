@extends('layouts.app')

@section('title', 'Cari Jadwal Perjalanan Bus — PO CAN Travel')
@section('meta_description', 'Temukan jadwal keberangkatan bus antarkota resmi sesuai tanggal, rute, dan armada pilihan Anda.')

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
                    <div class="inline-flex items-center gap-2 bg-orange-50/60 border border-orange-200/80 px-4 py-1.5 rounded-full font-heading font-bold text-slate-800">
                        <span>{{ request('origin') ?: 'Semua Kota Asal' }}</span>
                        <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
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
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        <span x-text="modifyOpen ? 'Tutup Form' : 'Ubah Pencarian'">Ubah Pencarian</span>
                    </button>
                </div>
            </div>

            <!-- Expandable Search Form Modification Panel -->
            <div 
                x-show="modifyOpen" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="pt-4 mt-3 pb-2 border-t border-slate-100"
                style="display: none;"
            >
                <form method="GET" action="{{ route('customer.trips.index') }}" class="p-5 bg-white border border-slate-200/80 rounded-2xl shadow-xs">
                    <input type="hidden" name="sort" :value="sort">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-center">
                        <!-- Kota Asal -->
                        <div class="lg:col-span-3 flex flex-col justify-center bg-slate-50 hover:bg-slate-100/70 focus-within:bg-white focus-within:ring-2 focus-within:ring-orange-500 rounded-2xl p-3 border border-slate-200 transition-all">
                            <label for="filter_origin_customer" class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-0.5 block cursor-pointer">Kota Asal</label>
                            <select id="filter_origin_customer" name="origin" x-model="origin" class="bg-transparent w-full font-heading font-bold text-sm text-slate-800 focus:outline-none cursor-pointer py-0.5 truncate">
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
                                class="w-9 h-9 rounded-full border border-slate-200 bg-white hover:bg-orange-50 text-slate-500 hover:text-orange-500 hover:border-orange-300 flex items-center justify-center transition-all cursor-pointer shadow-xs"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Kota Tujuan -->
                        <div class="lg:col-span-3 flex flex-col justify-center bg-slate-50 hover:bg-slate-100/70 focus-within:bg-white focus-within:ring-2 focus-within:ring-orange-500 rounded-2xl p-3 border border-slate-200 transition-all">
                            <label for="filter_destination_customer" class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-0.5 block cursor-pointer">Kota Tujuan</label>
                            <select id="filter_destination_customer" name="destination" x-model="destination" class="bg-transparent w-full font-heading font-bold text-sm text-slate-800 focus:outline-none cursor-pointer py-0.5 truncate">
                                <option value="">Semua Kota Tujuan</option>
                                @foreach($destinations as $d)
                                    <option value="{{ $d }}" {{ request('destination') === $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanggal Keberangkatan -->
                        <div class="lg:col-span-3 flex flex-col justify-center bg-slate-50 hover:bg-slate-100/70 focus-within:bg-white focus-within:ring-2 focus-within:ring-orange-500 rounded-2xl p-3 border border-slate-200 transition-all">
                            <label for="filter_date_customer" class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-0.5 block cursor-pointer">Tanggal Keberangkatan</label>
                            <input 
                                id="filter_date_customer"
                                type="date" 
                                name="departure_date" 
                                x-model="departureDate"
                                class="bg-transparent w-full font-heading font-bold text-sm text-slate-800 focus:outline-none cursor-pointer py-0.5"
                            />
                        </div>

                        <!-- Submit & Reset Actions -->
                        <div class="lg:col-span-2 flex items-center gap-2">
                            <button 
                                type="submit" 
                                class="flex-1 min-h-[46px] bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white py-2.5 px-4 rounded-full font-heading font-bold text-xs sm:text-sm transition-all shadow-md shadow-orange-500/20 flex items-center justify-center gap-1.5 cursor-pointer focus:ring-2 focus:ring-orange-500"
                            >
                                <span>Cari Jadwal</span>
                            </button>
                            @if(request()->hasAny(['origin', 'destination', 'departure_date', 'date', 'sort']))
                                <a 
                                    href="{{ route('customer.trips.index') }}" 
                                    class="min-h-[46px] px-3.5 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-800 font-semibold text-xs rounded-full border border-slate-200 flex items-center justify-center transition-colors"
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
    <section class="w-full bg-slate-50/60 border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center gap-2.5 overflow-x-auto no-scrollbar py-0.5">
                @php
                    $activeDateParam = request('departure_date', request('date'));
                    $baseDate = $activeDateParam ? \Carbon\Carbon::parse($activeDateParam) : now();
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
                        class="flex-1 min-w-[140px] px-4 py-2.5 rounded-2xl text-left transition-all border {{ $isCurrent ? 'bg-gradient-to-r from-orange-500 to-amber-500 border-orange-500 text-white shadow-sm shadow-orange-500/20' : 'bg-white hover:bg-orange-50/40 border-slate-200/80 text-slate-800' }}"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold block {{ $isCurrent ? 'text-white/80' : 'text-slate-500' }}">
                                {{ $d->translatedFormat('D, d M') }}
                            </span>
                            @if($isCurrent)
                                <span class="inline-block w-2 h-2 rounded-full bg-white"></span>
                            @endif
                        </div>
                        <span class="text-xs sm:text-sm font-heading font-bold block mt-1 {{ $isCurrent ? 'text-white' : 'text-slate-800' }}">
                            {{ $isCurrent ? 'Tanggal Dipilih' : 'Pilih Tanggal' }}
                        </span>
                        <span class="text-[10px] block mt-0.5 {{ $isCurrent ? 'text-white/70' : 'text-slate-400' }}">
                            Jadwal Keberangkatan
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    <!-- ═════════════════════════════════════════════════════════════════
         03. MAIN CATALOG GRID AREA
         ═════════════════════════════════════════════════════════════════ -->
    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT SIDEBAR: SORTING & OPERATIONAL INFO -->
            <aside class="lg:col-span-3 flex flex-col gap-4 sticky top-36">
                <!-- Sorting Box -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_16px_40px_-15px_rgba(15,23,42,0.04)] flex flex-col gap-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="text-sm font-heading font-bold text-slate-900">Urutkan Jadwal</span>
                        @if(request()->hasAny(['sort']))
                            <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" class="text-xs text-orange-500 hover:underline font-semibold transition-colors">Reset</a>
                        @endif
                    </div>

                    <!-- Sorting Options -->
                    <div class="flex flex-col gap-1.5 text-xs">
                        <label class="flex items-center justify-between py-2 px-3 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors {{ request('sort', 'departure_asc') === 'departure_asc' ? 'bg-orange-50 font-bold text-orange-600' : 'text-slate-700' }}">
                            <span class="font-medium">Keberangkatan Terawal</span>
                            <input 
                                type="radio" 
                                name="sortOption" 
                                value="departure_asc"
                                :checked="sort === 'departure_asc'"
                                @change="applySort('departure_asc')"
                                class="accent-orange-500 w-4 h-4 cursor-pointer"
                            />
                        </label>
                        <label class="flex items-center justify-between py-2 px-3 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors {{ request('sort') === 'departure_desc' ? 'bg-orange-50 font-bold text-orange-600' : 'text-slate-700' }}">
                            <span class="font-medium">Keberangkatan Terakhir</span>
                            <input 
                                type="radio" 
                                name="sortOption" 
                                value="departure_desc"
                                :checked="sort === 'departure_desc'"
                                @change="applySort('departure_desc')"
                                class="accent-orange-500 w-4 h-4 cursor-pointer"
                            />
                        </label>
                        <label class="flex items-center justify-between py-2 px-3 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors {{ request('sort') === 'price_asc' ? 'bg-orange-50 font-bold text-orange-600' : 'text-slate-700' }}">
                            <span class="font-medium">Tarif Terendah</span>
                            <input 
                                type="radio" 
                                name="sortOption" 
                                value="price_asc"
                                :checked="sort === 'price_asc'"
                                @change="applySort('price_asc')"
                                class="accent-orange-500 w-4 h-4 cursor-pointer"
                            />
                        </label>
                        <label class="flex items-center justify-between py-2 px-3 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors {{ request('sort') === 'price_desc' ? 'bg-orange-50 font-bold text-orange-600' : 'text-slate-700' }}">
                            <span class="font-medium">Tarif Tertinggi</span>
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

                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500 leading-relaxed">
                        <p>Harga dan ketersediaan kursi diperbarui secara langsung sesuai data sistem operasional.</p>
                    </div>
                </div>

                <!-- Bantuan Layanan Card -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-[0_16px_40px_-15px_rgba(15,23,42,0.04)] flex flex-col gap-2">
                    <span class="text-xs font-heading font-bold text-slate-900">Pusat Bantuan &amp; Panduan</span>
                    <p class="text-xs text-slate-500 leading-relaxed">Ketentuan tiket, tata cara reservasi mandiri, dan bagasi penumpang tersedia lengkap.</p>
                    <a href="{{ route('faq') }}" class="text-xs font-semibold text-orange-500 hover:text-orange-600 hover:underline mt-1 inline-flex items-center gap-1">
                        <span>Pusat Bantuan &amp; FAQ</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </aside>

            <!-- RIGHT COLUMN: SEARCH RESULTS TIMELINE -->
            <section class="lg:col-span-9 flex flex-col gap-4">
                <!-- Header Summary Count -->
                <div class="flex flex-wrap items-center justify-between gap-2 pb-1">
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-lg sm:text-xl font-heading font-bold text-slate-900">Cari Jadwal Perjalanan</h1>
                        <span class="px-3 py-1 rounded-full bg-orange-50 text-orange-600 font-heading font-bold border border-orange-200/80 text-xs">
                            {{ count($trips) }} Bus Ditemukan
                        </span>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">Jadwal Keberangkatan Terjadwal</span>
                </div>

                @if($trips->isEmpty())
                    <!-- EMPTY STATE -->
                    <div class="text-center py-16 px-6 bg-white rounded-3xl border border-slate-200/80 shadow-xs">
                        <div class="w-16 h-16 rounded-2xl bg-orange-50 border border-orange-200/80 text-orange-500 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-heading font-bold text-slate-900">Belum ada perjalanan yang sesuai dengan pencarian.</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                            Tidak ada jadwal perjalanan yang ditemukan. Silakan coba ganti tanggal atau pilih kota asal dan tujuan yang lain untuk melihat armada PO CAN Travel yang tersedia.
                        </p>
                        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('customer.trips.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-xs font-heading font-bold rounded-full transition-all shadow-md shadow-orange-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span>Lihat Semua Jadwal</span>
                            </a>
                        </div>
                    </div>
                @else
                    @foreach($trips as $trip)
                        @php
                            $availableSeats = max(0, $trip->bus->total_seats - ($trip->booked_seats_count ?? 0));
                            $durationHours = floor($trip->route->duration / 60);
                            $durationMinutes = $trip->route->duration % 60;
                            $isNextDay = $trip->arrival_at->day > $trip->departure_at->day;
                        @endphp

                        <article class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 hover:border-orange-300 hover:shadow-md transition-all flex flex-col gap-4 shadow-[0_16px_40px_-15px_rgba(15,23,42,0.04)]">
                            <!-- Card Header: Bus Info & Status -->
                            <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2.5">
                                    <span class="px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-xs font-heading font-bold border border-orange-200/80">
                                        {{ $trip->bus->name }}
                                    </span>
                                    <span class="font-mono text-slate-500 text-xs font-semibold">
                                        {{ $trip->bus->code }}
                                    </span>
                                    <span class="hidden sm:inline-block text-slate-300">•</span>
                                    <span class="hidden sm:inline-block text-xs text-slate-500 font-medium">
                                        {{ $trip->bus->bus_type ?? 'Bus Antarkota' }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-500 font-medium">
                                    Kapasitas {{ $trip->bus->total_seats }} Kursi
                                </div>
                            </div>

                            <!-- Timeline & Route Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <!-- Origin Departure -->
                                <div class="md:col-span-3 flex flex-col">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight leading-tight">
                                            {{ $trip->departure_at->format('H:i') }}
                                        </span>
                                        <span class="text-xs text-slate-400 font-medium">WIB</span>
                                    </div>
                                    <span class="text-sm font-heading font-bold text-slate-900 mt-1">
                                        {{ $trip->route->origin }}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        Terminal {{ $trip->route->origin }}
                                    </span>
                                </div>

                                <!-- Progress Graphic -->
                                <div class="md:col-span-6 flex flex-col items-center justify-center px-2 py-1">
                                    <div class="flex items-center justify-between w-full text-slate-500 text-xs mb-1.5">
                                        <span class="text-slate-400 font-medium text-xs">
                                            Estimasi Durasi
                                        </span>
                                        <span class="font-heading font-bold text-slate-800 text-xs">
                                            {{ $durationHours }}j {{ $durationMinutes > 0 ? $durationMinutes . 'm' : '' }}
                                        </span>
                                    </div>

                                    <div class="relative w-full flex items-center my-1">
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-900 shrink-0"></div>
                                        <div class="flex-1 h-[2px] bg-slate-200"></div>
                                        <div class="w-2 h-2 rounded-full bg-orange-500 shrink-0"></div>
                                        <div class="flex-1 h-[2px] bg-slate-200"></div>
                                        <div class="w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0"></div>
                                    </div>

                                    <span class="text-[10px] text-slate-400 mt-1 text-center font-medium">
                                        Jalur antarkota langsung &bull; Tol Trans Jawa
                                    </span>
                                </div>

                                <!-- Destination Arrival -->
                                <div class="md:col-span-3 flex flex-col md:text-right">
                                    <div class="flex items-center md:justify-end gap-1.5">
                                        <span class="text-2xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight leading-tight">
                                            {{ $trip->arrival_at->format('H:i') }}
                                        </span>
                                        <span class="text-xs text-slate-400 font-medium">WIB</span>
                                        @if($isNextDay)
                                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold border border-amber-200">
                                                +1 Hari
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-sm font-heading font-bold text-slate-900 mt-1">
                                        {{ $trip->route->destination }}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        Terminal {{ $trip->route->destination }}
                                    </span>
                                </div>
                            </div>

                            <!-- Amenities & Detail Link Strip -->
                            <div class="flex flex-wrap items-center justify-between gap-2 bg-slate-50/60 p-2.5 sm:p-3 rounded-2xl border border-slate-200/80">
                                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-600">
                                    <span class="font-heading font-bold text-slate-800 bg-white px-2.5 py-0.5 rounded-full border border-slate-200 text-xs">
                                        {{ $trip->bus->bus_type ?? 'Bus Antarkota' }}
                                    </span>
                                    @if($trip->bus->facilities && $trip->bus->facilities->isNotEmpty())
                                        @foreach($trip->bus->facilities->take(3) as $fac)
                                            <span class="inline-flex items-center gap-1 bg-white px-2.5 py-0.5 rounded-full border border-slate-200 text-xs text-slate-700">
                                                <svg class="w-3 h-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>{{ $fac->name }}</span>
                                            </span>
                                        @endforeach
                                    @endif
                                </div>

                                <a href="{{ route('customer.trips.show', $trip) }}" class="text-orange-500 hover:text-orange-600 font-heading font-bold text-xs hover:underline flex items-center gap-1">
                                    <span>Detail Perjalanan</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                            <!-- Price & Seat Selection CTA -->
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-1">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-xs font-heading font-bold {{ $availableSeats > 5 ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : ($availableSeats > 0 ? 'text-amber-700 bg-amber-50 border-amber-200' : 'text-rose-700 bg-rose-50 border-rose-200') }} border px-3 py-1 rounded-full">
                                        Sisa {{ $availableSeats }} kursi
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 sm:gap-4">
                                    <div class="flex flex-col text-right">
                                        <span class="text-[10px] text-slate-400 font-medium">Tarif per penumpang</span>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-xl sm:text-2xl text-orange-600 font-heading font-black">
                                                Rp{{ number_format($trip->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($availableSeats > 0)
                                        <a 
                                            href="{{ route('customer.trips.seats', $trip) }}" 
                                            class="px-6 py-2.5 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-xs sm:text-sm transition-all shadow-md shadow-orange-500/20 flex items-center gap-1.5 cursor-pointer"
                                        >
                                            <span>Pilih Kursi</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button 
                                            disabled 
                                            class="px-6 py-2.5 rounded-full bg-gray-200 text-gray-500 font-heading font-bold text-xs sm:text-sm cursor-not-allowed"
                                        >
                                            Kursi Penuh
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endif

                <!-- Travel Information Guidance -->
                <div class="bg-gradient-to-r from-orange-50/40 via-white to-amber-50/20 rounded-3xl p-5 sm:p-6 border border-orange-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-2">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0 border border-orange-200/80 shadow-2xs">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-heading font-bold text-slate-900">Informasi Keberangkatan PO CAN Travel</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Harap hadir di titik keberangkatan selambat-lambatnya 30 menit sebelum jadwal keberangkatan bus.</p>
                        </div>
                    </div>
                    <a href="{{ route('how-to-order') }}" class="shrink-0 px-5 py-2.5 rounded-full bg-white hover:bg-orange-50/50 border border-slate-200 text-slate-800 hover:text-orange-600 text-xs font-heading font-bold transition-colors">
                        Panduan Perjalanan
                    </a>
                </div>
            </section>

        </div>
    </main>
</div>
@endsection

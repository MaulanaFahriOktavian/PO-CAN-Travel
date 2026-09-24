@extends('layouts.app')

@section('title', 'Jadwal & Tiket Bus — PO CAN Travel')
@section('meta_description', 'Cari dan lihat jadwal perjalanan bus antarkota resmi PO CAN Travel. Pilih rute, cek ketersediaan kursi, dan pesan tiket langsung.')

@section('content')
<div x-data="{
    modifyOpen: false,
    origin: '{{ request('origin', '') }}',
    destination: '{{ request('destination', '') }}',
    departureDate: '{{ request('departure_date', '') }}',
    sort: '{{ request('sort', 'departure_asc') }}',
    applySort(val) {
        let url = new URL(window.location.href);
        url.searchParams.set('sort', val);
        window.location.href = url.toString();
    }
}">

    <!-- Top Search Sticky Summary Bar -->
    <section class="w-full bg-[#FBFAF6] border-b border-[#D9D5CA] shadow-2xs sticky top-16 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm">
                    <div class="inline-flex items-center gap-2 bg-white border border-[#D9D5CA] px-3.5 py-1.5 rounded-full font-bold text-[#1C2522]">
                        <span>{{ request('origin') ?: 'Semua Asal' }}</span>
                        <span class="material-symbols-outlined text-[16px] text-[#21483C]">arrow_right_alt</span>
                        <span>{{ request('destination') ?: 'Semua Tujuan' }}</span>
                    </div>

                    <span class="text-[#D9D5CA] font-medium hidden sm:inline-block">•</span>

                    <div class="inline-flex items-center gap-1.5 text-[#66716C] font-medium">
                        <span class="material-symbols-outlined text-[16px] text-[#21483C]">calendar_today</span>
                        <span>
                            @if(request('departure_date'))
                                {{ \Carbon\Carbon::parse(request('departure_date'))->translatedFormat('D, d M Y') }}
                            @else
                                Hari Ini &amp; Mendatang
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        @click="modifyOpen = !modifyOpen" 
                        type="button"
                        class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-[#F5F1E8] hover:bg-[#D9D5CA]/50 text-[#1C2522] border border-[#D9D5CA] font-semibold text-xs transition-colors cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[16px]">tune</span>
                        <span x-text="modifyOpen ? 'Tutup Filter' : 'Ubah Pencarian'">Ubah Pencarian</span>
                    </button>
                </div>
            </div>

            <!-- Expandable Search Modification Panel -->
            <div 
                x-show="modifyOpen" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="pt-4 mt-3 pb-2 border-t border-[#D9D5CA]"
                style="display: none;"
            >
                <form method="GET" action="{{ route('trips.index') }}" class="p-4 sm:p-5 bg-white border border-[#D9D5CA] rounded-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <!-- Kota Asal -->
                    <div class="flex flex-col gap-1.5">
                        <label for="filter_origin" class="text-xs font-bold text-[#1C2522]">Kota Asal</label>
                        <div class="flex items-center gap-2 bg-[#FBFAF6] border border-[#D9D5CA] px-3 py-2 rounded-xl">
                            <span class="material-symbols-outlined text-[#21483C] text-[18px]">trip_origin</span>
                            <select id="filter_origin" name="origin" class="bg-transparent w-full text-[#1C2522] text-xs sm:text-sm font-semibold focus:outline-none">
                                <option value="">Semua Kota Asal</option>
                                @foreach($origins as $o)
                                    <option value="{{ $o }}" {{ request('origin') === $o ? 'selected' : '' }}>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Kota Tujuan -->
                    <div class="flex flex-col gap-1.5">
                        <label for="filter_destination" class="text-xs font-bold text-[#1C2522]">Kota Tujuan</label>
                        <div class="flex items-center gap-2 bg-[#FBFAF6] border border-[#D9D5CA] px-3 py-2 rounded-xl">
                            <span class="material-symbols-outlined text-[#21483C] text-[18px]">location_on</span>
                            <select id="filter_destination" name="destination" class="bg-transparent w-full text-[#1C2522] text-xs sm:text-sm font-semibold focus:outline-none">
                                <option value="">Semua Kota Tujuan</option>
                                @foreach($destinations as $d)
                                    <option value="{{ $d }}" {{ request('destination') === $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tanggal Keberangkatan -->
                    <div class="flex flex-col gap-1.5">
                        <label for="filter_date" class="text-xs font-bold text-[#1C2522]">Tanggal Keberangkatan</label>
                        <div class="flex items-center gap-2 bg-[#FBFAF6] border border-[#D9D5CA] px-3 py-2 rounded-xl">
                            <span class="material-symbols-outlined text-[#21483C] text-[18px]">calendar_month</span>
                            <input 
                                id="filter_date"
                                type="date" 
                                name="departure_date" 
                                value="{{ request('departure_date') }}" 
                                class="bg-transparent w-full text-[#1C2522] text-xs sm:text-sm font-semibold focus:outline-none"
                            />
                        </div>
                    </div>

                    <!-- Submit & Reset -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-[#21483C] hover:bg-[#2F6252] text-white py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                            <span>Cari Jadwal</span>
                        </button>
                        @if(request()->hasAny(['origin', 'destination', 'departure_date', 'sort']))
                            <a href="{{ route('trips.index') }}" class="py-2.5 px-3 bg-[#FBFAF6] hover:bg-[#F5F1E8] text-[#1C2522] font-bold text-xs rounded-xl border border-[#D9D5CA] flex items-center justify-center transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Horizontal Date Ribbon Strip -->
    <section class="w-full bg-[#F5F1E8] border-b border-[#D9D5CA]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5">
                @php
                    $baseDate = request('departure_date') ? \Carbon\Carbon::parse(request('departure_date')) : now();
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
                        class="flex-1 min-w-[130px] px-3.5 py-2.5 rounded-xl text-left transition-all {{ $isCurrent ? 'bg-[#21483C] text-white shadow-xs' : 'bg-white hover:bg-[#FBFAF6] border border-[#D9D5CA] text-[#1C2522]' }}"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold block {{ $isCurrent ? 'text-[#D9D5CA]' : 'text-[#66716C]' }}">
                                {{ $d->translatedFormat('D, d M') }}
                            </span>
                            @if($isCurrent)
                                <span class="inline-block w-2 h-2 rounded-full bg-[#357A62]"></span>
                            @endif
                        </div>
                        <span class="text-xs sm:text-sm font-bold block mt-1 {{ $isCurrent ? 'text-white' : 'text-[#1C2522]' }}">
                            {{ $isCurrent ? 'Tanggal Dipilih' : 'Pilih Tanggal' }}
                        </span>
                        <span class="text-[10px] block mt-0.5 {{ $isCurrent ? 'text-[#D9D5CA]' : 'text-[#66716C]' }}">
                            Jadwal Keberangkatan
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Main Catalog Grid Area -->
    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT SIDEBAR: SORTING & INFO -->
            <aside class="lg:col-span-3 flex flex-col gap-4 sticky top-36">
                <div class="bg-white rounded-xl p-5 border border-[#D9D5CA] flex flex-col gap-4">
                    <div class="flex items-center justify-between pb-2 border-b border-[#D9D5CA]">
                        <span class="text-sm font-bold text-[#1C2522]">Urutkan Jadwal</span>
                        <a href="{{ route('trips.index') }}" class="text-xs text-[#21483C] hover:text-[#2F6252] font-semibold transition-colors">Reset</a>
                    </div>

                    <!-- Sorting Options -->
                    <div class="flex flex-col gap-1 text-xs">
                        <label class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-[#F5F1E8] cursor-pointer transition-colors">
                            <span class="font-medium text-[#1C2522]">Tarif Terendah</span>
                            <input 
                                type="radio" 
                                name="sortOption" 
                                value="price_asc"
                                :checked="sort === 'price_asc'"
                                @change="applySort('price_asc')"
                                class="accent-[#21483C] w-4 h-4 text-[#21483C]"
                            />
                        </label>
                        <label class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-[#F5F1E8] cursor-pointer transition-colors">
                            <span class="font-medium text-[#1C2522]">Keberangkatan Terawal</span>
                            <input 
                                type="radio" 
                                name="sortOption" 
                                value="departure_asc"
                                :checked="sort === 'departure_asc'"
                                @change="applySort('departure_asc')"
                                class="accent-[#21483C] w-4 h-4 text-[#21483C]"
                            />
                        </label>
                        <label class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-[#F5F1E8] cursor-pointer transition-colors">
                            <span class="font-medium text-[#1C2522]">Keberangkatan Terakhir</span>
                            <input 
                                type="radio" 
                                name="sortOption" 
                                value="departure_desc"
                                :checked="sort === 'departure_desc'"
                                @change="applySort('departure_desc')"
                                class="accent-[#21483C] w-4 h-4 text-[#21483C]"
                            />
                        </label>
                    </div>

                    <div class="pt-3 border-t border-[#D9D5CA] text-xs text-[#66716C] leading-relaxed">
                        <p>Harga dan ketersediaan kursi diperbarui secara langsung sesuai data sistem operasional.</p>
                    </div>
                </div>

                <!-- Bantuan Layanan Card -->
                <div class="bg-[#F5F1E8] rounded-xl p-4 border border-[#D9D5CA] flex flex-col gap-2">
                    <span class="text-xs font-bold text-[#1C2522]">Perlu Bantuan?</span>
                    <p class="text-xs text-[#66716C] leading-relaxed">Informasi mengenai tata cara pemesanan dan syarat tiket tersedia di pusat bantuan.</p>
                    <a href="{{ route('faq') }}" class="text-xs font-semibold text-[#21483C] hover:underline mt-1 inline-flex items-center gap-1">
                        <span>Pusat Bantuan &amp; FAQ</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
            </aside>

            <!-- RIGHT COLUMN: SEARCH RESULTS TIMELINE -->
            <section class="lg:col-span-9 flex flex-col gap-4">
                <!-- Header Summary Count -->
                <div class="flex flex-wrap items-center justify-between gap-2 pb-1">
                    <div class="flex items-center gap-2">
                        <h1 class="text-lg font-bold text-[#1C2522]">Pilihan Jadwal Bus</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-[#21483C]/10 text-[#21483C] font-bold border border-[#21483C]/20 text-xs">
                            {{ $trips->total() }} Perjalanan
                        </span>
                    </div>
                    <span class="text-xs text-[#66716C] font-medium">Jadwal Keberangkatan Terjadwal</span>
                </div>

                @if($trips->isEmpty())
                    <div class="text-center py-16 px-4 bg-white rounded-xl border border-[#D9D5CA]">
                        <div class="w-14 h-14 rounded-full bg-[#F5F1E8] text-[#66716C] flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-[28px]">directions_bus</span>
                        </div>
                        <h3 class="text-base font-bold text-[#1C2522]">Belum ada perjalanan yang sesuai dengan pencarian.</h3>
                        <p class="text-xs text-[#66716C] mt-1 max-w-sm mx-auto">
                            Tidak ada jadwal perjalanan yang ditemukan. Silakan coba ganti tanggal atau pilih kota asal dan tujuan yang lain untuk melihat armada PO CAN Travel yang tersedia.
                        </p>
                        <a href="{{ route('trips.index') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 bg-[#21483C] text-white text-xs font-bold rounded-xl hover:bg-[#2F6252] transition-colors">
                            <span class="material-symbols-outlined text-[16px]">refresh</span>
                            <span>Lihat Semua Jadwal</span>
                        </a>
                    </div>
                @else
                    @foreach($trips as $trip)
                        @php
                            $availableSeats = max(0, $trip->bus->total_seats - ($trip->booked_seats_count ?? 0));
                            $durationHours = floor($trip->route->duration / 60);
                            $durationMinutes = $trip->route->duration % 60;
                            $isNextDay = $trip->arrival_at->day > $trip->departure_at->day;
                        @endphp

                        <article class="bg-white rounded-xl p-5 sm:p-6 border border-[#D9D5CA] hover:border-[#21483C] transition-colors flex flex-col gap-4">
                            <!-- Card Header: Bus Info -->
                            <div class="flex flex-wrap items-center justify-between gap-2 pb-2.5 border-b border-[#D9D5CA]">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-md bg-[#F5F1E8] text-[#1C2522] text-xs font-bold border border-[#D9D5CA]">
                                        {{ $trip->bus->name }}
                                    </span>
                                    <span class="font-mono text-[#66716C] text-xs font-semibold">
                                        {{ $trip->bus->code }}
                                    </span>
                                </div>
                                <div class="text-xs text-[#66716C] font-medium">
                                    Kapasitas {{ $trip->bus->total_seats }} Kursi
                                </div>
                            </div>

                            <!-- Timeline Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <!-- Origin Departure -->
                                <div class="md:col-span-3 flex flex-col">
                                    <span class="text-2xl sm:text-3xl font-extrabold text-[#1C2522] tracking-tight leading-tight">
                                        {{ $trip->departure_at->format('H:i') }}
                                    </span>
                                    <span class="text-sm font-bold text-[#1C2522] mt-1">
                                        {{ $trip->route->origin }}
                                    </span>
                                    <span class="text-xs text-[#66716C]">
                                        Titik Keberangkatan
                                    </span>
                                </div>

                                <!-- Progress Track -->
                                <div class="md:col-span-6 flex flex-col items-center justify-center px-2">
                                    <div class="flex items-center justify-between w-full text-[#66716C] text-xs mb-1.5">
                                        <span class="text-[#66716C] font-medium text-xs">
                                            Estimasi Perjalanan
                                        </span>
                                        <span class="font-bold text-[#1C2522] text-xs">
                                            {{ $durationHours }}j {{ $durationMinutes > 0 ? $durationMinutes . 'm' : '' }}
                                        </span>
                                    </div>

                                    <div class="relative w-full flex items-center my-1">
                                        <div class="w-2.5 h-2.5 rounded-full bg-[#1C2522] shrink-0"></div>
                                        <div class="flex-1 h-[2px] bg-[#D9D5CA]"></div>
                                        <div class="w-2 h-2 rounded-full bg-[#21483C] shrink-0"></div>
                                        <div class="flex-1 h-[2px] bg-[#D9D5CA]"></div>
                                        <div class="w-2.5 h-2.5 rounded-full bg-[#B96545] shrink-0"></div>
                                    </div>

                                    <span class="text-[10px] text-[#66716C] mt-1 text-center font-medium">
                                        Jalur antarkota langsung
                                    </span>
                                </div>

                                <!-- Destination Arrival -->
                                <div class="md:col-span-3 flex flex-col md:text-right">
                                    <div class="flex items-center md:justify-end gap-1.5">
                                        <span class="text-2xl sm:text-3xl font-extrabold text-[#1C2522] tracking-tight leading-tight">
                                            {{ $trip->arrival_at->format('H:i') }}
                                        </span>
                                        @if($isNextDay)
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-[#B96545]/10 text-[#B96545] font-bold border border-[#B96545]/20">
                                                +1 Hari
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-[#1C2522] mt-1">
                                        {{ $trip->route->destination }}
                                    </span>
                                    <span class="text-xs text-[#66716C]">
                                        Titik Kedatangan
                                    </span>
                                </div>
                            </div>

                            <!-- Amenities Strip from Database -->
                            <div class="flex flex-wrap items-center justify-between gap-2 bg-[#FBFAF6] p-2.5 sm:p-3 rounded-xl border border-[#D9D5CA]">
                                <div class="flex flex-wrap items-center gap-1.5 text-xs text-[#66716C]">
                                    <span class="font-bold text-[#1C2522] bg-white px-2 py-0.5 rounded border border-[#D9D5CA] text-xs">
                                        {{ $trip->bus->bus_type ?? 'Bus Antarkota' }}
                                    </span>
                                    @if($trip->bus->facilities && $trip->bus->facilities->isNotEmpty())
                                        @foreach($trip->bus->facilities->take(3) as $fac)
                                            <span class="inline-flex items-center gap-1 bg-white px-2 py-0.5 rounded border border-[#D9D5CA] text-xs text-[#1C2522]">
                                                <span class="material-symbols-outlined text-[13px] text-[#21483C]">check</span> {{ $fac->name }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>

                                <a href="{{ route('trips.show', $trip) }}" class="text-[#21483C] font-semibold text-xs hover:underline flex items-center gap-0.5">
                                    <span>Detail Perjalanan</span>
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </a>
                            </div>

                            <!-- Price & CTA -->
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-1">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-xs font-bold {{ $availableSeats > 5 ? 'text-[#357A62] bg-[#357A62]/10 border-[#357A62]/20' : 'text-[#B96545] bg-[#B96545]/10 border-[#B96545]/20' }} border px-2.5 py-1 rounded-full">
                                        Sisa {{ $availableSeats }} kursi
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 sm:gap-4">
                                    <div class="flex flex-col text-right">
                                        <span class="text-[10px] text-[#66716C]">Tarif per penumpang</span>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-xl sm:text-2xl text-[#1C2522] font-black">
                                                Rp{{ number_format($trip->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    @auth
                                        @if(auth()->user()->role === 'customer')
                                            <a 
                                                href="{{ route('customer.trips.seats', $trip) }}" 
                                                class="px-5 py-2.5 rounded-xl bg-[#21483C] hover:bg-[#2F6252] text-white font-bold text-xs sm:text-sm transition-colors flex items-center gap-1.5"
                                            >
                                                <span>Pilih Kursi</span>
                                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                            </a>
                                        @else
                                            <a 
                                                href="{{ route('trips.show', $trip) }}" 
                                                class="px-5 py-2.5 rounded-xl bg-[#21483C] hover:bg-[#2F6252] text-white font-bold text-xs sm:text-sm transition-colors flex items-center gap-1.5"
                                            >
                                                <span>Detail Jadwal</span>
                                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                            </a>
                                        @endif
                                    @else
                                        <a 
                                            href="{{ route('login') }}" 
                                            class="px-5 py-2.5 rounded-xl bg-[#21483C] hover:bg-[#2F6252] text-white font-bold text-xs sm:text-sm transition-colors flex items-center gap-1.5"
                                        >
                                            <span>Pilih Kursi</span>
                                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $trips->links() }}
                    </div>
                @endif

                <!-- Travel Information Guidance -->
                <div class="bg-[#F5F1E8] rounded-xl p-4 sm:p-5 border border-[#D9D5CA] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#21483C] text-white flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">info</span>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-[#1C2522]">Informasi Keberangkatan PO CAN Travel</h4>
                            <p class="text-xs text-[#66716C] mt-0.5">Harap hadir di titik keberangkatan selambat-lambatnya 30 menit sebelum jadwal keberangkatan bus.</p>
                        </div>
                    </div>
                    <a href="{{ route('how-to-order') }}" class="shrink-0 px-4 py-2 rounded-xl bg-white hover:bg-[#FBFAF6] border border-[#D9D5CA] text-[#1C2522] text-xs font-bold transition-colors">
                        Panduan Perjalanan
                    </a>
                </div>
            </section>

        </div>
    </main>
</div>
@endsection

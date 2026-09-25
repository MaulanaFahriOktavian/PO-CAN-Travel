@extends('layouts.app')

@section('title', 'PO CAN Travel — Platform Pemesanan Tiket Bus Antarkota')
@section('meta_description', 'Pesan tiket bus antarkota resmi PO CAN Travel. Cari rute, pilih jadwal, tentukan nomor kursi mandiri di denah kabin bus, dan pesan tiket langsung.')

@section('content')
<div x-data="{
    origin: '{{ request('origin', '') }}',
    destination: '{{ request('destination', '') }}',
    departureDate: '{{ request('departure_date', '') }}',
    passengers: '1',
    activeTab: 'all',
    errorMessage: '',
    swap() {
        let temp = this.origin;
        this.origin = this.destination;
        this.destination = temp;
    },
    selectRoute(from, to) {
        this.origin = from;
        this.destination = to;
        this.errorMessage = '';
        let searchEl = document.getElementById('search-booking-bar');
        if (searchEl) {
            searchEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            let dateEl = document.getElementById('departure_date_input');
            if (dateEl) dateEl.focus();
        }
    },
    validateSearch(e) {
        if (this.origin && this.destination && this.origin === this.destination) {
            e.preventDefault();
            this.errorMessage = 'Kota tujuan tidak boleh sama dengan kota asal.';
            return false;
        }
        this.errorMessage = '';
        return true;
    }
}" class="w-full bg-[#FAFBFD]">

    <!-- ═════════════════════════════════════════════════════════════════
         SECTION 1: HERO (TWO-COLUMN WITH FLOATING PILL SEARCH & PHOTO COLLAGE)
         Inspired by modern youthful travel design (Warm Orange & Sky Blue)
         ═════════════════════════════════════════════════════════════════ -->
    <section class="relative pt-10 sm:pt-14 lg:pt-16 pb-16 sm:pb-24 overflow-hidden bg-gradient-to-b from-orange-50/40 via-white to-[#FAFBFD] border-b border-slate-100">
        <!-- Ambient Warm & Sky Glow Elements -->
        <div class="absolute top-0 right-1/4 w-[600px] h-[350px] bg-gradient-to-br from-orange-200/20 via-amber-100/20 to-sky-100/20 blur-3xl pointer-events-none -z-10 rounded-full"></div>
        <div class="absolute -top-12 left-10 w-[350px] h-[350px] bg-sky-200/15 blur-3xl pointer-events-none -z-10 rounded-full"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <!-- Left Column: Copy & Floating Pill Booking Bar -->
                <div class="lg:col-span-7 flex flex-col items-start text-left">

                    <!-- Pill Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-orange-200/80 text-xs font-semibold text-slate-700 shadow-2xs mb-5">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                        <span class="text-sky-600 font-bold">Explore. Dream. Discover.</span>
                        <span class="text-slate-300">&bull;</span>
                        <span class="font-heading font-bold text-slate-800">PO CAN TRAVEL</span>
                    </div>

                    <!-- Giant Bold Headline -->
                    <h1 class="font-heading font-extrabold text-3xl sm:text-5xl lg:text-[54px] text-slate-900 tracking-tight leading-[1.12]">
                        Temukan Perjalanan Terbaik &amp; Nyaman Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">PO CAN Travel</span>
                    </h1>

                    <!-- Subtitle (Preserving Test Assertion: 'Perjalanan antarkota, lebih mudah dipesan.') -->
                    <p class="mt-4 text-sm sm:text-base lg:text-lg text-slate-600 leading-relaxed font-normal max-w-xl">
                        Perjalanan antarkota, lebih mudah dipesan. Dapatkan kepastian nomor kursi real-time dari denah kabin bus, armada eksekutif berpendingin AC, dan tarif resmi tanpa biaya siluman.
                    </p>

                    <!-- ═════════════════════════════════════════════════════
                         FLOATING PILL SEARCH & BOOKING BAR
                         ═════════════════════════════════════════════════════ -->
                    <div id="search-booking-bar" class="w-full mt-8 sm:mt-10">
                        <!-- Validation Alert Banner -->
                        <div x-show="errorMessage" x-cloak class="mb-3 p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex items-center justify-between gap-3 shadow-xs transition-all">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span x-text="errorMessage" class="font-heading font-semibold"></span>
                            </div>
                            <button type="button" @click="errorMessage = ''" class="text-amber-600 hover:text-amber-800 text-sm font-bold leading-none p-1 cursor-pointer">&times;</button>
                        </div>
                        @if($errors->any())
                            <div class="mb-3 p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm flex items-center gap-2.5 shadow-xs">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span class="font-heading font-semibold">{{ $errors->first() }}</span>
                            </div>
                        @endif

                        <form
                            action="{{ route('trips.index') }}"
                            method="GET"
                            @submit="validateSearch($event)"
                            class="bg-white rounded-3xl lg:rounded-full p-3 sm:p-4 border border-slate-200/90 shadow-[0_16px_40px_-10px_rgba(249,115,22,0.12)] flex flex-col lg:flex-row items-stretch lg:items-center gap-3"
                        >
                            <!-- Kota Asal (Where to start?) -->
                            <div class="flex-1 min-w-[150px] flex items-center gap-3 px-4 py-2 bg-slate-50/70 hover:bg-slate-50 rounded-2xl lg:rounded-full border border-slate-100 transition-colors">
                                <div class="w-9 h-9 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label for="origin_select" class="block text-[10px] uppercase font-bold text-slate-400 font-heading">Dari Mana?</label>
                                    <select
                                        id="origin_select"
                                        name="origin"
                                        x-model="origin"
                                        class="w-full bg-transparent text-xs sm:text-sm font-heading font-bold text-slate-800 focus:outline-none cursor-pointer py-0.5"
                                    >
                                        <option value="">Pilih Kota Asal</option>
                                        @foreach($origins as $org)
                                            <option value="{{ $org }}">{{ $org }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Swap Button -->
                            <button
                                type="button"
                                @click="swap()"
                                class="hidden lg:flex w-9 h-9 rounded-full bg-slate-100 hover:bg-orange-100 text-slate-500 hover:text-orange-600 items-center justify-center transition-all shrink-0 cursor-pointer"
                                aria-label="Tukar Asal dan Tujuan"
                                title="Tukar Kota Asal & Tujuan"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </button>

                            <!-- Kota Tujuan (Where to?) -->
                            <div class="flex-1 min-w-[150px] flex items-center gap-3 px-4 py-2 bg-slate-50/70 hover:bg-slate-50 rounded-2xl lg:rounded-full border border-slate-100 transition-colors">
                                <div class="w-9 h-9 rounded-full bg-sky-50 text-sky-500 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label for="destination_select" class="block text-[10px] uppercase font-bold text-slate-400 font-heading">Mau ke Mana?</label>
                                    <select
                                        id="destination_select"
                                        name="destination"
                                        x-model="destination"
                                        class="w-full bg-transparent text-xs sm:text-sm font-heading font-bold text-slate-800 focus:outline-none cursor-pointer py-0.5"
                                    >
                                        <option value="">Pilih Kota Tujuan</option>
                                        @foreach($destinations as $dest)
                                            <option value="{{ $dest }}">{{ $dest }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Tanggal Berangkat (When?) -->
                            <div class="flex-1 min-w-[140px] flex items-center gap-3 px-4 py-2 bg-slate-50/70 hover:bg-slate-50 rounded-2xl lg:rounded-full border border-slate-100 transition-colors">
                                <div class="w-9 h-9 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label for="departure_date_input" class="block text-[10px] uppercase font-bold text-slate-400 font-heading">Tanggal</label>
                                    <input
                                        type="date"
                                        id="departure_date_input"
                                        name="departure_date"
                                        x-model="departureDate"
                                        min="{{ date('Y-m-d') }}"
                                        class="w-full bg-transparent text-xs sm:text-sm font-heading font-bold text-slate-800 focus:outline-none cursor-pointer"
                                    >
                                </div>
                            </div>

                            <!-- Explore Button -->
                            <button
                                type="submit"
                                class="px-7 py-3.5 rounded-2xl lg:rounded-full bg-gradient-to-r from-orange-500 via-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 shrink-0 cursor-pointer"
                            >
                                <span>Cari Tiket</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </form>

                        <!-- Quick Route Suggestion Pills -->
                        <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                            <span class="text-slate-400 font-medium">Paling Banyak Dicari:</span>
                            @foreach($routes->take(3) as $r)
                                <button
                                    type="button"
                                    @click="selectRoute('{{ $r->origin }}', '{{ $r->destination }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white hover:bg-orange-50 text-slate-700 hover:text-orange-600 border border-slate-200 hover:border-orange-200 transition-all font-medium cursor-pointer shadow-2xs"
                                >
                                    <span>{{ $r->origin }} &rarr; {{ $r->destination }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Right Column: Staggered Rounded Pill Collage -->
                <div class="lg:col-span-5 relative">
                    <div class="relative grid grid-cols-2 gap-4 max-w-md mx-auto">
                        <!-- Card 1: Top Left Capsule -->
                        <div class="relative rounded-3xl overflow-hidden shadow-lg border-2 border-white aspect-[3/4] group">
                            <img
                                src="{{ asset('images/destinations/jepara-thumb.jpg') }}"
                                alt="Destinasi Jepara"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex flex-col justify-end p-4 text-white">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-orange-300">Rute Populer</span>
                                <span class="text-sm font-heading font-black">Pantai Kartini, Jepara</span>
                            </div>
                        </div>

                        <!-- Card 2: Top Right Capsule (Shifted Down) -->
                        <div class="relative rounded-3xl overflow-hidden shadow-lg border-2 border-white aspect-[3/4] mt-8 group">
                            <img
                                src="{{ asset('images/hero-bus.jpg') }}"
                                alt="Armada Bus PO CAN Travel"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex flex-col justify-end p-4 text-white">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-sky-300">Executive Class</span>
                                <span class="text-sm font-heading font-black">Kenyamanan Suspensi Udara</span>
                            </div>
                        </div>

                        <!-- Floating Review Badge -->
                        <div class="absolute -bottom-4 -left-4 sm:left-4 z-20 bg-white/95 backdrop-blur-md p-3.5 rounded-2xl border border-slate-100 shadow-xl flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-400 to-amber-400 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                                ★
                            </div>
                            <div>
                                <div class="flex items-center gap-1 text-amber-500 text-xs">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                    <span class="font-bold text-slate-800 ml-1 text-xs">4.9/5</span>
                                </div>
                                <span class="text-[11px] text-slate-500 font-medium block">10.000+ Penumpang Puas</span>
                            </div>
                        </div>

                        <!-- Floating Guaranteed Seat Badge -->
                        <div class="absolute top-4 -right-4 z-20 bg-white/95 backdrop-blur-md py-2 px-3.5 rounded-full border border-slate-100 shadow-md flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-heading font-bold text-slate-800">Kursi Pasti Real-Time</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ═════════════════════════════════════════════════════════════════
         SECTION 2: 4 VALUE PROPOSITION HIGHLIGHT CARDS
         Best Price Guarantee, 24/7 Support, Easy Booking, Trusted & Safe
         ═════════════════════════════════════════════════════════════════ -->
    <section class="py-10 -mt-6 sm:-mt-8 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-[0_10px_30px_-5px_rgba(0,0,0,0.04)]">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- Feature 1 -->
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 border border-orange-100/80">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-bold text-slate-900">Jaminan Tarif Resmi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Harga transparan tanpa biaya perantara.</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center shrink-0 border border-sky-100/80">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-bold text-slate-900">Layanan Siaga 24/7</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Kru agen &amp; bantuan siap mendampingi.</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0 border border-amber-100/80">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 11l1-5h12l1 5M5 11v8h14v-8M5 11h14M8 19v2m8-2v2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-bold text-slate-900">Pilih Kursi Mandiri</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pilih nomor kursi favorit dari denah bus.</p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 border border-emerald-100/80">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-heading font-bold text-slate-900">Armada Terpercaya</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Bus prima dengan uji kelaikan rutin.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- ═════════════════════════════════════════════════════════════════
         SECTION 3: "WE RECOMMEND BEAUTIFUL DESTINATIONS"
         Staggered photos on left, editorial copy & stats on right
         ═════════════════════════════════════════════════════════════════ -->
    <section class="py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left: Artistic Rounded Pill Photo Collage -->
                <div class="lg:col-span-6 relative">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-3xl overflow-hidden shadow-md aspect-[4/5] border border-slate-100">
                            <img
                                src="{{ asset('images/about/fleet-comfort.jpg') }}"
                                alt="Kabin Eksekutif"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                        <div class="space-y-4">
                            <div class="rounded-3xl overflow-hidden shadow-md aspect-[4/3] border border-slate-100">
                                <img
                                    src="{{ asset('images/destinations/semarang-thumb.jpg') }}"
                                    alt="Kota Semarang"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                >
                            </div>
                            <div class="rounded-3xl overflow-hidden shadow-md aspect-[4/3] border border-slate-100">
                                <img
                                    src="{{ asset('images/bus_interior_dusk.jpg') }}"
                                    alt="Interior Nyaman"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Editorial Content & 3 Stat Pills -->
                <div class="lg:col-span-6 flex flex-col items-start">
                    <span class="text-xs font-heading font-bold uppercase tracking-wider text-orange-500 mb-2">
                        Handpicked For You
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-heading font-black text-slate-900 tracking-tight leading-tight">
                        Rute Perjalanan Pilihan dengan Kenyamanan Maksimal
                    </h2>
                    <p class="mt-4 text-sm sm:text-base text-slate-600 leading-relaxed font-normal">
                        Kami menghubungkan kota-kota strategis di Pulau Jawa dengan layanan armada eksekutif berstandar tinggi. Nikmati kursi ergonomis, pendingin udara merata, dan pengemudi berpengalaman yang memastikan Anda tiba dengan selamat.
                    </p>

                    <!-- 3 Pill Stat Cards -->
                    <div class="grid grid-cols-3 gap-3 sm:gap-4 w-full mt-8">
                        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs text-center">
                            <span class="text-xl sm:text-2xl font-heading font-black text-orange-500 block">2.5K+</span>
                            <span class="text-[11px] sm:text-xs text-slate-500 font-medium">Jadwal Sukses</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs text-center">
                            <span class="text-xl sm:text-2xl font-heading font-black text-slate-900 block">10K+</span>
                            <span class="text-[11px] sm:text-xs text-slate-500 font-medium">Pelanggan Puas</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs text-center">
                            <span class="text-xl sm:text-2xl font-heading font-black text-sky-500 block">99%</span>
                            <span class="text-[11px] sm:text-xs text-slate-500 font-medium">Tepat Waktu</span>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <a
                            href="{{ route('about') }}"
                            class="px-6 py-3 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-heading font-bold text-xs sm:text-sm transition-all shadow-xs"
                        >
                            Tentang PO CAN Travel
                        </a>
                        <a
                            href="{{ route('facilities.index') }}"
                            class="px-6 py-3 rounded-full bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-heading font-bold text-xs sm:text-sm transition-all"
                        >
                            Lihat Fasilitas Bus
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ═════════════════════════════════════════════════════════════════
         SECTION 4: "LET'S EXPLORE YOUR DREAM DESTINATION HERE!"
         Pill filter tabs + Route & Trip Cards with orange prices
         ═════════════════════════════════════════════════════════════════ -->
    <section class="py-14 sm:py-20 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Title -->
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-heading font-bold uppercase tracking-wider text-orange-500 block mb-2">
                    Top Destinations
                </span>
                <h2 class="text-2xl sm:text-4xl font-heading font-black text-slate-900 tracking-tight">
                    Pilih Destinasi &amp; Rute Perjalanan Impian Anda
                </h2>
                <p class="mt-2 text-xs sm:text-sm text-slate-500">
                    Jadwal perjalanan reguler aktif yang siap Anda pesan dengan harga resmi transparan.
                </p>

                <!-- Pill Category Filter Tabs -->
                <div class="mt-6 flex flex-wrap items-center justify-center gap-2">
                    <button
                        type="button"
                        @click="activeTab = 'all'"
                        :class="activeTab === 'all' ? 'bg-orange-500 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
                        class="px-4 py-2 rounded-full text-xs font-heading font-bold transition-all cursor-pointer"
                    >
                        Semua Rute
                    </button>
                    @foreach($routes->take(4) as $tabRoute)
                        <button
                            type="button"
                            @click="activeTab = '{{ Str::slug($tabRoute->origin . '-' . $tabRoute->destination) }}'"
                            :class="activeTab === '{{ Str::slug($tabRoute->origin . '-' . $tabRoute->destination) }}' ? 'bg-orange-500 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
                            class="px-4 py-2 rounded-full text-xs font-heading font-bold transition-all cursor-pointer"
                        >
                            {{ $tabRoute->origin }} - {{ $tabRoute->destination }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Grid of Route Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($routes as $route)
                    @php
                        $thumbMap = [
                            'jakarta' => 'destinations/jakarta-thumb.jpg',
                            'jepara' => 'destinations/jepara-thumb.jpg',
                            'semarang' => 'destinations/semarang-thumb.jpg',
                            'bandung' => 'destinations/bandung-thumb.jpg',
                            'yogyakarta' => 'destinations/yogyakarta-thumb.jpg',
                        ];
                        $destKey = strtolower($route->destination);
                        $thumbPath = $thumbMap[$destKey] ?? 'destinations/jepara-thumb.jpg';
                        $minPrice = $route->trips_min_price ?? 250000;
                        $slug = Str::slug($route->origin . '-' . $route->destination);
                    @endphp
                    <div
                        x-show="activeTab === 'all' || activeTab === '{{ $slug }}'"
                        class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-2xs hover:shadow-lg transition-all duration-300 flex flex-col group"
                    >
                        <!-- Card Photo with Badge -->
                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                            <img
                                src="{{ asset('images/' . $thumbPath) }}"
                                alt="{{ $route->destination }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-md px-2.5 py-1 rounded-full text-[10px] font-heading font-bold text-slate-800 shadow-xs">
                                {{ floor($route->duration / 60) }}j {{ $route->duration % 60 > 0 ? ($route->duration % 60).'m' : '' }}
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 mb-1">
                                    <span>{{ $route->origin }}</span>
                                    <span>&rarr;</span>
                                    <span>{{ $route->destination }}</span>
                                </div>
                                <h3 class="font-heading font-bold text-base text-slate-900 group-hover:text-orange-600 transition-colors">
                                    {{ $route->destination }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-1">
                                    {{ $route->trips_count }} jadwal keberangkatan aktif
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Tarif Mulai</span>
                                    <span class="text-sm sm:text-base font-heading font-extrabold text-orange-600 tabular-nums">
                                        Rp{{ number_format($minPrice, 0, ',', '.') }}
                                    </span>
                                </div>
                                <a
                                    href="{{ route('trips.index', ['origin' => $route->origin, 'destination' => $route->destination]) }}"
                                    class="px-3.5 py-2 rounded-full bg-orange-50 hover:bg-orange-500 text-orange-600 hover:text-white font-heading font-bold text-xs transition-all shadow-2xs"
                                >
                                    Pesan Kursi
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 p-8 text-center bg-slate-50 rounded-2xl text-slate-500 text-sm">
                        Belum ada data rute yang terdaftar.
                    </div>
                @endforelse
            </div>

            <!-- View All Button -->
            <div class="mt-12 text-center">
                <a
                    href="{{ route('trips.index') }}"
                    class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-sm shadow-md hover:shadow-lg transition-all"
                >
                    <span>Lihat Semua Jadwal &amp; Rute</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

        </div>
    </section>

    <!-- ═════════════════════════════════════════════════════════════════
         SECTION 5: "WHAT OUR CUSTOMERS SAY ABOUT US"
         Testimonial card on left, photo mosaic of trips on right
         ═════════════════════════════════════════════════════════════════ -->
    <section class="py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

                <!-- Left: Review Card -->
                <div class="lg:col-span-5 flex flex-col items-start">
                    <span class="text-xs font-heading font-bold uppercase tracking-wider text-orange-500 mb-2">
                        Testimoni Penumpang
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-heading font-black text-slate-900 tracking-tight leading-tight">
                        Pengalaman Nyata Bersama PO CAN Travel
                    </h2>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed font-normal">
                        Ribuan penumpang telah mempercayakan perjalanan jarak jauh mereka kepada kami setiap bulan.
                    </p>

                    <!-- Card Testimonial -->
                    <div class="mt-6 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/90 shadow-md w-full relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-orange-400 to-amber-400 text-white font-bold flex items-center justify-center text-base shrink-0 shadow-xs">
                                AS
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-sm text-slate-900">Ahmad Subagyo</h3>
                                <p class="text-xs text-slate-400">Penumpang Rute Jakarta &rarr; Jepara</p>
                            </div>
                            <div class="ml-auto flex items-center gap-0.5 text-amber-500 text-xs">
                                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                <span class="font-bold text-slate-800 ml-1 text-xs">5.0</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed italic">
                            &ldquo;Pemesanan tiket lewat web PO CAN Travel sangat mudah dan transparan. Bisa pilih nomor kursi langsung di layar tanpa ribet telepon agen. Bus berangkat tepat waktu, kursinya luas dan AC nya dingin!&rdquo;
                        </p>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span>Tiket Terverifikasi &bull; Penumpang Eksekutif</span>
                            <span class="text-emerald-600 font-bold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Verified Order
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right: Photo Mosaic of Travel Moments -->
                <div class="lg:col-span-7">
                    <div class="grid grid-cols-3 gap-3.5">
                        <div class="rounded-3xl overflow-hidden aspect-[3/4] shadow-md border border-slate-100">
                            <img src="{{ asset('images/destinations/bandung-thumb.jpg') }}" alt="Perjalanan Bandung" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="rounded-3xl overflow-hidden aspect-[3/4] shadow-md border border-slate-100 mt-6">
                            <img src="{{ asset('images/travel/booking-guide.jpg') }}" alt="Kenyamanan Penumpang" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="rounded-3xl overflow-hidden aspect-[3/4] shadow-md border border-slate-100">
                            <img src="{{ asset('images/destinations/yogyakarta-thumb.jpg') }}" alt="Destinasi Yogyakarta" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ═════════════════════════════════════════════════════════════════
         SECTION 6: PROMO & DISCOUNT BANNER
         Soft Peach / Warm Orange Gradient Card with Discount Badge
         ═════════════════════════════════════════════════════════════════ -->
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-orange-50 via-amber-50 to-orange-100/60 rounded-3xl p-8 sm:p-12 border border-orange-200/80 shadow-md relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
                <!-- Ambient Deco Circles -->
                <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-orange-400/10 blur-xl pointer-events-none"></div>

                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-gradient-to-tr from-orange-500 to-amber-500 text-white flex items-center justify-center font-heading font-black text-2xl sm:text-3xl shrink-0 shadow-lg shadow-orange-500/20">
                        %
                    </div>
                    <div>
                        <span class="text-xs font-heading font-bold uppercase tracking-wider text-orange-600 block mb-1">
                            PENAWARAN SPESIAL PERJALANAN
                        </span>
                        <h2 class="text-xl sm:text-3xl font-heading font-black text-slate-900 tracking-tight">
                            Pesan Lebih Awal &amp; Amankan Kursi Favorit Anda!
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-xl">
                            Dapatkan kenyamanan kelas eksekutif dengan harga terbaik tanpa antrean terminal.
                        </p>
                    </div>
                </div>

                <div class="shrink-0">
                    <a
                        href="{{ route('trips.index') }}"
                        class="px-8 py-3.5 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-sm shadow-md hover:shadow-lg transition-all inline-flex items-center gap-2"
                    >
                        <span>Cari Tiket Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═════════════════════════════════════════════════════════════════
         SECTION 7: PANDUAN CEPAT (CARA MEMESAN & FAQ)
         Preserves required test strings: 'Cara Memesan Tiket' and 'Pertanyaan Umum'
         ═════════════════════════════════════════════════════════════════ -->
    <section class="py-12 sm:py-16 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Card 1: Cara Memesan Tiket -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center font-bold text-sm mb-4">
                            01
                        </div>
                        <h3 class="text-base font-heading font-bold text-slate-900">
                            Cara Memesan Tiket
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Panduan 4 langkah mudah mulai dari memilih rute, menentukan nomor kursi di kabin, hingga konfirmasi pembayaran.
                        </p>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100">
                        <a href="{{ route('how-to-order') }}" class="text-xs font-heading font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                            <span>Baca Panduan Lengkap</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Pertanyaan Umum -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center font-bold text-sm mb-4">
                            02
                        </div>
                        <h3 class="text-base font-heading font-bold text-slate-900">
                            Pertanyaan Umum
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Temukan jawaban faktual mengenai aturan bagasi, verifikasi e-tiket saat boarding, dan jadwal keberangkatan bus.
                        </p>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100">
                        <a href="{{ route('faq') }}" class="text-xs font-heading font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                            <span>Buka Halaman FAQ</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Informasi Hari Keberangkatan -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-sm mb-4">
                            03
                        </div>
                        <h3 class="text-base font-heading font-bold text-slate-900">
                            Informasi Keberangkatan
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Ketahui ketentuan waktu tiba di pool/terminal (minimal 30 menit sebelum berangkat) dan dokumen identitas yang wajib dibawa.
                        </p>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100">
                        <a href="{{ route('departure-info') }}" class="text-xs font-heading font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                            <span>Lihat Prosedur Boarding</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Perjalanan ' . $trip->route->origin . ' ke ' . $trip->route->destination . ' — PO CAN Travel')
@section('meta_description', 'Detail jadwal keberangkatan bus PO CAN Travel rute ' . $trip->route->origin . ' ke ' . $trip->route->destination . '. Jam berangkat ' . $trip->departure_at->format('H:i') . ', armada ' . $trip->bus->name . ', tarif Rp' . number_format($trip->price, 0, ',', '.') . '.')

@section('content')
<div class="min-h-screen bg-[#FAFBFD]">

    <!-- ═════════════════════════════════════════════════════════════════
         01. HERO / BREADCRUMB & HEADER
         ═════════════════════════════════════════════════════════════════ -->
    <header class="bg-gradient-to-b from-orange-50/40 via-white to-slate-50 text-slate-900 py-8 lg:py-10 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-4" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-orange-600 transition-colors">Beranda</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('trips.index') }}" class="hover:text-orange-600 transition-colors">Perjalanan</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700 font-medium truncate">{{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 border border-orange-200/80 text-orange-600 text-xs font-semibold uppercase tracking-wider mb-2.5">
                        <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Jadwal Resmi Operasional</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-black tracking-tight text-slate-900 flex items-center gap-3">
                        <span>{{ $trip->route->origin }}</span>
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-orange-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <span>{{ $trip->route->destination }}</span>
                    </h1>

                    <p class="text-xs sm:text-sm text-slate-500 mt-1.5 flex items-center gap-2">
                        <span>Tanggal Keberangkatan:</span>
                        <strong class="text-slate-900 font-heading font-bold">{{ $trip->departure_at->translatedFormat('l, d F Y') }}</strong>
                    </p>
                </div>

                <div class="flex flex-col sm:items-end bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-xs">
                    <span class="text-xs text-slate-500 font-medium block">Tarif per Penumpang</span>
                    <div class="text-2xl sm:text-3xl font-heading font-black text-orange-600 tracking-tight mt-0.5">
                        Rp{{ number_format($trip->price, 0, ',', '.') }}
                    </div>
                    <span class="text-[11px] text-slate-400 mt-0.5">Sudah termasuk nomor kursi pasti</span>
                </div>
            </div>
        </div>
    </header>

    <!-- ═════════════════════════════════════════════════════════════════
         02. MAIN CONTENT
         ═════════════════════════════════════════════════════════════════ -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">
        
        <!-- SECTION 1: TIMELINE PERJALANAN -->
        <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-heading font-bold text-slate-900">
                        Rangkaian Waktu Perjalanan
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Estimasi jadwal keberangkatan dan kedatangan bus antarkota langsung</p>
                </div>
                <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-orange-50 text-orange-600 border border-orange-200/80">
                    <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Waktu Indonesia Barat (WIB)</span>
                </span>
            </div>

            @php
                $hours = floor($trip->route->duration / 60);
                $minutes = $trip->route->duration % 60;
                $isNextDay = $trip->arrival_at->day > $trip->departure_at->day;
            @endphp

            <div class="p-6 sm:p-8 rounded-2xl bg-[#FAFBFD] border border-slate-200/80 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-6">
                <!-- Keberangkatan -->
                <div class="flex items-start sm:items-center gap-4 text-left w-full md:w-auto">
                    <div class="w-14 h-14 rounded-2xl bg-orange-500 text-white flex flex-col items-center justify-center shrink-0 shadow-sm shadow-orange-500/20">
                        <span class="text-white/80 uppercase font-bold tracking-wider text-[10px]">WIB</span>
                        <strong class="font-heading font-black text-base">{{ $trip->departure_at->format('H:i') }}</strong>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold text-orange-600 uppercase tracking-wider block">TITIK KEBERANGKATAN</span>
                        <strong class="text-lg font-heading font-bold text-slate-900 block">{{ $trip->route->origin }}</strong>
                        <span class="text-xs text-slate-500 block mt-0.5">Terminal {{ $trip->route->origin }}</span>
                        <span class="text-[11px] text-slate-400 block mt-0.5">{{ $trip->departure_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>

                <!-- Connector Durasi -->
                <div class="flex-1 w-full flex flex-col items-center justify-center px-4 py-2 md:py-0">
                    <div class="inline-flex items-center gap-1.5 text-xs font-heading font-bold text-slate-800 bg-white px-3.5 py-1.5 rounded-full border border-slate-200/80 shadow-xs mb-2">
                        <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Estimasi {{ $hours }} Jam {{ $minutes > 0 ? $minutes . ' Menit' : '' }}</span>
                    </div>
                    <div class="w-full flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-orange-500 shrink-0"></div>
                        <div class="h-1 flex-1 bg-gradient-to-r from-orange-500 via-amber-400 to-sky-500 rounded-full"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-sky-500 shrink-0"></div>
                    </div>
                    <span class="text-[10px] text-slate-500 mt-1.5 text-center font-medium">Jalur Antarkota Bebas Hambatan / Tol Trans Jawa</span>
                </div>

                <!-- Kedatangan -->
                <div class="flex items-start sm:items-center gap-4 text-left md:text-right w-full md:w-auto md:flex-row-reverse">
                    <div class="w-14 h-14 rounded-2xl bg-sky-500 text-white flex flex-col items-center justify-center shrink-0 shadow-sm shadow-sky-500/20">
                        <span class="text-white/80 uppercase font-bold tracking-wider text-[10px]">WIB</span>
                        <strong class="font-heading font-black text-base">{{ $trip->arrival_at->format('H:i') }}</strong>
                    </div>
                    <div>
                        <div class="flex items-center md:justify-end gap-1.5">
                            <span class="text-[11px] font-bold text-sky-600 uppercase tracking-wider block">ESTIMASI TIBA</span>
                            @if($isNextDay)
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                    +1 Hari
                                </span>
                            @endif
                        </div>
                        <strong class="text-lg font-heading font-bold text-slate-900 block">{{ $trip->route->destination }}</strong>
                        <span class="text-xs text-slate-500 block mt-0.5">Terminal {{ $trip->route->destination }}</span>
                        <span class="text-[11px] text-slate-400 block mt-0.5">{{ $trip->arrival_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 2: ARMADA & FASILITAS -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Profil Armada Bus -->
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-heading font-bold text-slate-900">
                            Armada Bus Bertugas
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Spesifikasi unit dan konfigurasi kabin</p>
                    </div>
                    <a href="{{ route('buses.show', $trip->bus) }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700 inline-flex items-center gap-1">
                        <span>Profil Bus</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <div class="flex items-start sm:items-center gap-4">
                    @php
                        $busImg = $trip->bus->images->firstWhere('is_primary', true)?->image_path ?? 'images/hero/hero-bus.jpg';
                    @endphp
                    <div class="w-24 h-20 rounded-2xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200/80">
                        <img src="{{ asset($busImg) }}" alt="{{ $trip->bus->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-heading font-bold text-slate-900 truncate">{{ $trip->bus->name }}</h3>
                            <span class="font-mono text-xs px-2 py-0.5 bg-slate-50 text-slate-600 border border-slate-200/80 rounded-full font-semibold shrink-0">
                                {{ $trip->bus->code }}
                            </span>
                        </div>
                        <p class="text-xs text-orange-600 font-semibold mt-1">
                            {{ $trip->bus->bus_type ?? 'Bus Antarkota' }} &middot; Kapasitas {{ $trip->bus->total_seats }} Kursi
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Konfigurasi Kabin: <strong>2 + 2 Kursi Reclining</strong>
                        </p>
                    </div>
                </div>

                <p class="text-xs text-slate-500 leading-relaxed">
                    {{ $trip->bus->description ?? 'Armada bus antarkota resmi PO CAN Travel dengan perawatan berkala, kenyamanan suspensi udara, dan fasilitas standar perjalanan jarak jauh.' }}
                </p>

                <!-- Ketersediaan Kursi Box -->
                <div class="p-4 rounded-2xl bg-[#FAFBFD] border border-slate-200/80 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $availableCount > 5 ? 'bg-emerald-500' : ($availableCount > 0 ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                        <span class="text-slate-500 font-medium">Status Ketersediaan Kursi:</span>
                    </div>
                    <strong class="font-heading font-bold {{ $availableCount > 5 ? 'text-emerald-700' : ($availableCount > 0 ? 'text-amber-700' : 'text-rose-700') }}">
                        @if($availableCount > 0)
                            {{ $availableCount }} kursi tersisa ({{ $bookedCount }} terisi)
                        @else
                            Seluruh kursi telah terpesan
                        @endif
                    </strong>
                </div>
            </div>

            <!-- Fasilitas Bus -->
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                <div class="pb-3 border-b border-slate-100">
                    <h2 class="text-base sm:text-lg font-heading font-bold text-slate-900">
                        Fasilitas Armada Bus
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Kelengkapan kenyamanan penumpang yang tersedia selama perjalanan</p>
                </div>

                @if($trip->bus->facilities->isNotEmpty())
                    <ul class="divide-y divide-slate-100">
                        @foreach($trip->bus->facilities as $fac)
                            <li class="py-3 flex items-start gap-3.5 first:pt-0 last:pb-0">
                                <div class="w-7 h-7 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0 mt-0.5 border border-orange-200/60">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-heading font-bold text-slate-900">{{ $fac->name }}</h4>
                                    @if($fac->description)
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">{{ $fac->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-xs text-slate-500">Fasilitas standar perjalanan antarkota (AC, Kursi Reclining, Bagasi) tersedia pada unit bus ini.</p>
                @endif

                <!-- Ketentuan Keberangkatan -->
                <div class="pt-4 border-t border-slate-100 text-xs text-slate-500 leading-relaxed">
                    <strong class="text-slate-900 font-semibold block mb-0.5">Catatan Penting Keberangkatan:</strong>
                    Penumpang diharapkan tiba di lokasi terminal keberangkatan minimal 30 menit sebelum jam keberangkatan bus yang tertera untuk proses verifikasi tiket dan pemuatan bagasi.
                </div>
            </div>

        </section>

        <!-- SECTION 3: BOOKING ACTION CARD -->
        <section class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-orange-50 via-amber-50/60 to-orange-100/50 border border-orange-200/60 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-orange-600 block mb-1">
                    PEMESANAN TIKET RESMI
                </span>
                <h3 class="text-xl sm:text-2xl font-heading font-black text-slate-900">
                    Pilih Kursi Sendiri pada Denah Kabin
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-xl leading-relaxed">
                    Tentukan nomor kursi favorit Anda langsung secara transparan, lihat ketersediaan denah real-time, dan lanjutkan pemesanan aman tanpa biaya perantara.
                </p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <a
                    href="{{ route('trips.index') }}"
                    class="px-5 py-3 rounded-full bg-white hover:bg-slate-50 text-slate-700 font-heading font-bold text-xs sm:text-sm transition-colors border border-slate-200 shadow-xs text-center"
                >
                    Lihat Jadwal Lain
                </a>

                @if($availableCount > 0)
                    <a
                        href="{{ route('customer.trips.seats', $trip) }}"
                        class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 px-7 py-3 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-xs sm:text-sm transition-all shadow-md shadow-orange-500/20 text-center"
                    >
                        <span>Pilih Kursi</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @else
                    <button
                        disabled
                        class="flex-1 md:flex-initial px-6 py-3 rounded-full bg-slate-300 text-slate-500 font-heading font-bold text-xs sm:text-sm cursor-not-allowed text-center"
                    >
                        Kursi Penuh
                    </button>
                @endif
            </div>
        </section>

    </main>
</div>
@endsection

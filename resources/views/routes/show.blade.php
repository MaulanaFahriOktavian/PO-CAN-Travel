@extends('layouts.app')

@section('title', 'Rute ' . $route->origin . ' ke ' . $route->destination . ' — PO CAN Travel')
@section('meta_description', 'Detail rute perjalanan bus PO CAN Travel dari ' . $route->origin . ' ke ' . $route->destination . '. Estimasi waktu ' . intdiv($route->duration, 60) . ' jam, armada bertugas, dan jadwal aktif.')

@section('content')
{{-- Breadcrumb & Title Header (Light, Clean, Modern) --}}
<div class="relative py-10 lg:py-12 bg-gradient-to-b from-orange-50/40 via-white to-slate-50 border-b border-slate-100 overflow-hidden">
    <!-- Ambient Warm Glow -->
    <div class="absolute -top-20 right-10 w-96 h-96 bg-orange-200/20 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-orange-600 transition-colors">Beranda</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('routes.index') }}" class="hover:text-orange-600 transition-colors">Rute</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-semibold">{{ $route->origin }} &rarr; {{ $route->destination }}</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 border border-orange-200/80 text-orange-600 text-xs font-heading font-bold shadow-2xs uppercase tracking-wider mb-2">
                    Jalur Antarkota Resmi
                </span>
                <h1 class="text-3xl sm:text-4xl font-heading font-black tracking-tight text-slate-900 flex items-center gap-3">
                    <span>{{ $route->origin }}</span>
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-orange-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                    <span>{{ $route->destination }}</span>
                </h1>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-slate-400 block">Estimasi Perjalanan</span>
                <span class="text-xl font-heading font-black text-slate-900 tracking-wider inline-block mt-0.5 px-3 py-1 rounded-xl bg-white border border-slate-200/90 shadow-2xs">
                    &plusmn; {{ intdiv($route->duration, 60) }} Jam {{ $route->duration % 60 > 0 ? ($route->duration % 60) . ' Menit' : '' }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="py-12 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Section 1: Route Visualization Diagram --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-200 text-xs font-bold uppercase tracking-wider mb-2">
                Diagram Perjalanan
            </div>
            <h2 class="text-xl font-heading font-black tracking-tight text-slate-900 mb-6">
                Koridor Jalur Antarkota
            </h2>

            {{-- Visual Route Diagram --}}
            <div class="p-6 sm:p-8 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-6">
                {{-- Origin Point --}}
                <div class="flex items-center gap-4 text-left w-full md:w-auto">
                    <div class="w-12 h-12 rounded-2xl bg-orange-500 text-white flex items-center justify-center font-bold text-base shrink-0 shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Titik Keberangkatan</span>
                        <strong class="text-lg font-heading font-black text-slate-900">{{ $route->origin }}</strong>
                    </div>
                </div>

                {{-- Connector Line & Duration --}}
                <div class="flex-1 w-full flex flex-col items-center px-4">
                    <div class="flex items-center gap-2 text-xs font-heading font-bold text-slate-800 bg-white px-3.5 py-1.5 rounded-full border border-slate-200 shadow-2xs mb-2">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Estimasi Jalur Darat: &plusmn; {{ intdiv($route->duration, 60) }} Jam</span>
                    </div>
                    <div class="w-full flex items-center">
                        <div class="h-1 flex-1 bg-orange-500 rounded-full"></div>
                        <span class="text-orange-600 font-mono text-sm px-1 font-bold">→</span>
                    </div>
                </div>

                {{-- Destination Point --}}
                <div class="flex items-center gap-4 text-left md:text-right w-full md:w-auto">
                    <div class="order-2 md:order-1">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Titik Kedatangan</span>
                        <strong class="text-lg font-heading font-black text-slate-900">{{ $route->destination }}</strong>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold text-base shrink-0 order-1 md:order-2 shadow-xs">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Jadwal Perjalanan Tersedia --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
                <div>
                    <h2 class="text-xl font-heading font-bold text-slate-900">
                        Jadwal Keberangkatan Aktif
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Pilih jadwal yang sesuai dengan agenda perjalanan Anda</p>
                </div>
                <a
                    href="{{ route('trips.index', ['origin' => $route->origin, 'destination' => $route->destination]) }}"
                    class="inline-flex items-center gap-1.5 text-xs font-heading font-bold text-orange-600 hover:text-orange-700 transition-colors"
                >
                    <span>Cari Tanggal Lain</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($route->trips->isNotEmpty())
                <div class="space-y-3">
                    @foreach($route->trips as $trip)
                        <div class="p-5 rounded-xl border border-slate-200 bg-white hover:border-orange-400/60 hover:shadow-xs transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2.5 py-1 text-xs font-heading font-bold rounded-lg bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ $trip->bus->name }}
                                    </span>
                                    <span class="text-xs text-slate-500 font-mono">
                                        {{ $trip->bus->code }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-sm">
                                    <span class="font-heading font-bold text-slate-900">{{ $trip->departure_at->format('d M Y, H:i') }} WIB</span>
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    <span class="text-slate-600">{{ $trip->arrival_at->format('H:i') }} WIB</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Tarif per Penumpang</span>
                                    <strong class="text-lg font-heading font-black text-orange-600">Rp {{ number_format($trip->price, 0, ',', '.') }}</strong>
                                </div>
                                <a
                                    href="{{ route('trips.show', $trip) }}"
                                    class="px-5 py-2.5 text-xs font-heading font-bold text-white rounded-xl bg-orange-500 hover:bg-orange-600 transition-all shadow-xs whitespace-nowrap"
                                >
                                    Pilih Jadwal
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500 py-6 text-center">Belum ada jadwal keberangkatan aktif untuk rute ini dalam waktu dekat.</p>
            @endif
        </div>

        {{-- Section 3: Armada yang Melayani Rute Ini --}}
        @if($buses->isNotEmpty())
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs">
                <h2 class="text-xl font-heading font-bold text-slate-900 mb-4">
                    Armada yang Melayani Rute Ini
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($buses as $b)
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white hover:border-orange-300 transition-all flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-heading font-bold text-slate-900">{{ $b->name }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $b->bus_type ?? 'Bus Antarkota' }} &middot; {{ $b->total_seats }} Kursi</p>
                            </div>
                            <a href="{{ route('buses.show', $b) }}" class="inline-flex items-center gap-1 text-xs font-heading font-bold text-orange-600 hover:text-orange-700">
                                <span>Detail</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

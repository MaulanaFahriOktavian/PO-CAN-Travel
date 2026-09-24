@extends('layouts.app')

@section('title', 'Rute ' . $route->origin . ' ke ' . $route->destination . ' - PO CAN Travel')
@section('meta_description', 'Detail rute perjalanan bus PO CAN Travel dari ' . $route->origin . ' ke ' . $route->destination . '. Estimasi waktu ' . intdiv($route->duration, 60) . ' jam, armada bertugas, dan jadwal aktif.')

@section('content')
{{-- Breadcrumb & Title Header --}}
<div class="bg-[#1C2522] text-[#F5F1E8] py-10 lg:py-12 border-b border-[#2F6252]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-[#D9D5CA] mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('routes.index') }}" class="hover:text-white transition-colors">Rute</a>
            <span>/</span>
            <span class="text-[#F5F1E8] font-semibold">{{ $route->origin }} &rarr; {{ $route->destination }}</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#B96545] mb-1 block">
                    JALUR ANTARKOTA RESMI
                </span>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white flex items-center gap-3">
                    <span>{{ $route->origin }}</span>
                    <span class="text-[#D9D5CA] font-mono">→</span>
                    <span>{{ $route->destination }}</span>
                </h1>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-[#D9D5CA] block">Estimasi Perjalanan</span>
                <span class="text-xl font-bold text-white tracking-wider">&plusmn; {{ intdiv($route->duration, 60) }} Jam {{ $route->duration % 60 > 0 ? ($route->duration % 60) . ' Menit' : '' }}</span>
            </div>
        </div>
    </div>
</div>

<div class="py-12 bg-[#FBFAF6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Section 1: Route Visualization Diagram --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA]">
            <p class="text-xs font-bold uppercase tracking-wider text-[#21483C] mb-2">DIAGRAM PERJALANAN</p>
            <h2 class="text-xl font-black tracking-tight text-[#1C2522] mb-6">
                Koridor Jalur Antarkota
            </h2>

            {{-- Visual Route Diagram --}}
            <div class="p-6 sm:p-8 rounded-2xl bg-[#F5F1E8] border border-[#D9D5CA] flex flex-col md:flex-row items-center justify-between gap-6">
                {{-- Origin Point --}}
                <div class="flex items-center gap-4 text-left w-full md:w-auto">
                    <div class="w-12 h-12 rounded-2xl bg-[#21483C] text-white flex items-center justify-center font-bold text-base shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-[#66716C] uppercase tracking-wider block">Titik Keberangkatan</span>
                        <strong class="text-lg font-black text-[#1C2522]">{{ $route->origin }}</strong>
                    </div>
                </div>

                {{-- Connector Line & Duration --}}
                <div class="flex-1 w-full flex flex-col items-center px-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#1C2522] bg-white px-3 py-1.5 rounded-full border border-[#D9D5CA] mb-2">
                        <svg class="w-4 h-4 text-[#21483C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Estimasi Jalur Darat: &plusmn; {{ intdiv($route->duration, 60) }} Jam</span>
                    </div>
                    <div class="w-full flex items-center">
                        <div class="h-0.5 flex-1 bg-[#21483C] rounded-full"></div>
                        <span class="text-[#21483C] font-mono text-sm px-1">→</span>
                    </div>
                </div>

                {{-- Destination Point --}}
                <div class="flex items-center gap-4 text-left md:text-right w-full md:w-auto">
                    <div class="order-2 md:order-1">
                        <span class="text-xs font-semibold text-[#66716C] uppercase tracking-wider block">Titik Kedatangan</span>
                        <strong class="text-lg font-black text-[#1C2522]">{{ $route->destination }}</strong>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#1C2522] text-white flex items-center justify-center font-bold text-base shrink-0 order-1 md:order-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Jadwal Perjalanan Tersedia --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA]">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
                <div>
                    <h2 class="text-xl font-bold text-[#1C2522]">
                        Jadwal Keberangkatan Aktif
                    </h2>
                    <p class="text-xs text-[#66716C] mt-1">Pilih jadwal yang sesuai dengan agenda perjalanan Anda</p>
                </div>
                <a
                    href="{{ route('customer.trips.index', ['origin' => $route->origin, 'destination' => $route->destination]) }}"
                    class="text-xs font-bold text-[#21483C] hover:text-[#2F6252] transition-colors"
                >
                    Cari Tanggal Lain &rarr;
                </a>
            </div>

            @if($route->trips->isNotEmpty())
                <div class="space-y-4">
                    @foreach($route->trips as $trip)
                        <div class="p-5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6] hover:bg-white transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA]">
                                        {{ $trip->bus->name }}
                                    </span>
                                    <span class="text-xs text-[#66716C] font-mono">
                                        {{ $trip->bus->code }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="font-bold text-[#1C2522]">{{ $trip->departure_at->format('d M Y, H:i') }} WIB</span>
                                    <span class="text-[#D9D5CA]">&rarr;</span>
                                    <span class="text-[#66716C]">{{ $trip->arrival_at->format('H:i') }} WIB</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="text-right">
                                    <span class="text-[11px] text-[#66716C] block">Tarif per Penumpang</span>
                                    <strong class="text-lg font-black text-[#1C2522]">Rp {{ number_format($trip->price, 0, ',', '.') }}</strong>
                                </div>
                                <a
                                    href="{{ route('trips.show', $trip) }}"
                                    class="px-5 py-2.5 text-xs font-bold text-white rounded-xl bg-[#21483C] hover:bg-[#2F6252] transition-all whitespace-nowrap"
                                >
                                    Pilih Jadwal
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-[#66716C] py-6 text-center">Belum ada jadwal keberangkatan aktif untuk rute ini dalam waktu dekat.</p>
            @endif
        </div>

        {{-- Section 3: Armada yang Melayani Rute Ini --}}
        @if($buses->isNotEmpty())
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA]">
                <h2 class="text-xl font-bold text-[#1C2522] mb-4">
                    Armada yang Melayani Rute Ini
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($buses as $b)
                        <div class="p-4 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6] flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-[#1C2522]">{{ $b->name }}</h3>
                                <p class="text-xs text-[#66716C]">{{ $b->bus_type ?? 'Bus Antarkota' }} &middot; {{ $b->total_seats }} Kursi</p>
                            </div>
                            <a href="{{ route('buses.show', $b) }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                                Detail &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

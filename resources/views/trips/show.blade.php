@extends('layouts.app')

@section('title', 'Detail Perjalanan ' . $trip->route->origin . ' ke ' . $trip->route->destination . ' - PO CAN Travel')
@section('meta_description', 'Detail jadwal keberangkatan bus PO CAN Travel rute ' . $trip->route->origin . ' ke ' . $trip->route->destination . '. Jam berangkat ' . $trip->departure_at->format('H:i') . ', armada ' . $trip->bus->name . ', tarif Rp ' . number_format($trip->price, 0, ',', '.') . '.')

@section('content')
{{-- Breadcrumb & Title Header --}}
<div class="bg-[#1C2522] text-[#F5F1E8] py-10 lg:py-12 border-b border-[#2F6252]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-[#D9D5CA] mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('trips.index') }}" class="hover:text-white transition-colors">Perjalanan</a>
            <span>/</span>
            <span class="text-[#F5F1E8] font-semibold">{{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#B96545] mb-1 block">
                    DETAIL JADWAL OPERASIONAL
                </span>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white flex items-center gap-3">
                    <span>{{ $trip->route->origin }}</span>
                    <span class="text-[#D9D5CA]">&rarr;</span>
                    <span>{{ $trip->route->destination }}</span>
                </h1>
                <p class="text-xs text-[#D9D5CA] mt-1">
                    Keberangkatan: <strong class="text-white">{{ $trip->departure_at->format('l, d F Y') }}</strong>
                </p>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-[#D9D5CA] block">Tarif per Kursi</span>
                <strong class="text-2xl font-black text-white tracking-wider">Rp {{ number_format($trip->price, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="py-12 bg-[#FBFAF6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Section 1: Timeline & Perjalanan --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA]">
            <h2 class="text-xl font-bold text-[#1C2522] mb-6">
                Rangkaian Waktu Perjalanan
            </h2>

            <div class="p-6 sm:p-8 rounded-2xl bg-[#F5F1E8] border border-[#D9D5CA] flex flex-col md:flex-row items-center justify-between gap-6">
                {{-- Keberangkatan --}}
                <div class="flex items-center gap-4 text-left w-full md:w-auto">
                    <div class="w-12 h-12 rounded-xl bg-[#21483C] text-[#F5F1E8] flex items-center justify-center font-black text-sm shrink-0">
                        {{ $trip->departure_at->format('H:i') }}
                    </div>
                    <div>
                        <span class="text-xs font-bold text-[#66716C] uppercase tracking-wider block">WAKTU BERANGKAT</span>
                        <strong class="text-lg font-black text-[#1C2522]">{{ $trip->route->origin }}</strong>
                        <span class="text-xs text-[#66716C] block">{{ $trip->departure_at->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- Connector --}}
                <div class="flex-1 w-full flex flex-col items-center px-4">
                    <span class="text-xs font-bold text-[#1C2522] bg-white px-3 py-1 rounded-full border border-[#D9D5CA] mb-2">
                        &plusmn; {{ intdiv($trip->route->duration, 60) }} Jam Perjalanan
                    </span>
                    <div class="w-full flex items-center">
                        <div class="h-0.5 flex-1 bg-[#21483C] rounded-full"></div>
                        <span class="text-[#21483C] font-mono text-sm px-1">→</span>
                    </div>
                </div>

                {{-- Kedatangan --}}
                <div class="flex items-center gap-4 text-left md:text-right w-full md:w-auto">
                    <div class="order-2 md:order-1">
                        <span class="text-xs font-bold text-[#66716C] uppercase tracking-wider block">ESTIMASI TIBA</span>
                        <strong class="text-lg font-black text-[#1C2522]">{{ $trip->route->destination }}</strong>
                        <span class="text-xs text-[#66716C] block">{{ $trip->arrival_at->format('d M Y') }}</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-[#1C2522] text-[#F5F1E8] flex items-center justify-center font-black text-sm shrink-0 order-1 md:order-2">
                        {{ $trip->arrival_at->format('H:i') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Informasi Armada & Fasilitas --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Armada Info --}}
            <div class="lg:col-span-6 bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA] space-y-5">
                <div class="flex items-center justify-between border-b border-[#D9D5CA] pb-3">
                    <h2 class="text-lg font-bold text-[#1C2522]">
                        Armada Bus Bertugas
                    </h2>
                    <a href="{{ route('buses.show', $trip->bus) }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                        Profil Bus &rarr;
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    @php
                        $busImg = $trip->bus->images->firstWhere('is_primary', true)?->image_path ?? 'images/hero/hero-bus.jpg';
                    @endphp
                    <div class="w-24 h-20 rounded-xl overflow-hidden bg-[#1C2522] shrink-0 border border-[#D9D5CA]">
                        <img src="{{ asset($busImg) }}" alt="{{ $trip->bus->name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#1C2522]">{{ $trip->bus->name }}</h3>
                        <p class="text-xs text-[#66716C]">Kode: <strong class="font-mono text-[#1C2522]">{{ $trip->bus->code }}</strong></p>
                        <p class="text-xs text-[#21483C] font-semibold mt-0.5">{{ $trip->bus->bus_type ?? 'Bus Antarkota' }} &middot; {{ $trip->bus->total_seats }} Kursi</p>
                    </div>
                </div>

                <p class="text-xs text-[#66716C] leading-relaxed">
                    {{ $trip->bus->description ?? 'Armada bus yang melayani rute perjalanan antarkota Anda dengan standar kenyamanan terpercaya.' }}
                </p>

                <div class="p-4 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA] flex items-center justify-between text-xs">
                    <span class="text-[#66716C]">Ketersediaan Kursi:</span>
                    <strong class="{{ $availableCount <= 5 ? 'text-[#B96545] font-bold' : 'text-[#1C2522] font-bold' }}">
                        {{ $availableCount }} kursi tersisa ({{ $bookedCount }} terisi)
                    </strong>
                </div>
            </div>

            {{-- Fasilitas Terdaftar --}}
            <div class="lg:col-span-6 bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA] space-y-4">
                <h2 class="text-lg font-bold text-[#1C2522] border-b border-[#D9D5CA] pb-3">
                    Fasilitas Armada Bus
                </h2>

                @if($trip->bus->facilities->isNotEmpty())
                    <ul class="space-y-3">
                        @foreach($trip->bus->facilities as $fac)
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-lg bg-[#F5F1E8] text-[#21483C] flex items-center justify-center shrink-0 mt-0.5 border border-[#D9D5CA]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-[#1C2522]">{{ $fac->name }}</h4>
                                    <p class="text-[11px] text-[#66716C] mt-0.5 leading-relaxed">{{ $fac->description }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-xs text-[#66716C]">Fasilitas standar antarkota tersedia pada armada ini.</p>
                @endif
            </div>

        </div>

        {{-- Section 3: Booking Bar CTA --}}
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#D9D5CA] flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#66716C] block mb-1">SIAP MEMULAI PERJALANAN?</span>
                <h3 class="text-xl font-black text-[#1C2522]">
                    Pilih Kursi Sendiri pada Denah Kabin
                </h3>
                <p class="text-xs text-[#66716C] mt-0.5">
                    Kunci nomor kursi pilihan Anda langsung secara transparan tanpa biaya perantara.
                </p>
            </div>

            <div>
                <a
                    href="{{ route('customer.trips.seats', $trip) }}"
                    class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold text-[#F5F1E8] rounded-xl bg-[#21483C] hover:bg-[#2F6252] transition-colors"
                >
                    <span>Lanjut Pilih Kursi</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

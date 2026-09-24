@extends('layouts.app')

@section('title', 'Detail Perjalanan ' . $trip->route->origin . ' ke ' . $trip->route->destination . ' - PO CAN Travel')
@section('meta_description', 'Detail jadwal bus ' . $trip->route->origin . ' menuju ' . $trip->route->destination . ' tanggal ' . $trip->departure_at->translatedFormat('d F Y') . '. Cek ketersediaan kursi dan pesan langsung.')

@section('content')
<div class="py-10 sm:py-14 bg-[#FBFAF6]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Navigasi Kembali --}}
        <div>
            <a
                href="{{ route('customer.trips.index') }}"
                class="inline-flex items-center text-xs font-bold text-[#66716C] hover:text-[#21483C] transition-colors"
            >
                <span class="mr-1.5">&larr;</span> Kembali ke Daftar Perjalanan
            </a>
        </div>

        {{-- Main Container --}}
        <div class="bg-white border border-[#D9D5CA] rounded-2xl overflow-hidden shadow-2xs">

            {{-- Header Perjalanan --}}
            <div class="p-6 sm:p-8 border-b border-[#D9D5CA] bg-[#F5F1E8]">
                <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-3">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-bold block mb-1 text-[#21483C]">
                            Jadwal Resmi Perjalanan
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-[#1C2522]">
                            {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                        </h1>
                        <p class="mt-1 text-sm text-[#66716C]">
                            {{ $trip->departure_at->translatedFormat('l, d F Y') }}
                        </p>
                    </div>

                    <div>
                        @if(($availableSeatsCount ?? 1) > 0)
                            <span class="inline-flex items-center text-xs font-bold px-3 py-1.5 rounded-full border bg-white text-[#357A62] border-[#357A62]/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#357A62] mr-2"></span>
                                {{ $availableSeatsCount ?? 0 }} Kursi Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center text-xs font-bold px-3 py-1.5 rounded-full border bg-white text-[#B94A48] border-[#B94A48]/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#B94A48] mr-2"></span>
                                Kursi Habis
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Journey Timeline --}}
            <div class="p-6 sm:p-8 space-y-8">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider mb-6 text-[#66716C]">
                        Informasi Perjalanan
                    </h2>

                    {{-- Visual Timeline --}}
                    <div class="relative pl-8 space-y-8 before:absolute before:left-3 before:top-3 before:bottom-3 before:w-0.5 before:bg-[#D9D5CA]">
                        {{-- Departure --}}
                        <div class="relative">
                            <span class="absolute -left-8 top-1 w-6 h-6 rounded-full border-2 border-[#21483C] flex items-center justify-center bg-white">
                                <span class="w-2 h-2 rounded-full bg-[#B96545]"></span>
                            </span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#21483C]">Keberangkatan</span>
                                <div class="text-2xl font-black tabular-nums mt-0.5 text-[#1C2522]">
                                    {{ $trip->departure_at->format('H.i') }} <span class="text-xs font-normal text-[#66716C]">WIB</span>
                                </div>
                                <div class="text-sm font-bold mt-0.5 text-[#1C2522]">
                                    Terminal {{ $trip->route->origin }}
                                </div>
                                <div class="text-xs mt-0.5 text-[#66716C]">
                                    {{ $trip->departure_at->translatedFormat('d F Y') }}
                                </div>
                            </div>
                        </div>

                        {{-- Mid: Duration & Fleet Badge --}}
                        <div class="relative py-1">
                            @php
                                $hours = floor($trip->route->duration / 60);
                                $minutes = $trip->route->duration % 60;
                            @endphp
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-medium border border-[#D9D5CA] bg-[#F5F1E8] text-[#1C2522]">
                                <svg class="w-3.5 h-3.5 text-[#21483C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Durasi estimasi {{ $hours }} jam {{ $minutes > 0 ? $minutes . ' mnt' : '' }}</span>
                                <span class="text-[#D9D5CA]">&bull;</span>
                                <span>{{ $trip->bus->name }}</span>
                            </div>
                        </div>

                        {{-- Arrival --}}
                        <div class="relative">
                            <span class="absolute -left-8 top-1 w-6 h-6 rounded-full border-2 border-[#21483C] flex items-center justify-center bg-white">
                                <span class="w-2 h-2 rounded-full bg-[#21483C]"></span>
                            </span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#21483C]">Estimasi Tiba</span>
                                <div class="text-2xl font-black tabular-nums mt-0.5 text-[#1C2522]">
                                    {{ $trip->arrival_at->format('H.i') }} <span class="text-xs font-normal text-[#66716C]">WIB</span>
                                </div>
                                <div class="text-sm font-bold mt-0.5 text-[#1C2522]">
                                    Terminal {{ $trip->route->destination }}
                                </div>
                                <div class="text-xs mt-0.5 text-[#66716C]">
                                    {{ $trip->arrival_at->translatedFormat('d F Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Spesifikasi Armada & Rincian --}}
                <div class="pt-6 border-t border-[#D9D5CA]">
                    <h2 class="text-xs font-bold uppercase tracking-wider mb-4 text-[#66716C]">
                        Informasi Armada
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                        <div class="p-3.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6]">
                            <span class="text-xs block text-[#66716C]">Nama Bus</span>
                            <span class="font-bold mt-0.5 block truncate text-[#1C2522]">{{ $trip->bus->name }}</span>
                        </div>
                        <div class="p-3.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6]">
                            <span class="text-xs block text-[#66716C]">Kode Armada</span>
                            <span class="font-mono font-bold mt-0.5 block text-[#1C2522]">{{ $trip->bus->code }}</span>
                        </div>
                        <div class="p-3.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6]">
                            <span class="text-xs block text-[#66716C]">Kapasitas Kursi</span>
                            <span class="font-bold mt-0.5 block text-[#1C2522]">{{ $trip->bus->total_seats }} Kursi</span>
                        </div>
                        <div class="p-3.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6]">
                            <span class="text-xs block text-[#66716C]">Konfigurasi</span>
                            <span class="font-bold mt-0.5 block text-[#1C2522]">2 + 2</span>
                        </div>
                    </div>
                </div>

                {{-- Ketentuan Keberangkatan --}}
                <div class="p-4 rounded-xl border border-[#D9D5CA] text-xs leading-relaxed bg-[#F5F1E8] text-[#1C2522]">
                    <span class="font-bold block mb-1 text-[#1C2522]">Ketentuan Keberangkatan:</span>
                    Penumpang diharapkan hadir di terminal keberangkatan selambat-lambatnya 30 menit sebelum jadwal untuk verifikasi boarding. Pemilihan nomor kursi dilakukan secara mandiri pada tahap berikutnya.
                </div>

                {{-- Pricing & CTA Bar --}}
                <div class="pt-6 border-t border-[#D9D5CA] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs block text-[#66716C] font-medium">Tarif Resmi per Penumpang</span>
                        <div class="text-3xl font-black tabular-nums text-[#1C2522]">
                            Rp{{ number_format($trip->price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="px-4 py-2.5 text-xs font-bold rounded-xl border border-[#D9D5CA] text-[#1C2522] bg-white hover:bg-[#F5F1E8] transition-colors text-center"
                        >
                            Jadwal Lain
                        </a>

                        @if(($availableSeatsCount ?? 1) > 0)
                            <a
                                href="{{ route('customer.trips.seats', $trip) }}"
                                class="inline-flex items-center justify-center px-6 py-2.5 font-bold text-sm rounded-xl text-[#F5F1E8] bg-[#21483C] hover:bg-[#2F6252] transition-colors text-center"
                            >
                                Lanjut Pilih Kursi &rarr;
                            </a>
                        @else
                            <button
                                disabled
                                class="px-6 py-2.5 font-bold text-sm rounded-xl cursor-not-allowed text-center bg-[#D9D5CA] text-[#66716C]"
                            >
                                Kursi Penuh
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

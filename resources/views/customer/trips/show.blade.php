@extends('layouts.app')

@section('title', 'Detail Perjalanan ' . $trip->route->origin . ' ke ' . $trip->route->destination . ' — PO CAN Travel')
@section('meta_description', 'Detail jadwal bus ' . $trip->route->origin . ' menuju ' . $trip->route->destination . ' tanggal ' . $trip->departure_at->translatedFormat('d F Y') . '. Cek ketersediaan kursi dan pesan langsung.')

@section('content')
<div class="py-10 sm:py-14 bg-[#FAFBFD] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Navigasi Kembali --}}
        <div>
            <a
                href="{{ route('customer.trips.index') }}"
                class="inline-flex items-center text-xs font-heading font-bold text-slate-500 hover:text-orange-500 transition-colors gap-1.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Daftar Perjalanan</span>
            </a>
        </div>

        {{-- Main Container --}}
        <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-[0_16px_40px_-15px_rgba(15,23,42,0.06)]">

            {{-- Header Perjalanan: Clean Modern Light --}}
            <div class="p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-r from-orange-50/40 via-white to-amber-50/30">
                <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 border border-orange-200/80 text-orange-600 font-heading font-bold text-xs uppercase tracking-wider mb-2">
                            <span>Jadwal Resmi Perjalanan</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-heading font-black tracking-tight text-slate-900 flex items-center gap-3">
                            <span>{{ $trip->route->origin }}</span>
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            <span>{{ $trip->route->destination }}</span>
                        </h1>
                        <p class="mt-1.5 text-xs sm:text-sm text-slate-500 font-medium">
                            {{ $trip->departure_at->translatedFormat('l, d F Y') }}
                        </p>
                    </div>

                    <div>
                        @if(($availableSeatsCount ?? 1) > 0)
                            <span class="inline-flex items-center text-xs font-heading font-bold px-3.5 py-1.5 rounded-full border bg-emerald-50 text-emerald-700 border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                                {{ $availableSeatsCount ?? 0 }} Kursi Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center text-xs font-heading font-bold px-3.5 py-1.5 rounded-full border bg-rose-50 text-rose-700 border-rose-200">
                                <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span>
                                Kursi Habis
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Journey Timeline & Info --}}
            <div class="p-6 sm:p-8 space-y-8">
                <div>
                    <h2 class="text-xs font-heading font-bold uppercase tracking-wider mb-6 text-slate-400">
                        Informasi Perjalanan
                    </h2>

                    {{-- Visual Timeline --}}
                    <div class="relative pl-8 space-y-8 before:absolute before:left-3 before:top-3 before:bottom-3 before:w-0.5 before:bg-orange-100">
                        {{-- Departure --}}
                        <div class="relative">
                            <span class="absolute -left-8 top-1 w-6 h-6 rounded-full border-2 border-orange-500 flex items-center justify-center bg-white shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            </span>
                            <div>
                                <span class="text-xs font-heading font-bold uppercase tracking-wider text-orange-600">Keberangkatan</span>
                                <div class="text-2xl font-heading font-black tabular-nums mt-0.5 text-slate-900">
                                    {{ $trip->departure_at->format('H.i') }} <span class="text-xs font-normal text-slate-400">WIB</span>
                                </div>
                                <div class="text-sm font-heading font-bold mt-0.5 text-slate-800">
                                    Terminal {{ $trip->route->origin }}
                                </div>
                                <div class="text-xs mt-0.5 text-slate-500">
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
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-medium border border-orange-200/80 bg-orange-50/50 text-slate-700">
                                <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Durasi estimasi {{ $hours }} jam {{ $minutes > 0 ? $minutes . ' mnt' : '' }}</span>
                                <span class="text-slate-300">&bull;</span>
                                <strong class="font-heading font-bold text-orange-600">{{ $trip->bus->name }}</strong>
                            </div>
                        </div>

                        {{-- Arrival --}}
                        <div class="relative">
                            <span class="absolute -left-8 top-1 w-6 h-6 rounded-full border-2 border-orange-500 flex items-center justify-center bg-white shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            </span>
                            <div>
                                <span class="text-xs font-heading font-bold uppercase tracking-wider text-orange-600">Estimasi Tiba</span>
                                <div class="text-2xl font-heading font-black tabular-nums mt-0.5 text-slate-900">
                                    {{ $trip->arrival_at->format('H.i') }} <span class="text-xs font-normal text-slate-400">WIB</span>
                                </div>
                                <div class="text-sm font-heading font-bold mt-0.5 text-slate-800">
                                    Terminal {{ $trip->route->destination }}
                                </div>
                                <div class="text-xs mt-0.5 text-slate-500">
                                    {{ $trip->arrival_at->translatedFormat('d F Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Spesifikasi Armada & Rincian --}}
                <div class="pt-6 border-t border-slate-100">
                    <h2 class="text-xs font-heading font-bold uppercase tracking-wider mb-4 text-slate-400">
                        Informasi Armada
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50">
                            <span class="text-[11px] block text-slate-400 font-medium">Nama Bus</span>
                            <span class="font-heading font-bold mt-0.5 block truncate text-slate-900">{{ $trip->bus->name }}</span>
                        </div>
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50">
                            <span class="text-[11px] block text-slate-400 font-medium">Kode Armada</span>
                            <span class="font-mono font-bold mt-0.5 block text-slate-900">{{ $trip->bus->code }}</span>
                        </div>
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50">
                            <span class="text-[11px] block text-slate-400 font-medium">Kapasitas Kursi</span>
                            <span class="font-heading font-bold mt-0.5 block text-slate-900">{{ $trip->bus->total_seats }} Kursi</span>
                        </div>
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50">
                            <span class="text-[11px] block text-slate-400 font-medium">Konfigurasi</span>
                            <span class="font-heading font-bold mt-0.5 block text-slate-900">2 + 2</span>
                        </div>
                    </div>
                </div>

                {{-- Ketentuan Keberangkatan --}}
                <div class="p-4 sm:p-5 rounded-2xl border border-orange-200/80 text-xs leading-relaxed bg-orange-50/30 text-slate-700 flex items-start gap-3">
                    <svg class="w-5 h-5 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <strong class="font-heading font-bold block mb-0.5 text-slate-900">Ketentuan Keberangkatan:</strong>
                        Penumpang diharapkan hadir di terminal keberangkatan selambat-lambatnya 30 menit sebelum jadwal untuk verifikasi boarding. Pemilihan nomor kursi dilakukan secara mandiri pada tahap berikutnya.
                    </div>
                </div>

                {{-- Pricing & CTA Bar --}}
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs block text-slate-400 font-medium">Tarif Resmi per Penumpang</span>
                        <div class="text-2xl sm:text-3xl font-heading font-black tabular-nums text-orange-600">
                            Rp{{ number_format($trip->price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('customer.trips.index') }}"
                            class="px-5 py-2.5 text-xs font-heading font-bold rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 transition-colors text-center"
                        >
                            Jadwal Lain
                        </a>

                        @if(($availableSeatsCount ?? 1) > 0)
                            <a
                                href="{{ route('customer.trips.seats', $trip) }}"
                                class="inline-flex items-center justify-center gap-1.5 px-6 py-2.5 font-heading font-bold text-xs sm:text-sm rounded-full text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 transition-all shadow-md shadow-orange-500/20 text-center cursor-pointer"
                            >
                                <span>Lanjut Pilih Kursi</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        @else
                            <button
                                disabled
                                class="px-6 py-2.5 font-heading font-bold text-xs sm:text-sm rounded-full cursor-not-allowed text-center bg-gray-200 text-gray-500"
                            >
                                Kursi Penuh
                            </button>
                        @endif
                    </div>
                </div>

        </div>

    </div>
</div>
@endsection

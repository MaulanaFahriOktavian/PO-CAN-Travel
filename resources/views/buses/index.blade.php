@extends('layouts.app')

@section('title', 'Armada Bus Resmi - PO CAN Travel')
@section('meta_description', 'Katalog armada bus resmi PO CAN Travel. Ketahui tipe bus, kapasitas kursi, kenyamanan kabin, dan fasilitas perjalanan antarkota kami.')

@section('content')
{{-- Hero Header --}}
<div class="relative py-12 lg:py-16 bg-[#1C2522] text-[#F5F1E8] border-b border-[#2F6252]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-widest text-[#B96545] mb-2">STANDAR KELAYAKAN &amp; KENYAMANAN</p>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white mb-4">
            Armada PO CAN Travel
        </h1>
        <p class="text-base text-[#D9D5CA] max-w-2xl leading-relaxed">
            Seluruh perjalanan antarkota dilayani oleh armada bus resmi milik PO CAN Travel dengan perawatan berkala, denah kabin teratur, dan fasilitas perjalanan yang terstandarisasi.
        </p>
    </div>
</div>

{{-- Fleet List --}}
<div class="py-12 sm:py-16 bg-[#FBFAF6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="space-y-8">
            @forelse($buses as $bus)
                @php
                    $primaryImg = $bus->images->firstWhere('is_primary', true)?->image_path ?? 'images/hero/hero-bus.jpg';
                @endphp
                <div class="bg-white rounded-2xl overflow-hidden border border-[#D9D5CA] hover:border-[#21483C] transition-all">
                    <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch">
                        
                        {{-- Bus Image --}}
                        <div class="lg:col-span-5 relative min-h-[260px] bg-[#1C2522] overflow-hidden">
                            <img
                                src="{{ asset($primaryImg) }}"
                                alt="{{ $bus->name }}"
                                class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
                                loading="lazy"
                            />
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-bold rounded-lg text-white bg-[#21483C]">
                                    {{ $bus->bus_type ?? 'Bus Antarkota' }}
                                </span>
                            </div>
                        </div>

                        {{-- Bus Information --}}
                        <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-4 mb-2 flex-wrap">
                                    <h2 class="text-2xl font-black tracking-tight text-[#1C2522]">
                                        {{ $bus->name }}
                                    </h2>
                                    <span class="px-3 py-1 text-xs font-mono font-bold rounded-lg bg-[#F5F1E8] text-[#1C2522] border border-[#D9D5CA]">
                                        {{ $bus->code }}
                                    </span>
                                </div>

                                <p class="text-sm text-[#66716C] leading-relaxed mb-5">
                                    {{ $bus->description ?? 'Armada bus antarkota resmi PO CAN Travel dengan fasilitas kabin lengkap dan kapasitas tempat duduk terverifikasi.' }}
                                </p>

                                {{-- Spesifikasi Kunci --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6 p-4 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA]">
                                    <div>
                                        <span class="text-[11px] font-semibold text-[#66716C] uppercase tracking-wider block">Kapasitas Kursi</span>
                                        <strong class="text-sm font-bold text-[#1C2522]">{{ $bus->total_seats }} Kursi</strong>
                                    </div>
                                    <div>
                                        <span class="text-[11px] font-semibold text-[#66716C] uppercase tracking-wider block">Konfigurasi</span>
                                        <strong class="text-sm font-bold text-[#1C2522]">2 + 2 Baris</strong>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <span class="text-[11px] font-semibold text-[#66716C] uppercase tracking-wider block">Jadwal Aktif</span>
                                        <strong class="text-sm font-bold text-[#21483C]">{{ $bus->trips_count }} Perjalanan</strong>
                                    </div>
                                </div>

                                {{-- Fasilitas Armada --}}
                                @if($bus->facilities->isNotEmpty())
                                    <div>
                                        <span class="text-xs font-bold uppercase tracking-wider text-[#66716C] mb-2.5 block">Fasilitas Termasuk:</span>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($bus->facilities as $facility)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-[#1C2522] border border-[#D9D5CA]">
                                                    <svg class="w-3.5 h-3.5 text-[#21483C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    {{ $facility->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="mt-8 pt-5 border-t border-[#D9D5CA] flex items-center justify-between gap-4 flex-wrap">
                                <span class="text-xs text-[#66716C]">
                                    Melayani trayek antarkota resmi PO CAN Travel
                                </span>
                                <a
                                    href="{{ route('buses.show', $bus) }}"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#21483C] hover:bg-[#2F6252] transition-colors"
                                >
                                    <span>Lihat Detail & Denah Kursi</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>

                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-2xl border border-[#D9D5CA]">
                    <p class="text-base font-semibold text-[#1C2522]">Belum ada armada yang terdaftar di sistem.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection

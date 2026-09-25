@extends('layouts.app')

@section('title', 'Armada Bus Resmi — PO CAN Travel')
@section('meta_description', 'Katalog armada bus resmi PO CAN Travel. Ketahui tipe bus, kapasitas kursi, kenyamanan kabin, dan fasilitas perjalanan antarkota kami.')

@section('content')
{{-- Hero Header (Light, Clean, Modern) --}}
<div class="relative py-12 lg:py-16 bg-gradient-to-b from-orange-50/40 via-white to-slate-50 border-b border-slate-100 overflow-hidden">
    <!-- Ambient Warm Glow -->
    <div class="absolute -top-20 right-10 w-96 h-96 bg-orange-200/20 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white border border-orange-200/80 text-orange-600 text-xs font-heading font-bold shadow-2xs mb-3">
            <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span>Standar Kelayakan &amp; Kenyamanan</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black tracking-tight text-slate-900 mb-4">
            Armada PO CAN Travel
        </h1>
        <p class="text-base text-slate-600 max-w-2xl leading-relaxed">
            Seluruh perjalanan antarkota dilayani oleh armada bus resmi milik PO CAN Travel dengan perawatan berkala, denah kabin teratur, dan fasilitas perjalanan yang terstandarisasi.
        </p>
    </div>
</div>

{{-- Fleet List --}}
<div class="py-12 sm:py-16 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="space-y-8">
            @forelse($buses as $bus)
                @php
                    $primaryImg = $bus->images->firstWhere('is_primary', true)?->image_path ?? 'images/hero/hero-bus.jpg';
                @endphp
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 hover:border-orange-300 hover:shadow-md transition-all">
                    <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch">
                        
                        {{-- Bus Image --}}
                        <div class="lg:col-span-5 relative min-h-[260px] bg-slate-900 overflow-hidden">
                            <img
                                src="{{ asset($primaryImg) }}"
                                alt="{{ $bus->name }}"
                                class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
                                loading="lazy"
                            />
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-heading font-bold rounded-lg text-white bg-orange-500 shadow-xs">
                                    {{ $bus->bus_type ?? 'Bus Antarkota' }}
                                </span>
                            </div>
                        </div>

                        {{-- Bus Information --}}
                        <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-4 mb-2 flex-wrap">
                                    <h2 class="text-2xl font-heading font-black tracking-tight text-slate-900">
                                        {{ $bus->name }}
                                    </h2>
                                    <span class="px-3 py-1 text-xs font-mono font-bold rounded-lg bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $bus->code }}
                                    </span>
                                </div>

                                <p class="text-sm text-slate-600 leading-relaxed mb-5">
                                    {{ $bus->description ?? 'Armada bus antarkota resmi PO CAN Travel dengan fasilitas kabin lengkap dan kapasitas tempat duduk terverifikasi.' }}
                                </p>

                                {{-- Spesifikasi Kunci --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200">
                                    <div>
                                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Kapasitas Kursi</span>
                                        <strong class="text-sm font-heading font-bold text-slate-900">{{ $bus->total_seats }} Kursi</strong>
                                    </div>
                                    <div>
                                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Konfigurasi</span>
                                        <strong class="text-sm font-heading font-bold text-slate-900">2 + 2 Baris</strong>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Jadwal Aktif</span>
                                        <strong class="text-sm font-heading font-bold text-orange-600">{{ $bus->trips_count }} Perjalanan</strong>
                                    </div>
                                </div>

                                {{-- Fasilitas Armada --}}
                                @if($bus->facilities->isNotEmpty())
                                    <div>
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2.5 block">Fasilitas Termasuk:</span>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($bus->facilities as $facility)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-700 border border-slate-200 shadow-2xs">
                                                    @if(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'ac') || $facility->icon === 'wind')
                                                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20m9-10H3m15.364-6.364l-14.728 14.728m0-14.728l14.728 14.728"/></svg>
                                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'seat') || $facility->icon === 'seat')
                                                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 11l1-5h12l1 5M5 11v8h14v-8M5 11h14M8 19v2m8-2v2"/></svg>
                                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'usb') || $facility->icon === 'bolt')
                                                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'bagasi') || $facility->icon === 'briefcase')
                                                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7h-3V4a1 1 0 00-1-1H8a1 1 0 00-1 1v3H4a2 2 0 00-2 2v11a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM9 5h6v2H9V5zm11 15H4V9h16v11z"/></svg>
                                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'mineral') || str_contains(strtolower($facility->slug . ' ' . $facility->name), 'air') || $facility->icon === 'cup')
                                                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/></svg>
                                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'lamp') || $facility->icon === 'lamp')
                                                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                                    @else
                                                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    @endif
                                                    <span>{{ $facility->name }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-between gap-4 flex-wrap">
                                <span class="text-xs text-slate-500">
                                    Melayani trayek antarkota resmi PO CAN Travel
                                </span>
                                <a
                                    href="{{ route('buses.show', $bus) }}"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-heading font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 transition-all shadow-xs"
                                >
                                    <span>Lihat Detail &amp; Denah Kursi</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>

                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-2xl border border-slate-200">
                    <p class="text-base font-heading font-bold text-slate-900">Belum ada armada yang terdaftar di sistem.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection

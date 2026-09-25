@extends('layouts.app')

@section('title', 'Rute Perjalanan Bus — PO CAN Travel')
@section('meta_description', 'Temukan rute perjalanan antarkota yang dilayani armada PO CAN Travel. Cek jadwal tersedia, durasi perjalanan, dan tarif mulai per rute.')

@section('content')
{{-- Hero Header (Light, Clean, Modern) --}}
<div class="relative py-12 lg:py-16 bg-gradient-to-b from-orange-50/40 via-white to-slate-50 border-b border-slate-100 overflow-hidden">
    <!-- Ambient Warm Glow -->
    <div class="absolute -top-20 right-10 w-96 h-96 bg-orange-200/20 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white border border-orange-200/80 text-orange-600 text-xs font-heading font-bold shadow-2xs mb-3">
            <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <span>Jaringan Antarkota Resmi</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black tracking-tight text-slate-900 mb-4">
            Rute Perjalanan
        </h1>
        <p class="text-base text-slate-600 max-w-2xl leading-relaxed">
            Temukan jalur antarkota yang dilayani armada PO CAN Travel. Klik rute untuk melihat jadwal keberangkatan yang tersedia secara transparan.
        </p>
    </div>
</div>

<div class="py-12 sm:py-16 bg-[#F8FAFC]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($routesByOrigin->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-xs">
                <p class="text-base font-heading font-bold text-slate-900">Belum ada rute yang tersedia saat ini.</p>
                <p class="text-sm mt-1 text-slate-500">Jadwal perjalanan akan diperbarui secara berkala.</p>
            </div>
        @else
            <div class="space-y-10">
                @foreach($routesByOrigin as $origin => $routes)
                <div>
                    {{-- Origin city label --}}
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Dari</span>
                        <span class="text-base font-heading font-black text-slate-900">{{ $origin }}</span>
                        <span class="flex-1 h-px bg-slate-200"></span>
                    </div>

                    {{-- Route cards --}}
                    <div class="space-y-3">
                        @foreach($routes as $route)
                        <div class="bg-white rounded-xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border border-slate-200 hover:border-blue-400/60 shadow-xs hover:shadow-sm transition-all">
                            <div class="flex-1 min-w-0">
                                {{-- Route origin → destination --}}
                                <a href="{{ route('routes.show', $route) }}" class="flex items-center gap-2 mb-2 flex-wrap hover:text-orange-600 group transition-colors">
                                    <span class="text-base font-heading font-bold text-slate-900 group-hover:text-orange-600">{{ $route->origin }}</span>
                                    <svg class="w-4 h-4 text-orange-600 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                    <span class="text-base font-heading font-bold text-slate-900 group-hover:text-orange-600">{{ $route->destination }}</span>
                                </a>

                                {{-- Route metadata --}}
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-500">
                                    @php
                                        $h = floor($route->duration / 60);
                                        $m = $route->duration % 60;
                                    @endphp
                                    <span>{{ $h }} jam{{ $m > 0 ? ' ' . $m . ' menit' : '' }}</span>

                                    @if($route->scheduled_trips_count > 0)
                                        <span class="text-slate-300">·</span>
                                        <span>{{ $route->scheduled_trips_count }} jadwal tersedia</span>
                                        @if($route->min_price)
                                            <span class="text-slate-300">·</span>
                                            <span class="font-heading font-bold text-orange-600">Mulai Rp{{ number_format($route->min_price, 0, ',', '.') }}</span>
                                        @endif
                                    @else
                                        <span class="text-slate-300">·</span>
                                        <span class="italic text-slate-400">Tidak ada jadwal aktif</span>
                                    @endif
                                </div>
                            </div>

                            {{-- CTA --}}
                            <div class="shrink-0">
                                @if($route->scheduled_trips_count > 0)
                                    <a
                                        href="{{ route('trips.index', ['origin' => $route->origin, 'destination' => $route->destination]) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs sm:text-sm font-heading font-bold text-white rounded-xl transition-all bg-orange-500 hover:bg-orange-600 shadow-xs"
                                    >
                                        <span>Lihat Jadwal</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-lg text-slate-400 border border-slate-200 bg-slate-50">
                                        Belum Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Secondary CTA --}}
            <div class="mt-12 pt-8 border-t border-slate-200">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                    <div>
                        <p class="text-sm font-heading font-bold text-slate-900">Tidak menemukan rute yang dicari?</p>
                        <p class="text-xs text-slate-500 mt-0.5">Lihat seluruh jadwal perjalanan yang tersedia di sistem kami.</p>
                    </div>
                    <a
                        href="{{ route('trips.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-heading font-bold rounded-xl transition-colors"
                    >
                        <span>Semua Jadwal</span>
                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

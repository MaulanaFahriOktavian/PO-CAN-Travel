@extends('layouts.app')

@section('title', 'Fasilitas Layanan Perjalanan — PO CAN Travel')
@section('meta_description', 'Fasilitas resmi armada bus PO CAN Travel. Pendingin udara AC, kursi reclining 2+2, USB port pengisian daya, dan kapasitas bagasi kabin.')

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
            <span>Standar Pelayanan Resmi</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black tracking-tight text-slate-900 mb-4">
            Fasilitas Perjalanan
        </h1>
        <p class="text-base text-slate-600 max-w-2xl leading-relaxed">
            Kenyamanan dan keamanan penumpang adalah prioritas kami. Seluruh fasilitas di bawah ini terverifikasi secara operasional pada setiap armada bus resmi PO CAN Travel.
        </p>
    </div>
</div>

{{-- Facilities Grid --}}
<div class="py-12 sm:py-16 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        {{-- Section 1: Hero Editorial with Cabin Photo --}}
        <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-sm transition-all">
            <div class="grid grid-cols-1 lg:grid-cols-12 items-center">
                <div class="lg:col-span-6 relative aspect-[16/10] bg-slate-900">
                    <img
                        src="{{ asset('images/about/fleet-comfort.jpg') }}"
                        alt="Interior Kabin PO CAN Travel"
                        class="w-full h-full object-cover"
                        loading="eager"
                    />
                </div>
                <div class="lg:col-span-6 p-8 lg:p-10 space-y-4">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-orange-50 text-orange-700 border border-orange-200 text-xs font-bold uppercase tracking-wider">
                        <span>Kenyamanan Kabin</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-heading font-black tracking-tight text-slate-900">
                        Dirancang untuk Perjalanan Antarkota Tanpa Lelah
                    </h2>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Perjalanan jauh dari Jakarta menuju Jawa Tengah seperti Semarang dan Jepara membutuhkan ketenangan dan posisi duduk yang nyaman. Kami memilih konfigurasi kursi 2+2 dengan jarak antarkursi yang teratur, didukung pendingin udara sentral yang stabil.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('buses.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-orange-600 hover:text-orange-700 transition-colors">
                            <span>Eksplorasi Semua Armada</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Daftar Fasilitas dari Database --}}
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-heading font-black tracking-tight text-slate-900">
                        Rincian Fasilitas Operasional
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Standar kelayakan dan fasilitas yang terpasang pada kabin armada.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $index => $facility)
                    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 hover:border-orange-300 hover:shadow-sm transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-mono font-bold text-orange-700 bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-200">
                                    0{{ $index + 1 }}
                                </span>
                                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-100 shadow-2xs">
                                    @if(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'ac') || $facility->icon === 'wind')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20m9-10H3m15.364-6.364l-14.728 14.728m0-14.728l14.728 14.728"/></svg>
                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'seat') || $facility->icon === 'seat')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 11l1-5h12l1 5M5 11v8h14v-8M5 11h14M8 19v2m8-2v2"/></svg>
                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'usb') || $facility->icon === 'bolt')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'bagasi') || $facility->icon === 'briefcase')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7h-3V4a1 1 0 00-1-1H8a1 1 0 00-1 1v3H4a2 2 0 00-2 2v11a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM9 5h6v2H9V5zm11 15H4V9h16v11z"/></svg>
                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'mineral') || str_contains(strtolower($facility->slug . ' ' . $facility->name), 'air') || $facility->icon === 'cup')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/></svg>
                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'lamp') || $facility->icon === 'lamp')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                    @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'selimut') || str_contains(strtolower($facility->slug . ' ' . $facility->name), 'bantal') || $facility->icon === 'bed')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </div>
                            </div>

                            <h3 class="text-base font-heading font-bold text-slate-900 mb-2">
                                {{ $facility->name }}
                            </h3>

                            <p class="text-xs text-slate-600 leading-relaxed mb-4">
                                {{ $facility->description }}
                            </p>
                        </div>

                        @if($facility->buses->isNotEmpty())
                            <div class="pt-4 border-t border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1.5">Tersedia pada Armada:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($facility->buses as $fBus)
                                        <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-50 text-slate-700 border border-slate-200">
                                            {{ $fBus->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection

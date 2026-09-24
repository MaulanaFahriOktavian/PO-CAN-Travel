@extends('layouts.app')

@section('title', 'Fasilitas Layanan Perjalanan - PO CAN Travel')
@section('meta_description', 'Fasilitas resmi armada bus PO CAN Travel. Pendingin udara AC, kursi reclining 2+2, USB port pengisian daya, dan kapasitas bagasi kabin.')

@section('content')
{{-- Hero Header --}}
<div class="relative py-12 lg:py-16 bg-[#1C2522] text-[#F5F1E8] border-b border-[#2F6252]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-widest text-[#B96545] mb-2">STANDAR PELAYANAN RESMI</p>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white mb-4">
            Fasilitas Perjalanan
        </h1>
        <p class="text-base text-[#D9D5CA] max-w-2xl leading-relaxed">
            Kenyamanan dan keamanan penumpang adalah prioritas kami. Seluruh fasilitas di bawah ini terverifikasi secara operasional pada setiap armada yang beroperasi.
        </p>
    </div>
</div>

{{-- Facilities Grid --}}
<div class="py-12 sm:py-16 bg-[#FBFAF6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        {{-- Section 1: Hero Editorial with Cabin Photo --}}
        <div class="bg-white rounded-2xl overflow-hidden border border-[#D9D5CA]">
            <div class="grid grid-cols-1 lg:grid-cols-12 items-center">
                <div class="lg:col-span-6 relative aspect-[16/10] bg-[#1C2522]">
                    <img
                        src="{{ asset('images/about/fleet-comfort.jpg') }}"
                        alt="Interior Kabin PO CAN Travel"
                        class="w-full h-full object-cover"
                        loading="eager"
                    />
                </div>
                <div class="lg:col-span-6 p-8 lg:p-10 space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#21483C]">KENYAMANAN KABIN</span>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-[#1C2522]">
                        Dirancang untuk Perjalanan Antarkota Tanpa Lelah
                    </h2>
                    <p class="text-sm text-[#66716C] leading-relaxed">
                        Perjalanan jauh dari Jakarta menuju Jawa Tengah seperti Semarang dan Jepara membutuhkan ketenangan dan posisi duduk yang nyaman. Kami memilih konfigurasi kursi 2+2 dengan jarak antarkursi yang teratur, didukung pendingin udara sentral yang stabil.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('buses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                            <span>Eksplorasi Semua Armada</span>
                            <span class="font-mono">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Daftar Fasilitas dari Database --}}
        <div>
            <h2 class="text-2xl font-black tracking-tight text-[#1C2522] mb-6">
                Rincian Fasilitas Operasional
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $index => $facility)
                    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#D9D5CA] flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-mono font-bold text-[#21483C] bg-[#F5F1E8] px-2.5 py-1 rounded-md border border-[#D9D5CA]">
                                    0{{ $index + 1 }}
                                </span>
                                <div class="w-9 h-9 rounded-xl bg-[#F5F1E8] text-[#21483C] flex items-center justify-center border border-[#D9D5CA]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>

                            <h3 class="text-base font-bold text-[#1C2522] mb-2">
                                {{ $facility->name }}
                            </h3>

                            <p class="text-xs text-[#66716C] leading-relaxed mb-4">
                                {{ $facility->description }}
                            </p>
                        </div>

                        @if($facility->buses->isNotEmpty())
                            <div class="pt-4 border-t border-[#D9D5CA]">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#66716C] block mb-1.5">Tersedia pada Armada:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($facility->buses as $fBus)
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-[#FBFAF6] text-[#1C2522] border border-[#D9D5CA]">
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

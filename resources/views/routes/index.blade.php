@extends('layouts.app')

@section('title', 'Rute Perjalanan Bus - PO CAN Travel')
@section('meta_description', 'Temukan rute perjalanan antarkota yang dilayani armada PO CAN Travel. Cek jadwal tersedia, durasi perjalanan, dan tarif mulai per rute.')

@section('content')
<div class="py-12 sm:py-16 bg-[#FBFAF6]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="pb-8 mb-10 border-b border-[#D9D5CA]">
            <p class="text-xs font-bold uppercase tracking-wider text-[#21483C] mb-1">Jaringan Antarkota Resmi</p>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-[#1C2522]">Rute Perjalanan</h1>
            <p class="mt-2 text-base leading-relaxed text-[#66716C]">
                Temukan jalur antarkota yang dilayani armada PO CAN Travel. Klik rute untuk melihat jadwal keberangkatan yang tersedia.
            </p>
        </div>

        @if($routesByOrigin->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-[#D9D5CA]">
                <p class="text-base font-semibold text-[#1C2522]">Belum ada rute yang tersedia saat ini.</p>
                <p class="text-sm mt-1 text-[#66716C]">Jadwal perjalanan akan diperbarui secara berkala.</p>
            </div>
        @else
            <div class="space-y-12">
                @foreach($routesByOrigin as $origin => $routes)
                <div>
                    {{-- Origin city label --}}
                    <div class="flex items-center gap-3 mb-5">
                        <span class="text-xs font-bold uppercase tracking-widest text-[#66716C]">Dari</span>
                        <span class="text-base font-black text-[#21483C]">{{ $origin }}</span>
                        <span class="flex-1 h-px bg-[#D9D5CA]"></span>
                    </div>

                    {{-- Route cards --}}
                    <div class="space-y-3">
                        @foreach($routes as $route)
                        <div class="bg-white rounded-xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border border-[#D9D5CA]">
                            <div class="flex-1 min-w-0">
                                {{-- Route origin → destination --}}
                                <a href="{{ route('routes.show', $route) }}" class="flex items-center gap-2 mb-2 flex-wrap hover:text-[#21483C] group transition-colors">
                                    <span class="text-base font-bold text-[#1C2522] group-hover:text-[#21483C]">{{ $route->origin }}</span>
                                    <span class="text-[#21483C] font-mono">→</span>
                                    <span class="text-base font-bold text-[#1C2522] group-hover:text-[#21483C]">{{ $route->destination }}</span>
                                </a>

                                {{-- Route metadata --}}
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-[#66716C]">
                                    @php
                                        $h = floor($route->duration / 60);
                                        $m = $route->duration % 60;
                                    @endphp
                                    <span>{{ $h }} jam{{ $m > 0 ? ' ' . $m . ' menit' : '' }}</span>

                                    @if($route->scheduled_trips_count > 0)
                                        <span class="text-[#D9D5CA]">·</span>
                                        <span>{{ $route->scheduled_trips_count }} jadwal tersedia</span>
                                        @if($route->min_price)
                                            <span class="text-[#D9D5CA]">·</span>
                                            <span class="font-bold text-[#21483C]">Mulai Rp{{ number_format($route->min_price, 0, ',', '.') }}</span>
                                        @endif
                                    @else
                                        <span class="text-[#D9D5CA]">·</span>
                                        <span class="italic text-[#66716C]">Tidak ada jadwal aktif</span>
                                    @endif
                                </div>
                            </div>

                            {{-- CTA --}}
                            <div class="shrink-0">
                                @if($route->scheduled_trips_count > 0)
                                    <a
                                        href="{{ route('customer.trips.index', ['origin' => $route->origin, 'destination' => $route->destination]) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-colors bg-[#21483C] hover:bg-[#2F6252]"
                                    >
                                        Lihat Jadwal
                                        <span class="text-white font-mono">→</span>
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-[#66716C] border border-[#D9D5CA] bg-[#FBFAF6]">
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
            <div class="mt-14 pt-8 border-t border-[#D9D5CA]">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-[#1C2522]">Tidak menemukan rute yang dicari?</p>
                        <p class="text-xs text-[#66716C] mt-0.5">Lihat seluruh jadwal perjalanan yang tersedia di sistem.</p>
                    </div>
                    <a
                        href="{{ route('customer.trips.index') }}"
                        class="text-sm font-bold text-[#21483C] hover:text-[#2F6252] transition-colors"
                    >
                        Semua Jadwal &rarr;
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

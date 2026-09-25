@extends('layouts.app')

@section('title', $bus->name . ' (' . $bus->code . ') — Armada PO CAN Travel')
@section('meta_description', 'Spesifikasi armada ' . $bus->name . ' PO CAN Travel. Tipe bus ' . $bus->bus_type . ', kapasitas ' . $bus->total_seats . ' kursi, denah kabin, dan fasilitas.')

@section('content')
{{-- Breadcrumb & Title Header (Light, Clean, Modern) --}}
<div class="relative py-10 lg:py-12 bg-gradient-to-b from-orange-50/40 via-white to-slate-50 border-b border-slate-100 overflow-hidden">
    <!-- Ambient Warm Glow -->
    <div class="absolute -top-20 right-10 w-96 h-96 bg-orange-200/20 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-orange-600 transition-colors">Beranda</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('buses.index') }}" class="hover:text-orange-600 transition-colors">Armada</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-semibold">{{ $bus->code }}</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 border border-orange-200/80 text-orange-600 text-xs font-heading font-bold shadow-2xs uppercase tracking-wider mb-2">
                    {{ $bus->bus_type ?? 'Bus Antarkota' }}
                </span>
                <h1 class="text-3xl sm:text-4xl font-heading font-black tracking-tight text-slate-900">
                    {{ $bus->name }}
                </h1>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-slate-400 block">Kode Registrasi Armada</span>
                <span class="text-xl font-mono font-black text-slate-900 tracking-wider inline-block mt-0.5 px-3 py-1 rounded-xl bg-white border border-slate-200/90 shadow-2xs">
                    {{ $bus->code }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="py-12 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Section 1: Galeri Foto & Spesifikasi --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Foto Galeri --}}
            <div class="lg:col-span-7 space-y-4">
                @php
                    $primary = $bus->images->firstWhere('is_primary', true)?->image_path ?? 'images/hero/hero-bus.jpg';
                @endphp
                <div class="rounded-2xl overflow-hidden bg-slate-900 aspect-[16/10] border border-slate-200 shadow-xs">
                    <img
                        src="{{ asset($primary) }}"
                        alt="{{ $bus->name }}"
                        class="w-full h-full object-cover"
                        loading="eager"
                    />
                </div>

                {{-- Thumbnail Gallery --}}
                @if($bus->images->count() > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($bus->images as $img)
                            <div class="rounded-xl overflow-hidden aspect-[4/3] bg-slate-100 border border-slate-200">
                                <img
                                    src="{{ asset($img->image_path) }}"
                                    alt="{{ $img->caption ?? $bus->name }}"
                                    class="w-full h-full object-cover hover:opacity-90 transition-opacity cursor-pointer"
                                    loading="lazy"
                                />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Spesifikasi & Deskripsi --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-5">
                    <h2 class="text-lg font-heading font-bold text-slate-900 border-b border-slate-100 pb-3">
                        Spesifikasi Teknis Armada
                    </h2>

                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $bus->description ?? 'Armada resmi PO CAN Travel yang terawat prima dan memenuhi standar kelaikan transportasi antarkota.' }}
                    </p>

                    <dl class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <dt class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Tipe Kelas</dt>
                            <dd class="text-sm font-heading font-bold text-slate-900 mt-1">{{ $bus->bus_type ?? 'Bus Antarkota' }}</dd>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <dt class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Total Kapasitas</dt>
                            <dd class="text-sm font-heading font-bold text-slate-900 mt-1">{{ $bus->total_seats }} Kursi</dd>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <dt class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Konfigurasi Kabin</dt>
                            <dd class="text-sm font-heading font-bold text-slate-900 mt-1">2 - 2 (Lorong Tengah)</dd>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <dt class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Sistem Reservasi</dt>
                            <dd class="text-sm font-heading font-bold text-orange-600 mt-1">Pemilihan Mandiri</dd>
                        </div>
                    </dl>
                </div>

                {{-- Fasilitas Terpasang --}}
                <div class="bg-white rounded-2xl p-6 sm:p-7 border border-slate-200 shadow-xs space-y-4">
                    <h2 class="text-lg font-heading font-bold text-slate-900 border-b border-slate-100 pb-3">
                        Fasilitas Armada Terverifikasi
                    </h2>

                    @if($bus->facilities->isNotEmpty())
                        <ul class="space-y-3">
                            @foreach($bus->facilities as $facility)
                                <li class="flex items-start gap-3">
                                    <div class="w-7 h-7 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0 mt-0.5 border border-orange-100">
                                        @if(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'ac') || $facility->icon === 'wind')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20m9-10H3m15.364-6.364l-14.728 14.728m0-14.728l14.728 14.728"/></svg>
                                        @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'seat') || $facility->icon === 'seat')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 11l1-5h12l1 5M5 11v8h14v-8M5 11h14M8 19v2m8-2v2"/></svg>
                                        @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'usb') || $facility->icon === 'bolt')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'bagasi') || $facility->icon === 'briefcase')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7h-3V4a1 1 0 00-1-1H8a1 1 0 00-1 1v3H4a2 2 0 00-2 2v11a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM9 5h6v2H9V5zm11 15H4V9h16v11z"/></svg>
                                        @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'mineral') || str_contains(strtolower($facility->slug . ' ' . $facility->name), 'air') || $facility->icon === 'cup')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/></svg>
                                        @elseif(str_contains(strtolower($facility->slug . ' ' . $facility->name), 'lamp') || $facility->icon === 'lamp')
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-heading font-bold text-slate-900">{{ $facility->name }}</h3>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">{{ $facility->description }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-slate-500">Informasi fasilitas sedang diperbarui oleh sistem operasional.</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- Section 2: Denah Kabin Kursi Bus --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs">
            <div class="max-w-2xl mb-8">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-200 text-xs font-bold uppercase tracking-wider mb-2">
                    Layout Kabin Penumpang
                </div>
                <h2 class="text-2xl font-heading font-black tracking-tight text-slate-900">
                    Denah Nomor Kursi
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">
                    Konfigurasi tata letak tempat duduk 2+2 di kabin {{ $bus->name }}. Calon penumpang dapat menentukan posisi duduk sesuai denah ini saat pemesanan.
                </p>
            </div>

            {{-- Kabin Visualizer --}}
            <div class="max-w-md mx-auto p-6 rounded-2xl bg-slate-50 border-2 border-slate-200 relative">
                {{-- Bagian Depan / Driver --}}
                <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-widest">
                    <span>Pintu Depan</span>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-slate-800 border border-slate-200 shadow-2xs">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <span>Kemudi / Supir</span>
                    </div>
                </div>

                {{-- Grid Kursi 2+2 --}}
                @php
                    $rows = $seats->groupBy(function($s) {
                        preg_match('/(\d+)/', $s->seat_number, $m);
                        return $m[1] ?? '0';
                    });
                @endphp

                <div class="space-y-3">
                    @foreach($rows as $rowNum => $rowSeats)
                        <div class="flex items-center justify-between gap-4">
                            {{-- Sisi Kiri (A & B) --}}
                            <div class="flex gap-2">
                                @foreach($rowSeats->filter(fn($s) => str_ends_with($s->seat_number, 'A') || str_ends_with($s->seat_number, 'B')) as $seat)
                                    <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center font-heading font-bold text-xs text-slate-800">
                                        {{ $seat->seat_number }}
                                    </div>
                                @endforeach
                            </div>

                            {{-- Gang / Lorong Tengah --}}
                            <div class="text-[10px] font-mono text-slate-400 tracking-widest text-center px-1">
                                ||
                            </div>

                            {{-- Sisi Kanan (C & D) --}}
                            <div class="flex gap-2">
                                @foreach($rowSeats->filter(fn($s) => str_ends_with($s->seat_number, 'C') || str_ends_with($s->seat_number, 'D')) as $seat)
                                    <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center font-heading font-bold text-xs text-slate-800">
                                        {{ $seat->seat_number }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Bagian Belakang --}}
                <div class="pt-6 mt-6 border-t border-slate-200 text-center text-[11px] font-semibold text-slate-400 tracking-wider uppercase">
                    Bagian Belakang Kabin
                </div>
            </div>
        </div>

        {{-- Section 3: Jadwal Perjalanan Aktif Bus Ini --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-xs">
            <h2 class="text-xl font-heading font-bold text-slate-900 mb-4">
                Jadwal Keberangkatan Terdekat Armada Ini
            </h2>

            @if($bus->trips->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs uppercase tracking-wider text-slate-500 bg-slate-50 border-y border-slate-200">
                            <tr>
                                <th class="py-3 px-4 font-semibold">Rute Perjalanan</th>
                                <th class="py-3 px-4 font-semibold">Keberangkatan</th>
                                <th class="py-3 px-4 font-semibold">Kedatangan</th>
                                <th class="py-3 px-4 font-semibold">Tarif</th>
                                <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($bus->trips as $trip)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 font-heading font-bold text-slate-900">
                                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">
                                        {{ $trip->departure_at->format('d M Y, H:i') }} WIB
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">
                                        {{ $trip->arrival_at->format('d M Y, H:i') }} WIB
                                    </td>
                                    <td class="py-3.5 px-4 font-heading font-bold text-orange-600">
                                        Rp {{ number_format($trip->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a
                                            href="{{ route('trips.show', $trip) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-heading font-bold text-white bg-orange-500 hover:bg-orange-600 transition-colors shadow-2xs"
                                        >
                                            <span>Lihat Jadwal</span>
                                            <span class="font-mono">→</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-slate-500 py-4">Belum ada jadwal keberangkatan aktif yang dijadwalkan untuk armada ini dalam beberapa hari ke depan.</p>
            @endif
        </div>

    </div>
</div>
@endsection

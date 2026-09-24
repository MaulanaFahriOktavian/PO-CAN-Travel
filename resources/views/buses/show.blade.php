@extends('layouts.app')

@section('title', $bus->name . ' (' . $bus->code . ') - Armada PO CAN Travel')
@section('meta_description', 'Spesifikasi armada ' . $bus->name . ' PO CAN Travel. Tipe bus ' . $bus->bus_type . ', kapasitas ' . $bus->total_seats . ' kursi, denah kabin, dan fasilitas.')

@section('content')
{{-- Breadcrumb & Title Header --}}
<div class="bg-[#1C2522] text-[#F5F1E8] py-10 lg:py-12 border-b border-[#2F6252]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-[#D9D5CA] mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('buses.index') }}" class="hover:text-white transition-colors">Armada</a>
            <span>/</span>
            <span class="text-[#F5F1E8] font-semibold">{{ $bus->code }}</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#B96545] mb-1 block">
                    {{ $bus->bus_type ?? 'Bus Antarkota' }}
                </span>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    {{ $bus->name }}
                </h1>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-[#D9D5CA] block">Kode Registrasi Armada</span>
                <span class="text-xl font-mono font-black text-white tracking-wider">{{ $bus->code }}</span>
            </div>
        </div>
    </div>
</div>

<div class="py-12 bg-[#FBFAF6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Section 1: Galeri Foto & Spesifikasi --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Foto Galeri --}}
            <div class="lg:col-span-7 space-y-4">
                @php
                    $primary = $bus->images->firstWhere('is_primary', true)?->image_path ?? 'images/hero/hero-bus.jpg';
                @endphp
                <div class="rounded-2xl overflow-hidden bg-[#1C2522] aspect-[16/10] border border-[#D9D5CA]">
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
                            <div class="rounded-xl overflow-hidden aspect-[4/3] bg-[#F5F1E8] border border-[#D9D5CA]">
                                <img
                                    src="{{ asset($img->image_path) }}"
                                    alt="{{ $img->caption ?? $bus->name }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Spesifikasi & Deskripsi --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#D9D5CA] space-y-5">
                    <h2 class="text-lg font-bold text-[#1C2522] border-b border-[#D9D5CA] pb-3">
                        Spesifikasi Teknis Armada
                    </h2>

                    <p class="text-sm text-[#66716C] leading-relaxed">
                        {{ $bus->description ?? 'Armada resmi PO CAN Travel yang terawat prima dan memenuhi standar kelaikan transportasi antarkota.' }}
                    </p>

                    <dl class="grid grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA]">
                            <dt class="text-[#66716C] font-semibold uppercase tracking-wider text-[10px]">Tipe Kelas</dt>
                            <dd class="text-sm font-bold text-[#1C2522] mt-1">{{ $bus->bus_type ?? 'Bus Antarkota' }}</dd>
                        </div>
                        <div class="p-3.5 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA]">
                            <dt class="text-[#66716C] font-semibold uppercase tracking-wider text-[10px]">Total Kapasitas</dt>
                            <dd class="text-sm font-bold text-[#1C2522] mt-1">{{ $bus->total_seats }} Kursi</dd>
                        </div>
                        <div class="p-3.5 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA]">
                            <dt class="text-[#66716C] font-semibold uppercase tracking-wider text-[10px]">Konfigurasi Kabin</dt>
                            <dd class="text-sm font-bold text-[#1C2522] mt-1">2 - 2 (Lorong Tengah)</dd>
                        </div>
                        <div class="p-3.5 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA]">
                            <dt class="text-[#66716C] font-semibold uppercase tracking-wider text-[10px]">Sistem Reservasi</dt>
                            <dd class="text-sm font-bold text-[#21483C] mt-1">Pemilihan Mandiri</dd>
                        </div>
                    </dl>
                </div>

                {{-- Fasilitas Terpasang --}}
                <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#D9D5CA] space-y-4">
                    <h2 class="text-lg font-bold text-[#1C2522] border-b border-[#D9D5CA] pb-3">
                        Fasilitas Armada Terverifikasi
                    </h2>

                    @if($bus->facilities->isNotEmpty())
                        <ul class="space-y-3">
                            @foreach($bus->facilities as $facility)
                                <li class="flex items-start gap-3">
                                    <div class="w-6 h-6 rounded-lg bg-[#F5F1E8] text-[#21483C] flex items-center justify-center shrink-0 mt-0.5 border border-[#D9D5CA]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-bold text-[#1C2522]">{{ $facility->name }}</h3>
                                        <p class="text-[11px] text-[#66716C] mt-0.5 leading-relaxed">{{ $facility->description }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-[#66716C]">Informasi fasilitas sedang diperbarui oleh sistem operasional.</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- Section 2: Denah Kabin Kursi Bus --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA]">
            <div class="max-w-2xl mb-8">
                <p class="text-xs font-bold uppercase tracking-wider text-[#21483C] mb-1">LAYOUT KABIN PENUMPANG</p>
                <h2 class="text-2xl font-black tracking-tight text-[#1C2522]">
                    Denah Nomor Kursi
                </h2>
                <p class="text-xs sm:text-sm text-[#66716C] mt-1">
                    Konfigurasi tata letak tempat duduk 2+2 di kabin {{ $bus->name }}. Calon penumpang dapat menentukan posisi duduk sesuai denah ini saat pemesanan.
                </p>
            </div>

            {{-- Kabin Visualizer --}}
            <div class="max-w-md mx-auto p-6 rounded-2xl bg-[#FBFAF6] border-2 border-[#D9D5CA] relative">
                {{-- Bagian Depan / Driver --}}
                <div class="flex items-center justify-between pb-6 mb-6 border-b border-[#D9D5CA] text-xs font-bold text-[#66716C] uppercase tracking-widest">
                    <span>Pintu Depan</span>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#F5F1E8] text-[#1C2522] border border-[#D9D5CA]">
                        <svg class="w-4 h-4 text-[#21483C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <span>KEMUDI / SUPIR</span>
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
                                    <div class="w-11 h-11 rounded-xl bg-white border border-[#D9D5CA] flex items-center justify-center font-bold text-xs text-[#1C2522]">
                                        {{ $seat->seat_number }}
                                    </div>
                                @endforeach
                            </div>

                            {{-- Gang / Lorong Tengah --}}
                            <div class="text-[10px] font-mono text-[#D9D5CA] tracking-widest text-center px-1">
                                ||
                            </div>

                            {{-- Sisi Kanan (C & D) --}}
                            <div class="flex gap-2">
                                @foreach($rowSeats->filter(fn($s) => str_ends_with($s->seat_number, 'C') || str_ends_with($s->seat_number, 'D')) as $seat)
                                    <div class="w-11 h-11 rounded-xl bg-white border border-[#D9D5CA] flex items-center justify-center font-bold text-xs text-[#1C2522]">
                                        {{ $seat->seat_number }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Bagian Belakang --}}
                <div class="pt-6 mt-6 border-t border-[#D9D5CA] text-center text-[11px] font-semibold text-[#66716C] tracking-wider uppercase">
                    Bagian Belakang Kabin
                </div>
            </div>
        </div>

        {{-- Section 3: Jadwal Perjalanan Aktif Bus Ini --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA]">
            <h2 class="text-xl font-bold text-[#1C2522] mb-4">
                Jadwal Keberangkatan Terdekat Armada Ini
            </h2>

            @if($bus->trips->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs uppercase tracking-wider text-[#66716C] bg-[#F5F1E8] border-y border-[#D9D5CA]">
                            <tr>
                                <th class="py-3 px-4">Rute Perjalanan</th>
                                <th class="py-3 px-4">Keberangkatan</th>
                                <th class="py-3 px-4">Kedatangan</th>
                                <th class="py-3 px-4">Tarif</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#D9D5CA]/50">
                            @foreach($bus->trips as $trip)
                                <tr class="hover:bg-[#F5F1E8]/50 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-[#1C2522]">
                                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                    </td>
                                    <td class="py-3.5 px-4 text-[#66716C]">
                                        {{ $trip->departure_at->format('d M Y, H:i') }} WIB
                                    </td>
                                    <td class="py-3.5 px-4 text-[#66716C]">
                                        {{ $trip->arrival_at->format('d M Y, H:i') }} WIB
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-[#21483C]">
                                        Rp {{ number_format($trip->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a
                                            href="{{ route('trips.show', $trip) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-[#21483C] bg-[#F5F1E8] hover:bg-[#D9D5CA] transition-colors"
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
                <p class="text-sm text-[#66716C] py-4">Belum ada jadwal keberangkatan aktif yang dijadwalkan untuk armada ini dalam beberapa hari ke depan.</p>
            @endif
        </div>

    </div>
</div>
@endsection

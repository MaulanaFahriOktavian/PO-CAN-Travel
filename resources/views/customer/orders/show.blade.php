@extends('layouts.app')

@section('title', 'E-Tiket & Boarding Pass ' . $order->order_code . ' — PO CAN Travel')
@section('meta_description', 'Official digital boarding pass dan e-tiket resmi PO CAN Travel untuk pesanan kode ' . $order->order_code . '. Rute ' . $order->trip->route->origin . ' ke ' . $order->trip->route->destination . '.')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] text-[#0F172A]">

    <!-- Progress Stepper Tracker Bar (no-print) -->
    <section class="w-full bg-white border-b border-slate-200/90 sticky top-[74px] z-30 no-print shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between overflow-x-auto py-1 text-xs font-semibold">
                <!-- Step 1 -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Tahap 1</span>
                        <span class="text-xs font-heading font-bold text-[#0F172A]">Pilih Rute</span>
                    </div>
                </div>

                <div class="w-8 sm:w-16 lg:w-24 h-[2px] bg-slate-900 shrink-0 mx-2"></div>

                <!-- Step 2 -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Tahap 2</span>
                        <span class="text-xs font-heading font-bold text-[#0F172A]">Pilih Kursi</span>
                    </div>
                </div>

                <div class="w-8 sm:w-16 lg:w-24 h-[2px] bg-slate-900 shrink-0 mx-2"></div>

                <!-- Step 3 -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Tahap 3</span>
                        <span class="text-xs font-heading font-bold text-[#0F172A]">Data Pemesan</span>
                    </div>
                </div>

                <div class="w-8 sm:w-16 lg:w-24 h-[2px] bg-orange-500 shrink-0 mx-2"></div>

                <!-- Step 4: Active -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 text-white flex items-center justify-center text-xs font-bold shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-orange-600 font-bold uppercase tracking-wider">Selesai</span>
                        <span class="text-xs font-heading font-extrabold text-orange-600">E-Tiket Terbit</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Workspace -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10 w-full space-y-6">

        <!-- Confident Header Card (no-print) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/90 shadow-2xs no-print">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2.5 mb-1">
                        <span class="text-xs font-mono font-bold text-slate-500 uppercase tracking-wider">
                            Kode Pesanan Resmi
                        </span>
                        <span class="text-slate-300">•</span>
                        <span class="text-xs text-slate-500 font-medium">
                            Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-mono font-black text-[#0F172A] tracking-tight">
                        {{ $order->order_code }}
                    </h1>
                </div>
            </div>

            <!-- Status Badge & Action -->
            <div class="flex flex-wrap items-center gap-3">
                @if ($order->status === 'pending')
                    <span class="inline-flex items-center gap-1.5 text-xs font-heading font-bold px-3.5 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu Pembayaran
                    </span>
                @elseif ($order->status === 'confirmed')
                    <span class="inline-flex items-center gap-1.5 text-xs font-heading font-bold px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Dikonfirmasi
                    </span>
                @elseif ($order->status === 'completed')
                    <span class="inline-flex items-center gap-1.5 text-xs font-heading font-bold px-3.5 py-1.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                        Selesai
                    </span>
                @elseif ($order->status === 'cancelled')
                    <span class="inline-flex items-center gap-1.5 text-xs font-heading font-bold px-3.5 py-1.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                        Dibatalkan
                    </span>
                @else
                    <span class="inline-block text-xs font-bold text-slate-700 bg-slate-100 px-3.5 py-1.5 rounded-full border border-slate-200 capitalize">
                        {{ $order->status }}
                    </span>
                @endif

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-heading font-bold rounded-full transition-all shadow-xs cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak E-Tiket</span>
                </button>
            </div>
        </div>

        <!-- ═════════════════════════════════════════════════════════════
             DIGITAL BOARDING PASS CARD
             ═════════════════════════════════════════════════════════════ -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-[0_16px_40px_-15px_rgba(15,23,42,0.08)] overflow-hidden">

            <!-- Navy Executive Ticket Top Header -->
            <div class="bg-gradient-to-r from-[#0B132B] via-[#0F172A] to-[#1E293B] text-white p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-white.png') }}" alt="PO CAN Travel" class="h-8 w-auto object-contain">
                        <div class="hidden sm:block border-l border-white/20 pl-3">
                            <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold font-heading block">
                                BOARDING PASS
                            </span>
                            <span class="text-sm font-heading font-extrabold text-white">
                                Tiket Bus Antarkota Resmi
                            </span>
                        </div>
                    </div>

                    <div class="text-left sm:text-right">
                        <span class="text-xs text-slate-400 block font-mono">KODE BOOKING</span>
                        <span class="text-xl font-mono font-black text-amber-400 tracking-wider">
                            {{ $order->order_code }}
                        </span>
                    </div>
                </div>

                <!-- Main Journey Highlight -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center pt-6">
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-heading">Kota Asal</span>
                        <span class="text-2xl sm:text-3xl font-heading font-black text-white block mt-1">
                            {{ $order->trip->route->origin }}
                        </span>
                        <span class="text-xs text-slate-300 mt-1 block">
                            Terminal / Pool Resmi PO CAN
                        </span>
                    </div>

                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-300 mb-1">
                            <svg class="w-4 h-4 text-[#38BDF8]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span>&plusmn; {{ floor($order->trip->route->duration / 60) }} Jam {{ $order->trip->route->duration % 60 > 0 ? ($order->trip->route->duration % 60) . 'm' : '' }}</span>
                        </div>
                        <div class="w-full flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shrink-0"></span>
                            <div class="flex-1 h-[2px] bg-gradient-to-r from-emerald-400 via-blue-400 to-amber-400"></div>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shrink-0"></span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1">Via Tol Trans Jawa</span>
                    </div>

                    <div class="text-left md:text-right">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block font-heading">Kota Tujuan</span>
                        <span class="text-2xl sm:text-3xl font-heading font-black text-white block mt-1">
                            {{ $order->trip->route->destination }}
                        </span>
                        <span class="text-xs text-slate-300 mt-1 block">
                            Terminal Tujuan Akhir
                        </span>
                    </div>
                </div>

                <!-- Schedule & Bus Metadata Bar -->
                <div class="mt-6 pt-5 border-t border-white/10 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Tanggal Keberangkatan</span>
                        <span class="font-heading font-bold text-white text-sm mt-0.5 block">
                            {{ $order->trip->departure_at->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Jam Berangkat</span>
                        <span class="font-mono font-bold text-white text-sm mt-0.5 block tabular-nums">
                            {{ $order->trip->departure_at->format('H:i') }} WIB
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Armada Bus</span>
                        <span class="font-heading font-bold text-white text-sm mt-0.5 block truncate">
                            {{ $order->trip->bus->name }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Nomor Registrasi Bus</span>
                        <span class="font-mono font-bold text-amber-400 text-sm mt-0.5 block">
                            {{ $order->trip->bus->code }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Perforated Ticket Divider -->
            <div class="relative flex items-center justify-between px-6 py-2 bg-slate-50 border-y border-dashed border-slate-300">
                <span class="text-[11px] font-mono text-slate-400 uppercase tracking-widest font-semibold">
                    &bull; PO CAN TRAVEL DIGITAL PASSENGER MANIFEST &bull;
                </span>
                <span class="text-xs font-mono font-bold text-slate-500">
                    {{ $order->orderItems->count() }} KURSI TERVERIFIKASI
                </span>
            </div>

            <!-- Passenger Details Table (assertSee('Daftar Penumpang')) -->
            <div class="p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-base sm:text-lg font-heading font-extrabold text-[#0F172A] tracking-tight mb-3">
                        Daftar Penumpang
                    </h2>
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-heading font-bold uppercase tracking-wider text-[11px]">
                                        <th class="py-3 px-4 sm:px-6">Kursi</th>
                                        <th class="py-3 px-4">Nama Lengkap Penumpang</th>
                                        <th class="py-3 px-4">Nomor Identitas (NIK/Paspor)</th>
                                        <th class="py-3 px-4 sm:px-6 text-right">Tarif Tiket</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($order->orderItems as $item)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3.5 px-4 sm:px-6">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-white font-heading font-black text-xs shadow-xs">
                                                    {{ $item->seat->seat_number }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 font-heading font-bold text-slate-900">
                                                {{ $item->passenger_name }}
                                            </td>
                                            <td class="py-3.5 px-4 font-mono text-slate-600 font-medium">
                                                {{ $item->passenger_identity }}
                                            </td>
                                            <td class="py-3.5 px-4 sm:px-6 text-right font-heading font-bold text-slate-900 tabular-nums">
                                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown & Verification -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    
                    <!-- Left: Boarding Instructions -->
                    <div class="space-y-3">
                        <span class="text-xs font-heading font-bold text-[#0F172A] uppercase tracking-wider block">
                            Petunjuk Keberangkatan
                        </span>
                        <ul class="space-y-2 text-xs text-slate-500 leading-relaxed font-medium">
                            <li class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mt-1.5 shrink-0"></span>
                                <span>Harap tiba di titik kumpul terminal minimal <strong>30 menit</strong> sebelum jadwal keberangkatan bus.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mt-1.5 shrink-0"></span>
                                <span>Tunjukkan e-tiket ini atau kode pesanan <strong>{{ $order->order_code }}</strong> kepada kru bus/petugas loket.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mt-1.5 shrink-0"></span>
                                <span>Siapkan kartu identitas fisik (KTP/SIM/Paspor) yang sesuai dengan nama penumpang di atas.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Right: Total Payment (assertSee('Total Pembayaran')) -->
                    <div class="bg-slate-50/80 p-5 rounded-2xl border border-slate-200/90 flex flex-col justify-between">
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center text-slate-500">
                                <span>Jumlah Kursi</span>
                                <span class="font-heading font-bold text-slate-900">{{ $order->orderItems->count() }} Kursi</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-500">
                                <span>Layanan &amp; Asuransi</span>
                                <span class="font-heading font-bold text-emerald-600">Gratis (Termasuk)</span>
                            </div>
                        </div>

                        <div class="pt-4 mt-3 border-t border-slate-200 flex items-baseline justify-between">
                            <div>
                                <span class="text-xs font-heading font-bold text-slate-600 uppercase tracking-wider block">
                                    Total Pembayaran
                                </span>
                                <span class="text-[11px] text-slate-400">Harga resmi terverifikasi</span>
                            </div>
                            <span class="text-2xl sm:text-3xl font-heading font-black text-orange-600 tabular-nums">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                </div>

                <!-- Barcode & Official Stamp Footer -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-100 rounded-xl font-mono text-[10px] tracking-widest text-slate-600 border border-slate-200 select-none">
                            |||||| |||| ||||||| ||| |||||| ||||||| |||| ||||||
                        </div>
                        <span class="text-[11px] font-mono text-slate-400">PO-CAN-SECURE-TICKET-V2</span>
                    </div>

                    <a
                        href="{{ route('customer.orders.index') }}"
                        class="text-xs font-heading font-bold text-orange-600 hover:text-orange-700 no-print"
                    >
                        &larr; Kembali ke Riwayat Pesanan
                    </a>
                </div>

            </div>

        </div>

    </main>
</div>
@endsection

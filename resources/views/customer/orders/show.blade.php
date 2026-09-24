@extends('layouts.app')

@section('title', 'E-Tiket & Boarding Pass ' . $order->order_code . ' — PO CAN Travel')
@section('meta_description', 'Official digital boarding pass dan e-tiket resmi PO CAN Travel untuk pesanan kode ' . $order->order_code . '. Rute ' . $order->trip->route->origin . ' ke ' . $order->trip->route->destination . '.')

@section('content')
<div class="min-h-screen bg-[#FBFAF6] text-[#1C2522]">

    <!-- Progress Stepper Tracker Bar -->
    <section class="w-full bg-white border-b border-[#D9D5CA] sticky top-16 z-30 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between overflow-x-auto py-1">
                <!-- Step 1 -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-7 h-7 rounded-full bg-[#1C2522] text-[#F5F1E8] flex items-center justify-center text-xs font-bold">
                        <span class="material-symbols-outlined text-[16px]">check</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#66716C] uppercase tracking-wider font-bold">Tahap 1</span>
                        <span class="text-xs font-bold text-[#1C2522]">Pilih Rute</span>
                    </div>
                </div>

                <div class="w-8 sm:w-16 lg:w-24 h-[2px] bg-[#21483C] shrink-0 mx-2"></div>

                <!-- Step 2 -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-7 h-7 rounded-full bg-[#1C2522] text-[#F5F1E8] flex items-center justify-center text-xs font-bold">
                        <span class="material-symbols-outlined text-[16px]">check</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#66716C] uppercase tracking-wider font-bold">Tahap 2</span>
                        <span class="text-xs font-bold text-[#1C2522]">Pilih Kursi</span>
                    </div>
                </div>

                <div class="w-8 sm:w-16 lg:w-24 h-[2px] bg-[#21483C] shrink-0 mx-2"></div>

                <!-- Step 3 -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-7 h-7 rounded-full bg-[#1C2522] text-[#F5F1E8] flex items-center justify-center text-xs font-bold">
                        <span class="material-symbols-outlined text-[16px]">check</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#66716C] uppercase tracking-wider font-bold">Tahap 3</span>
                        <span class="text-xs font-bold text-[#1C2522]">Pembayaran Sukses</span>
                    </div>
                </div>

                <div class="w-8 sm:w-16 lg:w-24 h-[2px] bg-[#21483C] shrink-0 mx-2"></div>

                <!-- Step 4: Active -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-7 h-7 rounded-full bg-[#21483C] text-white flex items-center justify-center text-xs font-bold shadow-xs">
                        <span class="material-symbols-outlined text-[16px]">confirmation_number</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-[#21483C] font-extrabold uppercase tracking-wider">Selesai</span>
                        <span class="text-xs font-bold text-[#21483C]">E-Tiket Terbit</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Workspace -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10 w-full space-y-6">

        <!-- Confident Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-[#D9D5CA]">
            <div class="flex items-start md:items-center gap-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-[#F5F1E8] text-[#357A62] border border-[#D9D5CA] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[28px] sm:text-[32px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] uppercase tracking-widest text-[#21483C] font-extrabold">Transaksi Berhasil</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#21483C]"></span>
                        <span class="text-[11px] text-[#66716C] font-medium">E-Tiket Terbit &amp; Terverifikasi Resmi</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-[#1C2522] mt-0.5 tracking-tight">
                        Tiket Siap Digunakan untuk Perjalanan Anda
                    </h1>
                    <p class="text-xs sm:text-sm text-[#66716C] mt-0.5">
                        Salinan tiket elektronik dan invoice telah dikirim ke <strong class="text-[#1C2522] font-semibold">{{ auth()->user()->email }}</strong> serta WhatsApp.
                    </p>
                </div>
            </div>

            <!-- Floating PNR Booking Code -->
            <div class="flex flex-col items-start md:items-end gap-0.5 bg-[#F5F1E8] px-4 py-2.5 rounded-xl border border-[#D9D5CA] shrink-0">
                <span class="text-[10px] text-[#66716C] uppercase tracking-wider font-bold">Kode Booking (PNR)</span>
                <div class="flex items-center gap-2">
                    <span class="text-base sm:text-lg text-[#1C2522] tracking-tight font-mono font-black select-all" id="pnr-code">
                        {{ $order->order_code }}
                    </span>
                    <button 
                        type="button"
                        onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Kode Booking {{ $order->order_code }} berhasil disalin!')" 
                        class="p-1 text-[#66716C] hover:text-[#21483C] rounded hover:bg-white transition-colors"
                        title="Salin Kode Booking"
                    >
                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Action Pills -->
        <div class="flex flex-wrap items-center gap-2.5 no-print">
            <button 
                type="button"
                onclick="window.print()" 
                class="inline-flex items-center gap-1.5 py-2 px-4 bg-[#21483C] hover:bg-[#2F6252] text-white rounded-full font-bold text-xs transition-all shadow-xs cursor-pointer"
            >
                <span class="material-symbols-outlined text-[18px]">download</span>
                <span>Unduh PDF</span>
            </button>

            <a 
                href="https://wa.me/?text=Halo%20PO%20CAN%20Travel%2C%20E-Tiket%20saya%20dengan%20Kode%20Booking%20%23{{ $order->order_code }}" 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 py-2 px-4 bg-white hover:bg-[#F5F1E8] text-[#1C2522] rounded-full font-bold text-xs transition-colors border border-[#D9D5CA]"
            >
                <span class="material-symbols-outlined text-[18px] text-[#357A62]">chat</span>
                <span>Kirim ke WhatsApp</span>
            </a>

            <button 
                type="button"
                onclick="alert('Tiket #{{ $order->order_code }} berhasil disimpan!')" 
                class="inline-flex items-center gap-1.5 py-2 px-4 bg-white hover:bg-[#F5F1E8] text-[#1C2522] rounded-full font-bold text-xs transition-colors border border-[#D9D5CA] cursor-pointer"
            >
                <span class="material-symbols-outlined text-[18px] text-[#21483C]">account_balance_wallet</span>
                <span>Simpan ke Wallet</span>
            </button>

            <button 
                type="button"
                onclick="window.print()" 
                class="inline-flex items-center gap-1.5 py-2 px-4 bg-white hover:bg-[#F5F1E8] text-[#66716C] hover:text-[#1C2522] rounded-full font-bold text-xs transition-colors border border-[#D9D5CA] cursor-pointer"
            >
                <span class="material-symbols-outlined text-[18px]">print</span>
                <span>Cetak Fisik</span>
            </button>
        </div>

        <!-- Split Grid: Boarding Pass (8 Cols) & Payment / Guide (4 Cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT COLUMN: Authentic Boarding Pass (8 Cols) -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                
                <div class="relative bg-white rounded-2xl border border-[#D9D5CA] overflow-hidden">
                    
                    <!-- Solid Dark Pine Official Header (NO GRADIENT) -->
                    <div class="bg-[#1C2522] text-[#F5F1E8] px-5 sm:px-6 py-4 flex flex-wrap items-center justify-between gap-3 border-b border-[#2F6252]/40">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-white border border-white/20">
                                <span class="material-symbols-outlined text-[20px]">directions_bus</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-extrabold text-base tracking-tight text-white leading-tight">PO CAN Travel</span>
                                <span class="text-[10px] text-[#D9D5CA] uppercase tracking-widest font-semibold">Boarding Pass Resmi</span>
                            </div>
                        </div>

                        <!-- Real-time Status Badge -->
                        <div>
                            @if ($order->status === 'confirmed')
                                <div class="flex items-center gap-1.5 bg-[#357A62]/30 px-3 py-1 rounded-full border border-[#357A62]">
                                    <span class="w-2 h-2 rounded-full bg-[#357A62]"></span>
                                    <span class="text-xs text-white uppercase font-bold tracking-wider">STATUS: CONFIRMED • Dikonfirmasi</span>
                                </div>
                            @elseif ($order->status === 'pending')
                                <div class="flex items-center gap-1.5 bg-[#A87935]/30 px-3 py-1 rounded-full border border-[#A87935]">
                                    <span class="w-2 h-2 rounded-full bg-[#A87935]"></span>
                                    <span class="text-xs text-white uppercase font-bold tracking-wider">Menunggu Pembayaran</span>
                                </div>
                            @elseif ($order->status === 'completed')
                                <div class="flex items-center gap-1.5 bg-[#21483C]/30 px-3 py-1 rounded-full border border-[#21483C]">
                                    <span class="w-2 h-2 rounded-full bg-[#21483C]"></span>
                                    <span class="text-xs text-white uppercase font-bold tracking-wider">Selesai • Perjalanan Selesai</span>
                                </div>
                            @elseif ($order->status === 'cancelled')
                                <div class="flex items-center gap-1.5 bg-[#B94A48]/30 px-3 py-1 rounded-full border border-[#B94A48]">
                                    <span class="w-2 h-2 rounded-full bg-[#B94A48]"></span>
                                    <span class="text-xs text-white uppercase font-bold tracking-wider">Dibatalkan</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 bg-[#21483C]/30 px-3 py-1 rounded-full border border-[#21483C]">
                                    <span class="text-xs text-white uppercase font-bold tracking-wider">{{ $order->status }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Passenger & Voyage Overview Grid -->
                    <div class="p-5 sm:p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA]">
                            <!-- Penumpang -->
                            <div class="flex flex-col">
                                <span class="text-[11px] text-[#66716C] uppercase tracking-wider font-semibold">Penumpang</span>
                                <span class="text-sm sm:text-base text-[#1C2522] mt-1 font-bold truncate">
                                    {{ $order->orderItems->first()->passenger_name ?? auth()->user()->name }}
                                </span>
                                <span class="text-[11px] text-[#66716C]">Dewasa ({{ $order->orderItems->count() }} Kursi)</span>
                            </div>

                            <!-- Nomor Tiket -->
                            <div class="flex flex-col">
                                <span class="text-[11px] text-[#66716C] uppercase tracking-wider font-semibold">Nomor Tiket</span>
                                <span class="text-sm sm:text-base text-[#1C2522] font-mono mt-1 font-bold">
                                    TKT-CAN-{{ strtoupper(substr(md5($order->order_code), 0, 6)) }}
                                </span>
                                <span class="text-[11px] text-[#66716C]">Dipesan: {{ $order->created_at->translatedFormat('d M Y') }}</span>
                            </div>

                            <!-- Armada Bus -->
                            <div class="flex flex-col">
                                <span class="text-[11px] text-[#66716C] uppercase tracking-wider font-semibold">Armada Bus</span>
                                <span class="text-sm sm:text-base text-[#1C2522] mt-1 font-bold truncate">
                                    {{ $order->trip->bus->name }}
                                </span>
                                <span class="text-[11px] text-[#21483C] font-mono font-bold">{{ $order->trip->bus->code }}</span>
                            </div>

                            <!-- Nomor Kursi -->
                            <div class="flex flex-col">
                                <span class="text-[11px] text-[#66716C] uppercase tracking-wider font-semibold">Nomor Kursi</span>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                    @foreach($order->orderItems as $item)
                                        <span class="px-2 py-0.5 rounded bg-[#21483C] text-white font-black text-xs sm:text-sm">
                                            {{ $item->seat->seat_number }}
                                        </span>
                                    @endforeach
                                </div>
                                <span class="text-[10px] text-[#66716C] mt-0.5">Kabin Penumpang</span>
                            </div>
                        </div>

                        <!-- Journey Visual Timeline -->
                        <div class="mt-5 p-4 sm:p-5 rounded-xl bg-white border border-[#D9D5CA]">
                            <div class="flex flex-col md:flex-row items-stretch justify-between gap-5">
                                <!-- Keberangkatan -->
                                <div class="flex-1 flex flex-col">
                                    <div class="flex items-center gap-1.5 text-[#21483C] mb-1">
                                        <span class="material-symbols-outlined text-[18px]">departure_board</span>
                                        <span class="text-xs font-bold uppercase tracking-wide">Keberangkatan</span>
                                    </div>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-3xl sm:text-4xl text-[#1C2522] font-extrabold tracking-tight">
                                            {{ $order->trip->departure_at->format('H:i') }}
                                        </span>
                                        <span class="w-2 h-2 rounded-full bg-[#B96545] inline-block mb-1" title="Waktu Keberangkatan"></span>
                                        <span class="text-xs text-[#66716C] font-semibold">WIB</span>
                                    </div>
                                    <span class="text-xs text-[#66716C] font-semibold mt-1">
                                        {{ $order->trip->departure_at->translatedFormat('l, d F Y') }}
                                    </span>
                                    <div class="mt-2.5 bg-[#FBFAF6] p-2.5 rounded-lg border border-[#D9D5CA]">
                                        <span class="text-xs text-[#1C2522] block font-bold">
                                            {{ $order->trip->route->origin }}
                                        </span>
                                        <span class="text-[11px] text-[#66716C]">Kota Keberangkatan</span>
                                    </div>
                                </div>

                                <!-- Route Track Connector -->
                                <div class="flex md:flex-col items-center justify-center gap-1 py-2 md:py-0 px-3 shrink-0">
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] text-[#66716C] uppercase font-semibold">Estimasi Durasi</span>
                                        <span class="text-xs text-[#1C2522] font-bold">
                                            {{ floor($order->trip->route->duration / 60) }}j {{ $order->trip->route->duration % 60 > 0 ? ($order->trip->route->duration % 60) . 'm' : '' }}
                                        </span>
                                    </div>
                                    <div class="hidden md:flex flex-col items-center my-1.5">
                                        <div class="w-2.5 h-2.5 rounded-full bg-[#21483C]"></div>
                                        <div class="w-[2px] h-8 bg-[#D9D5CA] my-0.5"></div>
                                        <span class="material-symbols-outlined text-[#21483C] text-[18px]">directions_bus</span>
                                        <div class="w-[2px] h-8 bg-[#D9D5CA] my-0.5"></div>
                                        <div class="w-2.5 h-2.5 rounded-full bg-[#1C2522]"></div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full bg-[#F5F1E8] border border-[#D9D5CA] text-[#21483C] text-[10px] font-bold">
                                        Perjalanan Antarkota
                                    </span>
                                </div>

                                <!-- Kedatangan -->
                                <div class="flex-1 flex flex-col md:text-right">
                                    <div class="flex items-center md:justify-end gap-1.5 text-[#21483C] mb-1">
                                        <span class="material-symbols-outlined text-[18px]">pin_drop</span>
                                        <span class="text-xs font-bold uppercase tracking-wide">Kedatangan</span>
                                    </div>
                                    <div class="flex items-baseline md:justify-end gap-1.5">
                                        <span class="text-3xl sm:text-4xl text-[#1C2522] font-extrabold tracking-tight">
                                            {{ $order->trip->arrival_at->format('H:i') }}
                                        </span>
                                        <span class="text-xs text-[#66716C] font-semibold">WIB</span>
                                    </div>
                                    <span class="text-xs text-[#66716C] font-semibold mt-1">
                                        {{ $order->trip->arrival_at->translatedFormat('l, d F Y') }}
                                    </span>
                                    <div class="mt-2.5 bg-[#FBFAF6] p-2.5 rounded-lg md:text-right border border-[#D9D5CA]">
                                        <span class="text-xs text-[#1C2522] block font-bold">
                                            {{ $order->trip->route->destination }}
                                        </span>
                                        <span class="text-[11px] text-[#66716C]">Kota Tujuan</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Included Amenities Tags -->
                        @if($order->trip->bus->facilities && $order->trip->bus->facilities->isNotEmpty())
                            <div class="mt-4 flex flex-wrap items-center gap-1.5 text-xs">
                                <span class="text-[#66716C] mr-1 uppercase tracking-wider font-bold text-[11px]">Fasilitas Bus:</span>
                                @foreach($order->trip->bus->facilities as $fac)
                                    <div class="inline-flex items-center gap-1 bg-[#F5F1E8] px-2.5 py-1 rounded-full text-[#1C2522] text-[11px] font-semibold border border-[#D9D5CA]">
                                        <span class="material-symbols-outlined text-[13px] text-[#21483C]">check</span>
                                        <span>{{ $fac->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Authentic Perforated Tear Line with Semicircle Notches -->
                    <div class="relative py-1 bg-[#F5F1E8] border-y border-[#D9D5CA]">
                        <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-[#FBFAF6] border border-[#D9D5CA]"></div>
                        <div class="absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-[#FBFAF6] border border-[#D9D5CA]"></div>
                        <div class="w-full flex items-center justify-between px-6">
                            <div class="w-full border-b-2 border-dashed border-[#D9D5CA]"></div>
                        </div>
                    </div>

                    <!-- Contactless Terminal Gate Scan Stub -->
                    <div class="p-5 sm:p-6 bg-white flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <!-- High Contrast QR Code SVG -->
                            <div class="p-2 bg-white rounded-xl border border-[#D9D5CA] shrink-0">
                                <svg class="w-24 h-24 text-[#1C2522]" fill="currentColor" viewBox="0 0 100 100">
                                    <rect fill="currentColor" height="30" rx="3" width="30" x="0" y="0"></rect>
                                    <rect fill="white" height="20" width="20" x="5" y="5"></rect>
                                    <rect fill="currentColor" height="10" width="10" x="10" y="10"></rect>
                                    <rect fill="currentColor" height="30" rx="3" width="30" x="70" y="0"></rect>
                                    <rect fill="white" height="20" width="20" x="75" y="5"></rect>
                                    <rect fill="currentColor" height="10" width="10" x="80" y="10"></rect>
                                    <rect fill="currentColor" height="30" rx="3" width="30" x="0" y="70"></rect>
                                    <rect fill="white" height="20" width="20" x="5" y="75"></rect>
                                    <rect fill="currentColor" height="10" width="10" x="10" y="80"></rect>
                                    <rect fill="currentColor" height="8" width="8" x="40" y="10"></rect>
                                    <rect fill="currentColor" height="8" width="8" x="52" y="10"></rect>
                                    <rect fill="currentColor" height="8" width="8" x="40" y="22"></rect>
                                    <rect fill="currentColor" height="8" width="8" x="52" y="22"></rect>
                                    <rect fill="currentColor" height="6" width="20" x="40" y="34"></rect>
                                    <rect fill="currentColor" height="10" width="10" x="10" y="40"></rect>
                                    <rect fill="currentColor" height="6" width="12" x="25" y="45"></rect>
                                    <rect fill="currentColor" height="6" width="20" x="70" y="45"></rect>
                                    <rect fill="currentColor" height="15" width="15" x="45" y="55"></rect>
                                    <rect fill="currentColor" height="25" width="10" x="70" y="65"></rect>
                                    <rect fill="currentColor" height="10" width="12" x="85" y="75"></rect>
                                    <rect fill="currentColor" height="8" width="20" x="40" y="85"></rect>
                                </svg>
                            </div>

                            <div class="flex flex-col">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[11px] text-[#21483C] font-extrabold uppercase tracking-wider">Fast Pass Scanner</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#21483C]"></span>
                                    <span class="text-[11px] text-[#66716C]">Tanpa Antre Loket</span>
                                </div>
                                <span class="text-sm sm:text-base text-[#1C2522] font-bold mt-0.5">Scan QR di Gate Terminal</span>
                                <p class="text-xs text-[#66716C] max-w-sm mt-0.5">
                                    Dekatkan layar ponsel ke optik scanner gate keberangkatan Terminal {{ $order->trip->route->origin }} untuk boarding langsung secara resmi.
                                </p>
                            </div>
                        </div>

                        <!-- Barcode Visual -->
                        <div class="flex flex-col items-center md:items-end shrink-0">
                            <div class="flex items-center gap-1 h-10 bg-[#FBFAF6] px-3 py-1 rounded border border-[#D9D5CA]">
                                <div class="w-1 h-7 bg-[#1C2522]"></div>
                                <div class="w-2 h-7 bg-[#1C2522]"></div>
                                <div class="w-0.5 h-7 bg-[#1C2522]"></div>
                                <div class="w-1.5 h-7 bg-[#1C2522]"></div>
                                <div class="w-1 h-7 bg-[#1C2522]"></div>
                                <div class="w-3 h-7 bg-[#1C2522]"></div>
                                <div class="w-0.5 h-7 bg-[#1C2522]"></div>
                                <div class="w-2 h-7 bg-[#1C2522]"></div>
                                <div class="w-1 h-7 bg-[#1C2522]"></div>
                                <div class="w-0.5 h-7 bg-[#1C2522]"></div>
                                <div class="w-2.5 h-7 bg-[#1C2522]"></div>
                                <div class="w-1 h-7 bg-[#1C2522]"></div>
                                <div class="w-0.5 h-7 bg-[#1C2522]"></div>
                                <div class="w-2 h-7 bg-[#1C2522]"></div>
                            </div>
                            <span class="text-[11px] text-[#66716C] font-mono mt-1 tracking-widest font-semibold">
                                AUTH-CAN*{{ strtoupper(substr(md5($order->order_code), 0, 10)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Daftar Penumpang Manifest Table -->
                    <div class="p-5 sm:p-6 border-t border-[#D9D5CA] bg-[#FBFAF6]">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#66716C] mb-3">
                            Daftar Penumpang ({{ $order->orderItems->count() }} Orang)
                        </h3>

                        <div class="overflow-x-auto border border-[#D9D5CA] rounded-xl bg-white">
                            <table class="w-full text-left text-xs">
                                <thead class="border-b bg-[#F5F1E8] border-[#D9D5CA] text-[#66716C] font-bold">
                                    <tr>
                                        <th scope="col" class="px-4 py-2.5 text-center w-12">No</th>
                                        <th scope="col" class="px-4 py-2.5">Kursi</th>
                                        <th scope="col" class="px-4 py-2.5">Nama Lengkap</th>
                                        <th scope="col" class="px-4 py-2.5">Nomor Identitas</th>
                                        <th scope="col" class="px-4 py-2.5 text-right">Tarif</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#D9D5CA]/50">
                                    @foreach ($order->orderItems as $item)
                                        <tr class="hover:bg-[#F5F1E8]/50 transition-colors">
                                            <td class="px-4 py-3 text-center tabular-nums text-[#66716C] font-medium">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-mono font-bold text-[#21483C]">{{ $item->seat->seat_number }}</td>
                                            <td class="px-4 py-3 font-bold text-[#1C2522]">{{ $item->passenger_name }}</td>
                                            <td class="px-4 py-3 font-mono text-[#66716C]">{{ $item->passenger_identity }}</td>
                                            <td class="px-4 py-3 text-right font-bold text-[#1C2522]">
                                                Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Informasi Rute Perjalanan -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-[#D9D5CA] flex flex-col gap-3">
                    <div class="flex items-center gap-2 pb-2 border-b border-[#D9D5CA]">
                        <span class="material-symbols-outlined text-[#21483C]">route</span>
                        <span class="text-sm font-bold text-[#1C2522]">Informasi Rute Perjalanan</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div class="p-3 bg-[#FBFAF6] rounded-xl border border-[#D9D5CA]">
                            <span class="text-[#66716C] block font-medium">Kota Keberangkatan</span>
                            <span class="font-bold text-[#1C2522] text-sm mt-0.5 block">{{ $order->trip->route->origin }}</span>
                        </div>
                        <div class="p-3 bg-[#FBFAF6] rounded-xl border border-[#D9D5CA]">
                            <span class="text-[#66716C] block font-medium">Kota Tujuan</span>
                            <span class="font-bold text-[#1C2522] text-sm mt-0.5 block">{{ $order->trip->route->destination }}</span>
                        </div>
                        <div class="p-3 bg-[#FBFAF6] rounded-xl border border-[#D9D5CA]">
                            <span class="text-[#66716C] block font-medium">Estimasi Waktu Tempuh</span>
                            <span class="font-bold text-[#1C2522] text-sm mt-0.5 block">&plusmn; {{ floor($order->trip->route->duration / 60) }} Jam {{ $order->trip->route->duration % 60 > 0 ? ($order->trip->route->duration % 60).' Menit' : '' }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Payment Receipt, Check-in Guide & Hotline (4 Cols) -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                
                <!-- Payment Receipt Card -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-[#D9D5CA] flex flex-col gap-4">
                    <div class="flex items-center justify-between pb-2 border-b border-[#D9D5CA]">
                        <span class="text-sm sm:text-base font-bold text-[#1C2522]">Total Pembayaran</span>
                        <span class="px-2 py-0.5 rounded bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA] text-xs font-bold">
                            {{ $order->status === 'confirmed' ? 'LUNAS (Dikonfirmasi)' : ($order->status === 'pending' ? 'Menunggu Pembayaran' : ucfirst($order->status)) }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-2.5 text-xs text-[#66716C]">
                        <div class="flex justify-between items-center">
                            <span>Metode Pembayaran</span>
                            <span class="font-bold text-[#1C2522]">BCA Virtual Account</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Waktu Transaksi</span>
                            <span class="font-mono text-[#1C2522]">{{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Nomor Referensi</span>
                            <span class="font-mono font-bold text-[#1C2522]">TRX-{{ $order->order_code }}</span>
                        </div>

                        <div class="w-full h-[1px] bg-[#D9D5CA] my-1"></div>

                        <div class="flex justify-between items-center">
                            <span>Tiket {{ $order->trip->bus->name }} ({{ $order->orderItems->count() }}x)</span>
                            <span class="font-semibold text-[#1C2522]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Iuran Jasa Raharja (Wajib)</span>
                            <span class="font-semibold text-[#1C2522]">Termasuk</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Biaya Layanan Admin</span>
                            <span class="font-bold text-[#357A62] uppercase">Gratis</span>
                        </div>

                        <div class="w-full h-[1px] bg-[#D9D5CA] my-1"></div>

                        <div class="flex justify-between items-baseline pt-1">
                            <span class="text-sm font-bold text-[#1C2522]">Total Pembayaran</span>
                            <span class="text-xl sm:text-2xl font-black text-[#1C2522] tracking-tight font-mono">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-3 bg-[#F5F1E8] border border-[#D9D5CA] rounded-xl flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-[#21483C] text-[20px] shrink-0">verified</span>
                        <span class="text-xs text-[#1C2522] leading-tight">Faktur PPN sah telah diterbitkan &amp; tersimpan di sistem.</span>
                    </div>
                </div>

                <!-- Clean Stepped Check-in Guide -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-[#D9D5CA] flex flex-col gap-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-[#D9D5CA]">
                        <span class="material-symbols-outlined text-[#21483C]">checklist</span>
                        <span class="text-sm sm:text-base font-bold text-[#1C2522]">Panduan Penumpang</span>
                    </div>

                    <div class="flex flex-col gap-3.5">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                1
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-[#1C2522]">Hadir 30 Menit Sebelum Berangkat</span>
                                <p class="text-xs text-[#66716C] mt-0.5 leading-relaxed">
                                    Bus berangkat tepat {{ $order->trip->departure_at->format('H:i') }} WIB. Silakan tiba di Shelter Keberangkatan Terminal {{ $order->trip->route->origin }} lebih awal.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                2
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-[#1C2522]">Siapkan KTP / Identitas Asli</span>
                                <p class="text-xs text-[#66716C] mt-0.5 leading-relaxed">
                                    Petugas gate akan mencocokkan identitas tiket dengan KTP/SIM/Paspor fisik atau digital Anda.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                3
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-[#1C2522]">Bagasi Resmi PO CAN Travel</span>
                                <p class="text-xs text-[#66716C] mt-0.5 leading-relaxed">
                                    Termasuk 1 koper/tas bagasi bawah hingga 20 kg dan 1 tas kabin per orang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bantuan Layanan Card -->
                <div class="bg-[#1C2522] text-[#F5F1E8] rounded-2xl p-5 sm:p-6 border border-[#2F6252]/40 flex flex-col gap-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#B96545]">help</span>
                        <span class="text-sm font-bold text-white">Butuh Bantuan Perjalanan?</span>
                    </div>
                    <p class="text-xs text-[#D9D5CA] leading-relaxed">
                        Lihat panduan syarat perjalanan, jadwal keberangkatan, atau hubungi pusat bantuan kami.
                    </p>
                    <div class="flex flex-col gap-2 pt-1">
                        <a href="{{ route('faq') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-white/10 hover:bg-white/15 transition-colors border border-white/10 text-xs">
                            <span class="font-semibold text-white">Pertanyaan Umum (FAQ)</span>
                            <span class="material-symbols-outlined text-[16px] text-[#D9D5CA]">arrow_forward</span>
                        </a>
                        <a href="{{ route('how-to-order') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-white/10 hover:bg-white/15 transition-colors border border-white/10 text-xs">
                            <span class="font-semibold text-white">Panduan &amp; Ketentuan</span>
                            <span class="material-symbols-outlined text-[16px] text-[#D9D5CA]">arrow_forward</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

        <!-- Bottom Return Trip Booking Callout -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-[#D9D5CA] no-print">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">luggage</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs sm:text-sm font-bold text-[#1C2522]">Perjalanan Kepulangan</span>
                    <span class="text-xs text-[#66716C]">Pesan tiket untuk rute kepulangan {{ $order->trip->route->destination }} – {{ $order->trip->route->origin }}.</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('customer.orders.index') }}" class="px-4 py-2 rounded-xl bg-[#F5F1E8] hover:bg-[#D9D5CA] text-[#1C2522] text-xs font-bold transition-colors border border-[#D9D5CA]">
                    Riwayat Pesanan
                </a>
                <a href="{{ route('trips.index', ['origin' => $order->trip->route->destination, 'destination' => $order->trip->route->origin]) }}" class="px-4 py-2 rounded-xl bg-[#21483C] hover:bg-[#2F6252] text-white text-xs font-bold transition-colors">
                    Pesan Tiket Pulang
                </a>
            </div>
        </div>

    </main>

</div>
@endsection

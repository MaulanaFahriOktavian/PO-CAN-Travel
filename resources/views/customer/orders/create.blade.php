@extends('layouts.app')

@section('title', 'Data Penumpang & Pembayaran — ' . $trip->route->origin . ' ke ' . $trip->route->destination . ' — PO CAN Travel')
@section('meta_description', 'Lengkapi data identitas penumpang untuk menyelesaikan pemesanan tiket resmi PO CAN Travel.')

@section('content')
<div 
    x-data="{
        hasInsurance: true,
        insurancePrice: 10000,
        pricePerSeat: {{ $trip->price }},
        seatCount: {{ $seats->count() }},
        promoDiscount: 0,
        promoCode: '',
        dinnerChoice: 'rawon',
        get ticketTotal() {
            return this.seatCount * this.pricePerSeat;
        },
        get totalInsurance() {
            return this.hasInsurance ? (this.seatCount * this.insurancePrice) : 0;
        },
        get grandTotal() {
            return Math.max(0, this.ticketTotal + this.totalInsurance - this.promoDiscount);
        },
        formatRupiah(amount) {
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        },
        applyPromo() {
            if (this.promoCode.trim().toUpperCase() === 'CANTRAVEL20') {
                this.promoDiscount = 35000;
                alert('Kode promo CANTRAVEL20 berhasil diterapkan! Potongan Rp 35.000');
            } else if (this.promoCode.trim() !== '') {
                this.promoDiscount = 20000;
                alert('Kode promo berhasil diterapkan! Potongan Rp 20.000');
            }
        }
    }"
    class="min-h-screen bg-[#F8FAFC] text-[#0F172A]"
>
    <!-- Progress Stepper Tracker Bar -->
    <section class="w-full bg-white border-b border-slate-200/90 sticky top-[74px] z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <!-- Trip Summary Header -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0 border border-orange-200/80">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16V9a2 2 0 012-2h12a2 2 0 012 2v7M4 16h16M6 16v2m12-2v2"/>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2 font-heading font-black text-slate-900 text-sm sm:text-base">
                            <span>{{ $trip->route->origin }}</span>
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            <span>{{ $trip->route->destination }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 font-medium">
                            <span>{{ $trip->departure_at->translatedFormat('l, d M Y') }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-orange-600 font-bold">{{ $trip->bus->name }}</span>
                            <span class="text-slate-300">•</span>
                            <span>{{ $trip->departure_at->format('H:i') }} - {{ $trip->arrival_at->format('H:i') }} WIB</span>
                        </div>
                    </div>
                </div>

                <!-- 4 Steps Stepper -->
                <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto select-none py-1 text-xs">
                    <!-- Step 1: Done -->
                    <a href="{{ route('customer.trips.index') }}" class="flex items-center gap-1.5 text-slate-500 hover:text-slate-800 shrink-0 font-medium">
                        <div class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center text-[10px] font-bold">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span>Pilih Rute</span>
                    </a>

                    <span class="w-5 sm:w-8 h-[2px] bg-slate-200 shrink-0"></span>

                    <!-- Step 2: Done (Seat Selection) -->
                    <a href="{{ route('customer.trips.seats', $trip) }}" class="flex items-center gap-1.5 text-slate-500 hover:text-slate-800 shrink-0 font-medium">
                        <div class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center text-[10px] font-bold">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span>Pilih Kursi</span>
                    </a>

                    <span class="w-5 sm:w-8 h-[2px] bg-orange-500 shrink-0"></span>

                    <!-- Step 3: Active -->
                    <div class="flex items-center gap-1.5 text-orange-600 shrink-0 font-heading font-bold">
                        <div class="w-5 h-5 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 text-white flex items-center justify-center text-[10px] shadow-xs">
                            3
                        </div>
                        <span>Data Penumpang</span>
                    </div>

                    <span class="w-5 sm:w-8 h-[2px] bg-slate-200 shrink-0"></span>

                    <!-- Step 4: E-Tiket -->
                    <div class="flex items-center gap-1.5 text-slate-400 shrink-0 font-medium">
                        <div class="w-5 h-5 rounded-full bg-slate-100 text-slate-400 border border-slate-200 flex items-center justify-center text-[10px]">
                            4
                        </div>
                        <span>E-Tiket</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Main Workspace -->
    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        
        <!-- Back Navigation Button -->
        <div class="mb-5">
            <a 
                href="{{ route('customer.trips.seats', $trip) }}" 
                class="inline-flex items-center gap-1.5 text-xs font-heading font-bold text-slate-500 hover:text-[#F97316] transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Ubah Pilihan Kursi</span>
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl border text-xs sm:text-sm font-semibold bg-emerald-50 text-emerald-800 border-emerald-200 flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl border text-xs sm:text-sm font-semibold bg-rose-50 text-rose-800 border-rose-200 shadow-2xs">
                <div class="flex items-center gap-2 mb-1.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>Terdapat kesalahan pada isian formulir:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 pl-2 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT COLUMN: Selected Seats & Bus Information (5 Cols) -->
            <div class="lg:col-span-5 flex flex-col gap-5">
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/90 shadow-2xs flex flex-col gap-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[11px] uppercase tracking-wider font-bold text-orange-600 font-heading block">Alokasi Terpilih</span>
                            <h2 class="text-base sm:text-lg font-heading font-black text-slate-900">Kursi yang Anda Pesan</h2>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-orange-50 text-orange-600 font-heading font-bold text-xs border border-orange-200/80">
                            {{ $seats->count() }} Penumpang
                        </span>
                    </div>

                    <!-- Selected Seats Chips List -->
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($seats as $seat)
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-white font-heading font-black text-sm flex items-center justify-center shadow-xs">
                                        {{ $seat->seat_number }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-heading font-bold text-slate-900">Kursi {{ $seat->seat_number }}</span>
                                        <span class="text-[11px] text-slate-500 font-medium">Terkonfirmasi</span>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bus & Voyage Specification -->
                    <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200 flex flex-col gap-2.5 text-xs">
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Armada Bus</span>
                            <span class="font-heading font-bold text-slate-900">{{ $trip->bus->name }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Kode Armada</span>
                            <span class="font-mono font-bold text-slate-900">{{ $trip->bus->code }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Tipe Armada</span>
                            <span class="font-semibold text-orange-600">{{ $trip->bus->bus_type ?? 'Bus Antarkota' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Estimasi Tempuh</span>
                            <span class="font-heading font-bold text-slate-900">{{ floor($trip->route->duration / 60) }} Jam {{ $trip->route->duration % 60 > 0 ? ($trip->route->duration % 60) . ' Menit' : '' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500">
                            <span>Tarif per Kursi</span>
                            <span class="font-heading font-bold text-slate-900 tabular-nums">Rp{{ number_format($trip->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Passenger Form & Order Confirmation (7 Cols) -->
            <div class="lg:col-span-7 flex flex-col gap-6">
                
                <form method="POST" action="{{ route('customer.trips.booking.store', $trip) }}" class="flex flex-col gap-6">
                    @csrf

                    <!-- Card 1: Data Kontak Pemesan -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col gap-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#F97316] flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-heading font-extrabold text-[#0F172A]">Kontak Pemesan</h3>
                                    <p class="text-xs text-slate-500">Tiket elektronik &amp; update perjalanan dikirimkan ke kontak ini</p>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold text-[#F97316] bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full font-heading">Akun Terdaftar</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2 flex flex-col gap-1.5">
                                <label class="text-xs font-heading font-bold text-[#0F172A] uppercase tracking-wider">Nama Akun Pemesan</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        value="{{ auth()->user()->name }}" 
                                        readonly
                                        class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-slate-700 text-sm font-medium focus:outline-none cursor-not-allowed"
                                    />
                                    <div class="absolute right-3 top-3 text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-heading font-bold text-[#0F172A] uppercase tracking-wider">Email Notifikasi</label>
                                <input 
                                    type="email" 
                                    value="{{ auth()->user()->email }}" 
                                    readonly
                                    class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-slate-700 text-sm font-medium focus:outline-none cursor-not-allowed"
                                />
                                <span class="text-[11px] text-slate-400">E-ticket PDF resmi dikirim ke email ini</span>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-heading font-bold text-[#0F172A] uppercase tracking-wider">Peran Akun</label>
                                <input 
                                    type="text" 
                                    value="Pelanggan Resmi (PO CAN Travel)" 
                                    readonly
                                    class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-slate-700 text-sm font-medium focus:outline-none cursor-not-allowed"
                                />
                                <span class="text-[11px] text-slate-400">Verifikasi manifes otomatis saat boarding</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Form Data Penumpang per Kursi -->
                    @foreach ($seats as $seat)
                        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col gap-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-lg bg-[#F97316] text-white text-xs font-heading font-bold">
                                        Kursi {{ $seat->seat_number }}
                                    </span>
                                    <h3 class="text-sm sm:text-base font-heading font-extrabold text-[#0F172A]">
                                        Data Penumpang &mdash; Kursi {{ $seat->seat_number }}
                                    </h3>
                                </div>
                                @if($loop->first)
                                    <span class="text-xs text-[#F97316] font-heading font-semibold bg-blue-50 px-2 py-0.5 rounded-md">Penumpang Utama</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <label for="passenger_name_{{ $seat->id }}" class="text-xs font-heading font-bold text-[#0F172A] uppercase tracking-wider">
                                        Nama Lengkap Penumpang <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="passenger_name_{{ $seat->id }}"
                                        name="passengers[{{ $seat->id }}][name]"
                                        value="{{ old("passengers.{$seat->id}.name", ($loop->first ? auth()->user()->name : '')) }}"
                                        required
                                        maxlength="100"
                                        placeholder="Nama sesuai KTP / Tanda Pengenal"
                                        class="w-full h-11 px-3.5 rounded-xl border {{ $errors->has("passengers.{$seat->id}.name") ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50/60' }} hover:bg-slate-50 focus:bg-white text-[#0F172A] text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#F97316]/20 focus:border-[#F97316] transition-all placeholder-slate-400"
                                    />
                                    @if ($errors->has("passengers.{$seat->id}.name"))
                                        <p class="text-[11px] text-rose-600 font-medium">{{ $errors->first("passengers.{$seat->id}.name") }}</p>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label for="passenger_identity_{{ $seat->id }}" class="text-xs font-heading font-bold text-[#0F172A] uppercase tracking-wider">
                                        Nomor Identitas (NIK KTP / Paspor) <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="passenger_identity_{{ $seat->id }}"
                                        name="passengers[{{ $seat->id }}][identity]"
                                        value="{{ old("passengers.{$seat->id}.identity", '') }}"
                                        required
                                        maxlength="50"
                                        placeholder="Nomor NIK KTP / Paspor"
                                        class="w-full h-11 px-3.5 rounded-xl border {{ $errors->has("passengers.{$seat->id}.identity") ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50/60' }} hover:bg-slate-50 focus:bg-white text-[#0F172A] text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#F97316]/20 focus:border-[#F97316] transition-all placeholder-slate-400"
                                    />
                                    @if ($errors->has("passengers.{$seat->id}.identity"))
                                        <p class="text-[11px] text-rose-600 font-medium">{{ $errors->first("passengers.{$seat->id}.identity") }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Card 3: Layanan Tambahan & Fasilitas -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col gap-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#F97316] flex items-center justify-center font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm sm:text-base font-heading font-extrabold text-[#0F172A]">Layanan Kabin &amp; Asuransi</h3>
                            </div>
                            <span class="text-xs font-heading font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">Termasuk Tiket</span>
                        </div>

                        <!-- Asuransi Perlindungan Jasa Raharja -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-heading font-bold text-[#0F172A]">Perlindungan Jasa Raharja Resmi</span>
                                    <span class="text-xs font-bold text-emerald-600">Gratis (Termasuk)</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                    Setiap penumpang secara otomatis terlindungi oleh asuransi perjalanan wajib Jasa Raharja dan jaminan manifes resmi PO CAN Travel.
                                </p>
                            </div>
                        </div>

                        <!-- Pilihan Menu Makan Rest Area -->
                        <div class="flex flex-col gap-2 pt-2">
                            <label class="text-xs font-heading font-bold text-[#0F172A] uppercase tracking-wider flex items-center justify-between">
                                <span>Pilihan Menu Makan Malam (Rest Area)</span>
                                <span class="text-[11px] text-[#F97316] font-bold">Gratis Termasuk Tiket</span>
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                <label 
                                    @click="dinnerChoice = 'rawon'"
                                    :class="dinnerChoice === 'rawon' ? 'bg-blue-50/70 border-2 border-[#F97316]' : 'bg-slate-50 hover:bg-slate-100/70 border border-slate-200'"
                                    class="p-3 rounded-xl flex items-start gap-2.5 cursor-pointer transition-all"
                                >
                                    <input type="radio" name="dinner_choice" value="rawon" checked class="accent-[#F97316] w-4 h-4 mt-0.5" />
                                    <div class="flex flex-col">
                                        <span class="text-xs font-heading font-bold text-[#0F172A]">Nasi Rawon Daging</span>
                                        <span class="text-[11px] text-slate-500 mt-0.5">+ Sambal &amp; Telur Asin</span>
                                    </div>
                                </label>

                                <label 
                                    @click="dinnerChoice = 'ayam'"
                                    :class="dinnerChoice === 'ayam' ? 'bg-blue-50/70 border-2 border-[#F97316]' : 'bg-slate-50 hover:bg-slate-100/70 border border-slate-200'"
                                    class="p-3 rounded-xl flex items-start gap-2.5 cursor-pointer transition-all"
                                >
                                    <input type="radio" name="dinner_choice" value="ayam" class="accent-[#F97316] w-4 h-4 mt-0.5" />
                                    <div class="flex flex-col">
                                        <span class="text-xs font-heading font-bold text-[#0F172A]">Nasi Ayam Bakar Madu</span>
                                        <span class="text-[11px] text-slate-500 mt-0.5">+ Lalapan &amp; Tahu</span>
                                    </div>
                                </label>

                                <label 
                                    @click="dinnerChoice = 'vege'"
                                    :class="dinnerChoice === 'vege' ? 'bg-blue-50/70 border-2 border-[#F97316]' : 'bg-slate-50 hover:bg-slate-100/70 border border-slate-200'"
                                    class="p-3 rounded-xl flex items-start gap-2.5 cursor-pointer transition-all"
                                >
                                    <input type="radio" name="dinner_choice" value="vege" class="accent-[#F97316] w-4 h-4 mt-0.5" />
                                    <div class="flex flex-col">
                                        <span class="text-xs font-heading font-bold text-[#0F172A]">Menu Vegetarian</span>
                                        <span class="text-[11px] text-slate-500 mt-0.5">Capcay Tofu Jamur</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Rincian Pembayaran & Submit -->
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/90 shadow-2xs flex flex-col gap-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="text-base font-heading font-black text-slate-900">Rincian Pembayaran</h3>
                            <span class="text-xs text-slate-500 font-medium">
                                {{ $seats->count() }} Kursi Terpilih ({{ $seats->pluck('seat_number')->implode(', ') }})
                            </span>
                        </div>

                        <div class="flex flex-col gap-2.5 text-xs">
                            <div class="flex justify-between items-center text-slate-500">
                                <span>Tarif Tiket Bus ({{ $seats->count() }} Kursi &times; Rp{{ number_format($trip->price, 0, ',', '.') }})</span>
                                <span class="font-heading font-bold text-slate-900 text-sm tabular-nums">
                                    Rp{{ number_format($trip->price * $seats->count(), 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-slate-500">
                                <span>Biaya Layanan &amp; Pemrosesan Tiket</span>
                                <span class="font-heading font-bold text-emerald-600 text-sm">GRATIS (Rp 0)</span>
                            </div>

                            <!-- Total -->
                            <div class="flex justify-between items-end pt-4 border-t border-slate-100">
                                <div class="flex flex-col">
                                    <span class="text-xs text-slate-500 font-medium">Total Pembayaran</span>
                                    <span class="text-xs text-emerald-600 font-heading font-bold flex items-center gap-1 mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        Tarif Resmi PO CAN Travel
                                    </span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-2xl sm:text-3xl font-heading font-black text-orange-600 tracking-tight leading-none tabular-nums">
                                        Rp{{ number_format($trip->price * $seats->count(), 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 font-medium mt-1">Termasuk PPN &amp; Jasa Raharja</span>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="flex flex-col gap-3 pt-3">
                            <button 
                                type="submit"
                                class="w-full h-12 rounded-full font-heading font-bold text-sm bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white shadow-md shadow-orange-500/20 active:scale-[0.99] transition-all flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-orange-500"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Konfirmasi &amp; Selesaikan Pesanan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>

                            <div class="flex flex-wrap items-center justify-center gap-4 text-slate-500 text-[11px] pt-1 font-medium">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Enkripsi 256-Bit
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    Batas Bayar 30 Menit
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Jadwal Terverifikasi
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- On-time Official Guarantee Card -->
                    <div class="bg-orange-50/50 border border-orange-200/80 p-4 rounded-3xl flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white border border-orange-200/80 flex items-center justify-center text-orange-600 shrink-0 shadow-2xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-heading font-bold text-slate-900">Jaminan Keberangkatan &amp; Fasilitas Resmi PO CAN Travel</p>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                Garansi kompensasi voucher jika terjadi kendala operasional yang menyebabkan keterlambatan keberangkatan lebih dari 60 menit.
                            </p>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </main>
</div>
@endsection

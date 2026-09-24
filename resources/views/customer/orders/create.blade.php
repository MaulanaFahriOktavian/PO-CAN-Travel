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
    class="min-h-screen bg-[#FBFAF6] text-[#1C2522]"
>
    <!-- Progress Stepper Tracker Bar -->
    <section class="w-full bg-white border-b border-[#D9D5CA] sticky top-16 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <!-- Trip Summary Header -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#F5F1E8] text-[#21483C] flex items-center justify-center shrink-0 border border-[#D9D5CA]">
                        <span class="material-symbols-outlined text-[22px]">directions_bus</span>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2 font-bold text-[#1C2522] text-sm sm:text-base">
                            <span>{{ $trip->route->origin }}</span>
                            <span class="text-[#21483C] font-mono">→</span>
                            <span>{{ $trip->route->destination }}</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-xs text-[#66716C] font-medium">
                            <span>{{ $trip->departure_at->translatedFormat('l, d M Y') }}</span>
                            <span class="text-[#D9D5CA]">•</span>
                            <span class="text-[#21483C] font-semibold">{{ $trip->bus->name }}</span>
                            <span class="text-[#D9D5CA]">•</span>
                            <span>{{ $trip->departure_at->format('H:i') }} - {{ $trip->arrival_at->format('H:i') }} WIB</span>
                        </div>
                    </div>
                </div>

                <!-- 4 Steps Stepper -->
                <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto select-none py-1">
                    <!-- Step 1: Done -->
                    <a href="{{ route('customer.trips.index') }}" class="flex items-center gap-1.5 text-[#66716C] hover:text-[#21483C] shrink-0 text-xs font-medium">
                        <div class="w-5 h-5 rounded-full bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA] flex items-center justify-center text-[10px] font-bold">
                            <span class="material-symbols-outlined text-[12px]">check</span>
                        </div>
                        <span>Pilih Rute</span>
                    </a>

                    <span class="w-5 sm:w-8 h-[2px] bg-[#21483C] shrink-0"></span>

                    <!-- Step 2: Active -->
                    <div class="flex items-center gap-1.5 text-[#21483C] shrink-0 text-xs font-bold">
                        <div class="w-5 h-5 rounded-full bg-[#21483C] text-white flex items-center justify-center text-[10px] font-bold">
                            2
                        </div>
                        <span>Kursi &amp; Data</span>
                    </div>

                    <span class="w-5 sm:w-8 h-[2px] bg-[#D9D5CA] shrink-0"></span>

                    <!-- Step 3: Pembayaran -->
                    <div class="flex items-center gap-1.5 text-[#66716C] shrink-0 text-xs font-medium">
                        <div class="w-5 h-5 rounded-full bg-[#F5F1E8] text-[#66716C] border border-[#D9D5CA] flex items-center justify-center text-[10px]">
                            3
                        </div>
                        <span>Pembayaran</span>
                    </div>

                    <span class="w-5 sm:w-8 h-[2px] bg-[#D9D5CA] shrink-0"></span>

                    <!-- Step 4: E-Tiket -->
                    <div class="flex items-center gap-1.5 text-[#66716C] shrink-0 text-xs font-medium">
                        <div class="w-5 h-5 rounded-full bg-[#F5F1E8] text-[#66716C] border border-[#D9D5CA] flex items-center justify-center text-[10px]">
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
                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#66716C] hover:text-[#21483C] transition-colors"
            >
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Ubah Nomor Kursi</span>
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl border text-xs font-bold bg-[#F5F1E8] text-[#21483C] border-[#D9D5CA] flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-[#357A62]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl border text-xs font-bold bg-[#F5F1E8] text-[#B94A48] border-[#B94A48]/30">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT COLUMN: Selected Seats & Bus Information (5 Cols) -->
            <div class="lg:col-span-5 flex flex-col gap-5">
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-[#D9D5CA] flex flex-col gap-4">
                    <div class="flex items-center justify-between pb-2 border-b border-[#D9D5CA]">
                        <div>
                            <span class="text-[11px] uppercase tracking-wider font-extrabold text-[#21483C]">Alokasi Terpilih</span>
                            <h2 class="text-base sm:text-lg font-bold text-[#1C2522]">Kursi yang Anda Pesan</h2>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-[#F5F1E8] text-[#21483C] font-bold text-xs border border-[#D9D5CA]">
                            {{ $seats->count() }} Penumpang
                        </span>
                    </div>

                    <!-- Selected Seats Chips List -->
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($seats as $seat)
                            <div class="p-3.5 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA] flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-[#21483C] text-white font-black text-sm flex items-center justify-center">
                                        {{ $seat->seat_number }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-[#1C2522]">Kursi {{ $seat->seat_number }}</span>
                                        <span class="text-[11px] text-[#66716C]">Kabin Terkonfirmasi</span>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-[#357A62] text-[18px]">verified</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bus & Voyage Specification -->
                    <div class="p-4 rounded-xl bg-[#FBFAF6] border border-[#D9D5CA] flex flex-col gap-2.5 text-xs">
                        <div class="flex justify-between items-center text-[#66716C]">
                            <span>Armada Bus</span>
                            <span class="font-bold text-[#1C2522]">{{ $trip->bus->name }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[#66716C]">
                            <span>Kode Armada</span>
                            <span class="font-mono font-bold text-[#1C2522]">{{ $trip->bus->code }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[#66716C]">
                            <span>Tipe Armada</span>
                            <span class="font-semibold text-[#21483C]">{{ $trip->bus->bus_type ?? 'Bus Antarkota' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[#66716C]">
                            <span>Estimasi Tempuh</span>
                            <span class="font-bold text-[#1C2522]">{{ floor($trip->route->duration / 60) }} Jam {{ $trip->route->duration % 60 > 0 ? ($trip->route->duration % 60) . ' Menit' : '' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[#66716C]">
                            <span>Tarif per Kursi</span>
                            <span class="font-bold text-[#1C2522]">Rp{{ number_format($trip->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Interior Ambience Banner -->
                    <div class="relative overflow-hidden rounded-xl h-36 bg-[#1C2522] border border-[#D9D5CA]">
                        <img 
                            src="{{ asset('images/bus_interior_dusk.jpg') }}" 
                            alt="Suasana Kabin Bus" 
                            class="w-full h-full object-cover opacity-85"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1C2522] via-transparent to-transparent flex items-end p-3.5">
                            <div class="flex items-center justify-between w-full text-white">
                                <span class="text-xs font-bold">Kenyamanan Perjalanan</span>
                                <span class="text-[10px] bg-white/20 backdrop-blur-md px-2.5 py-0.5 rounded-full font-medium">Kabin Penumpang</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT COLUMN: Passenger Form & Order Confirmation (7 Cols) -->
            <div class="lg:col-span-7 flex flex-col gap-6">
                
                <form method="POST" action="{{ route('customer.trips.booking.store', $trip) }}" class="flex flex-col gap-6">
                    @csrf

                    <!-- Card 1: Data Kontak Pemesan -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-[#D9D5CA] flex flex-col gap-4">
                        <div class="flex items-center justify-between pb-2 border-b border-[#D9D5CA]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-[#F5F1E8] text-[#21483C] flex items-center justify-center font-bold border border-[#D9D5CA]">
                                    <span class="material-symbols-outlined text-[18px]">person</span>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-[#1C2522]">Kontak Pemesan</h3>
                                    <p class="text-xs text-[#66716C]">Tiket elektronik &amp; update perjalanan dikirimkan ke kontak ini</p>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold text-[#21483C] bg-[#F5F1E8] border border-[#D9D5CA] px-2 py-0.5 rounded-full">Wajib</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2 flex flex-col gap-1">
                                <label class="text-xs font-bold text-[#1C2522]">Nama Lengkap Sesuai KTP</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        value="{{ auth()->user()->name ?? 'Dimas Pratama' }}" 
                                        class="w-full h-11 px-3.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:border-[#21483C] focus:ring-1 focus:ring-[#21483C] focus:outline-none transition-all"
                                    />
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-[#357A62] text-[18px]">check_circle</span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-[#1C2522]">Nomor WhatsApp / Seluler</label>
                                <div class="relative">
                                    <input 
                                        type="tel" 
                                        value="+62 812-3456-7890" 
                                        class="w-full h-11 px-3.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:border-[#21483C] focus:ring-1 focus:ring-[#21483C] focus:outline-none transition-all"
                                    />
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-[#66716C] text-[18px]">phone_iphone</span>
                                </div>
                                <span class="text-[11px] text-[#66716C]">Notifikasi e-tiket via WhatsApp otomatis</span>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-[#1C2522]">Alamat Email</label>
                                <div class="relative">
                                    <input 
                                        type="email" 
                                        value="{{ auth()->user()->email ?? 'dimas.pratama@email.com' }}" 
                                        class="w-full h-11 px-3.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:border-[#21483C] focus:ring-1 focus:ring-[#21483C] focus:outline-none transition-all"
                                    />
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-[#66716C] text-[18px]">mail</span>
                                </div>
                                <span class="text-[11px] text-[#66716C]">Faktur &amp; e-boarding pass PDF</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Form Data Penumpang per Kursi -->
                    @foreach ($seats as $seat)
                        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-[#D9D5CA] flex flex-col gap-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-[#D9D5CA]">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-lg bg-[#21483C] text-white text-xs font-bold">
                                        Kursi {{ $seat->seat_number }}
                                    </span>
                                    <h3 class="text-sm sm:text-base font-bold text-[#1C2522]">
                                        Data Penumpang &mdash; Kursi {{ $seat->seat_number }}
                                    </h3>
                                </div>
                                @if($loop->first)
                                    <span class="text-xs text-[#66716C] font-medium">Penumpang Utama</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1">
                                    <label for="passenger_name_{{ $seat->id }}" class="text-xs font-bold text-[#1C2522]">
                                        Nama Lengkap Penumpang <span class="text-[#B94A48]">*</span>
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            id="passenger_name_{{ $seat->id }}"
                                            name="passengers[{{ $seat->id }}][name]"
                                            value="{{ old("passengers.{$seat->id}.name", ($loop->first ? (auth()->user()->name ?? 'Dimas Pratama') : '')) }}"
                                            required
                                            maxlength="100"
                                            placeholder="Contoh: Dimas Pratama"
                                            class="w-full h-11 px-3.5 rounded-xl border {{ $errors->has("passengers.{$seat->id}.name") ? 'border-[#B94A48]' : 'border-[#D9D5CA]' }} bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:border-[#21483C] focus:ring-1 focus:ring-[#21483C] focus:outline-none transition-all"
                                        />
                                    </div>
                                    @if ($errors->has("passengers.{$seat->id}.name"))
                                        <p class="text-[11px] text-[#B94A48]">{{ $errors->first("passengers.{$seat->id}.name") }}</p>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-1">
                                    <label for="passenger_identity_{{ $seat->id }}" class="text-xs font-bold text-[#1C2522]">
                                        Nomor Identitas (NIK KTP / Paspor) <span class="text-[#B94A48]">*</span>
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            id="passenger_identity_{{ $seat->id }}"
                                            name="passengers[{{ $seat->id }}][identity]"
                                            value="{{ old("passengers.{$seat->id}.identity", ($loop->first ? '3171012908950001' : '')) }}"
                                            required
                                            maxlength="50"
                                            placeholder="Contoh: 3171012908950001"
                                            class="w-full h-11 px-3.5 rounded-xl border {{ $errors->has("passengers.{$seat->id}.identity") ? 'border-[#B94A48]' : 'border-[#D9D5CA]' }} bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:border-[#21483C] focus:ring-1 focus:ring-[#21483C] focus:outline-none transition-all"
                                        />
                                        <span class="material-symbols-outlined absolute right-3 top-3 text-[#66716C] text-[18px]">badge</span>
                                    </div>
                                    @if ($errors->has("passengers.{$seat->id}.identity"))
                                        <p class="text-[11px] text-[#B94A48]">{{ $errors->first("passengers.{$seat->id}.identity") }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Card 3: Layanan & Makanan -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-[#D9D5CA] flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-[#F5F1E8] text-[#21483C] flex items-center justify-center font-bold border border-[#D9D5CA]">
                                    <span class="material-symbols-outlined text-[18px]">card_travel</span>
                                </div>
                                <h3 class="text-sm sm:text-base font-bold text-[#1C2522]">Layanan &amp; Makanan</h3>
                            </div>
                            <span class="text-xs font-semibold text-[#21483C] bg-[#F5F1E8] border border-[#D9D5CA] px-2 py-0.5 rounded-full">Termasuk Tiket</span>
                        </div>

                        <!-- Asuransi Jasa Raharja Plus -->
                        <label class="p-3.5 rounded-xl bg-[#F5F1E8] hover:bg-[#D9D5CA]/40 border border-[#D9D5CA] transition-all flex items-start gap-3 cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                x-model="hasInsurance"
                                class="mt-1 w-4 h-4 rounded text-[#21483C] focus:ring-[#21483C] accent-[#21483C] cursor-pointer" 
                            />
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-bold text-[#1C2522]">Perlindungan Jasa Raharja Plus</span>
                                        <span class="px-1.5 py-0.5 rounded bg-white text-[#21483C] border border-[#D9D5CA] text-[10px] font-bold">Direkomendasikan</span>
                                    </div>
                                    <span class="text-xs font-bold text-[#21483C]">+Rp 10.000</span>
                                </div>
                                <p class="text-xs text-[#66716C] mt-1 leading-relaxed">
                                    Santunan medis darurat s.d. Rp 50.000.000, jaminan tepat waktu, &amp; perlindungan bagasi selama perjalanan.
                                </p>
                            </div>
                        </label>

                        <!-- Menu Makan Malam Rest Area -->
                        <div class="flex flex-col gap-2 pt-1">
                            <label class="text-xs font-bold text-[#1C2522] flex items-center justify-between">
                                <span>Pilih Menu Makan Malam (Rest Area)</span>
                                <span class="text-[11px] text-[#21483C] font-semibold">Gratis Termasuk Tiket</span>
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                <label 
                                    @click="dinnerChoice = 'rawon'"
                                    :class="dinnerChoice === 'rawon' ? 'bg-[#F5F1E8] border-2 border-[#21483C]' : 'bg-white hover:bg-[#FBFAF6] border border-[#D9D5CA]'"
                                    class="p-3 rounded-xl flex items-start gap-2.5 cursor-pointer transition-all"
                                >
                                    <input type="radio" name="dinner_choice" value="rawon" checked class="accent-[#21483C] w-4 h-4 mt-0.5" />
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-[#1C2522]">Nasi Rawon Daging</span>
                                        <span class="text-[11px] text-[#66716C] mt-0.5">+ Sambal &amp; Telur Asin</span>
                                    </div>
                                </label>

                                <label 
                                    @click="dinnerChoice = 'ayam'"
                                    :class="dinnerChoice === 'ayam' ? 'bg-[#F5F1E8] border-2 border-[#21483C]' : 'bg-white hover:bg-[#FBFAF6] border border-[#D9D5CA]'"
                                    class="p-3 rounded-xl flex items-start gap-2.5 cursor-pointer transition-all"
                                >
                                    <input type="radio" name="dinner_choice" value="ayam" class="accent-[#21483C] w-4 h-4 mt-0.5" />
                                    <div class="flex flex-col">
                                        <span class="text-xs font-semibold text-[#1C2522]">Nasi Ayam Bakar Madu</span>
                                        <span class="text-[11px] text-[#66716C] mt-0.5">+ Lalapan &amp; Tahu</span>
                                    </div>
                                </label>

                                <label 
                                    @click="dinnerChoice = 'vege'"
                                    :class="dinnerChoice === 'vege' ? 'bg-[#F5F1E8] border-2 border-[#21483C]' : 'bg-white hover:bg-[#FBFAF6] border border-[#D9D5CA]'"
                                    class="p-3 rounded-xl flex items-start gap-2.5 cursor-pointer transition-all"
                                >
                                    <input type="radio" name="dinner_choice" value="vege" class="accent-[#21483C] w-4 h-4 mt-0.5" />
                                    <div class="flex flex-col">
                                        <span class="text-xs font-semibold text-[#1C2522]">Menu Vegetarian</span>
                                        <span class="text-[11px] text-[#66716C] mt-0.5">Capcay Tofu Jamur</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Rincian Pembayaran & Submit -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-[#D9D5CA] flex flex-col gap-4">
                        <div class="flex items-center justify-between pb-2 border-b border-[#D9D5CA]">
                            <h3 class="text-base font-bold text-[#1C2522]">Rincian Pembayaran</h3>
                            <span class="text-xs text-[#66716C] font-medium">
                                {{ $seats->count() }} Kursi Terpilih ({{ $seats->pluck('seat_number')->implode(', ') }})
                            </span>
                        </div>

                        <div class="flex flex-col gap-2.5 text-xs">
                            <div class="flex justify-between items-center text-[#66716C]">
                                <span>Tarif Tiket ({{ $seats->count() }} Kursi)</span>
                                <span class="font-bold text-[#1C2522] text-sm" x-text="formatRupiah(ticketTotal)">Rp{{ number_format($trip->price * $seats->count(), 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between items-center text-[#66716C]" x-show="hasInsurance">
                                <span>Asuransi Jasa Raharja Plus ({{ $seats->count() }}x)</span>
                                <span class="font-bold text-[#1C2522] text-sm" x-text="formatRupiah(totalInsurance)">Rp 0</span>
                            </div>

                            <div class="flex justify-between items-center text-[#66716C]">
                                <span class="flex items-center gap-1">
                                    <span>Biaya Layanan &amp; Pemrosesan</span>
                                    <span class="material-symbols-outlined text-[14px] text-[#66716C]">info</span>
                                </span>
                                <span class="font-bold text-[#357A62] text-sm">GRATIS (Rp 0)</span>
                            </div>

                            <div x-show="promoDiscount > 0" class="flex justify-between items-center text-[#357A62] font-semibold">
                                <span>Potongan Voucher Promo</span>
                                <span class="font-bold text-sm" x-text="'- ' + formatRupiah(promoDiscount)">- Rp 0</span>
                            </div>

                            <!-- Total -->
                            <div class="flex justify-between items-end pt-3 border-t border-[#D9D5CA]">
                                <div class="flex flex-col">
                                    <span class="text-xs text-[#66716C] font-medium">Total Pembayaran</span>
                                    <span class="text-xs text-[#21483C] font-semibold flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined text-[14px]">savings</span>
                                        Harga Resmi PO CAN Travel
                                    </span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-2xl font-black text-[#1C2522] tracking-tight leading-none" x-text="formatRupiah(grandTotal)">
                                        Rp 0
                                    </span>
                                    <span class="text-[11px] text-[#66716C] font-medium mt-1">Termasuk PPN 11%</span>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="flex flex-col gap-2 pt-2">
                            <button 
                                type="submit"
                                class="w-full h-12 rounded-xl font-bold text-sm bg-[#21483C] hover:bg-[#2F6252] text-white active:scale-[0.99] transition-all flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <span class="material-symbols-outlined text-[18px]">lock</span>
                                <span>Konfirmasi &amp; Lanjut Pembayaran</span>
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </button>

                            <div class="flex items-center justify-center gap-4 text-[#66716C] text-[11px] pt-1">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px] text-[#21483C]">verified_user</span>Enkripsi 256-Bit
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px] text-[#B96545]">schedule</span>Batas Bayar 30 Menit
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px] text-[#21483C]">sync</span>Bisa Reschedule
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- On-time Official Guarantee Card -->
                    <div class="bg-[#F5F1E8] border border-[#D9D5CA] p-4 rounded-2xl flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white border border-[#D9D5CA] flex items-center justify-center text-[#21483C] shrink-0">
                            <span class="material-symbols-outlined text-[22px]">verified</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#1C2522]">Jaminan On-Time &amp; Fasilitas Resmi PO CAN Travel</p>
                            <p class="text-[11px] text-[#66716C] mt-0.5 leading-relaxed">
                                Garansi kompensasi voucher 25% jika keberangkatan terlambat lebih dari 60 menit dari jadwal tiket.
                            </p>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </main>
</div>
@endsection

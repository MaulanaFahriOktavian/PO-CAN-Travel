@extends('layouts.app')

@section('title', 'Cara Memesan Tiket — PO CAN Travel')
@section('meta_description', 'Panduan lengkap cara memesan tiket bus PO CAN Travel. Mulai dari pencarian jadwal, pemilihan kursi, pengisian data penumpang, hingga konfirmasi pesanan.')

@section('content')
{{-- Hero Header (Light, Clean, Modern) --}}
<div class="relative py-12 lg:py-16 bg-gradient-to-b from-orange-50/40 via-white to-slate-50 border-b border-slate-100 overflow-hidden">
    <!-- Ambient Warm Glow -->
    <div class="absolute -top-20 right-10 w-96 h-96 bg-orange-200/20 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white border border-orange-200/80 text-orange-600 text-xs font-heading font-bold shadow-2xs mb-3">
            <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Panduan Penumpang</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black tracking-tight text-slate-900 mb-4">
            Cara Memesan Tiket
        </h1>
        <p class="text-base text-slate-600 max-w-2xl leading-relaxed">
            Panduan langkah demi langkah untuk memesan tiket bus antarkota melalui PO CAN Travel secara transparan dan mandiri.
        </p>
    </div>
</div>

<div class="py-12 sm:py-16 bg-[#F8FAFC]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Steps --}}
        <div class="space-y-0 mb-16">
            {{-- Step 1 --}}
            <div class="relative flex gap-6 pb-12">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-heading font-black shrink-0 bg-gradient-to-br from-orange-500 to-amber-500 shadow-md shadow-orange-500/25">01</div>
                    <div class="flex-1 w-px mt-3 bg-orange-200/60"></div>
                </div>
                <div class="pt-1.5 pb-2 flex-1">
                    <h2 class="text-lg font-heading font-black mb-2 text-slate-900">Cari Perjalanan</h2>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Buka halaman <strong class="text-slate-900">Cari Tiket</strong> dan masukkan kota asal, kota tujuan, serta tanggal keberangkatan. Sistem akan menampilkan seluruh jadwal bus yang tersedia sesuai filter Anda.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('trips.index') }}" class="inline-flex items-center gap-1.5 text-xs font-heading font-bold text-orange-600 hover:text-orange-700">
                            <span>Buka Halaman Pencarian</span>
                            <span class="font-mono">→</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="relative flex gap-6 pb-12">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-heading font-black shrink-0 bg-gradient-to-br from-orange-500 to-amber-500 shadow-md shadow-orange-500/25">02</div>
                    <div class="flex-1 w-px mt-3 bg-orange-200/60"></div>
                </div>
                <div class="pt-1.5 pb-2 flex-1">
                    <h2 class="text-lg font-heading font-black mb-2 text-slate-900">Pilih Jadwal &amp; Lihat Detail</h2>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Pilih jadwal yang sesuai dari daftar hasil pencarian. Halaman detail perjalanan menampilkan waktu keberangkatan, estimasi tiba, armada bus, durasi perjalanan, dan tarif resmi per penumpang.
                    </p>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="relative flex gap-6 pb-12">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-heading font-black shrink-0 bg-gradient-to-br from-orange-500 to-amber-500 shadow-md shadow-orange-500/25">03</div>
                    <div class="flex-1 w-px mt-3 bg-orange-200/60"></div>
                </div>
                <div class="pt-1.5 pb-2 flex-1">
                    <h2 class="text-lg font-heading font-black mb-2 text-slate-900">Pilih Kursi</h2>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Denah bus ditampilkan secara visual dengan konfigurasi kabin resmi. Kursi berwarna putih dengan garis border tersedia untuk dipilih, sedangkan kursi berwarna abu-abu sudah dipesan oleh penumpang lain.
                    </p>
                    <div class="mt-3 flex items-center gap-4 text-xs text-slate-600">
                        <span class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded border inline-block border-slate-300 bg-white"></span>
                            Tersedia
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded border inline-block border-orange-500 bg-orange-500"></span>
                            Dipilih
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded border inline-block border-slate-200 bg-slate-200/80"></span>
                            Sudah dipesan
                        </span>
                    </div>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="relative flex gap-6 pb-12">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-heading font-black shrink-0 bg-gradient-to-br from-orange-500 to-amber-500 shadow-md shadow-orange-500/25">04</div>
                    <div class="flex-1 w-px mt-3 bg-orange-200/60"></div>
                </div>
                <div class="pt-1.5 pb-2 flex-1">
                    <h2 class="text-lg font-heading font-black mb-2 text-slate-900">Isi Data Penumpang</h2>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Setiap kursi yang dipilih memerlukan data penumpang: nama lengkap dan nomor identitas (NIK KTP / Paspor). Pastikan data diisi dengan benar sesuai dokumen resmi yang dibawa saat perjalanan.
                    </p>
                </div>
            </div>

            {{-- Step 5 --}}
            <div class="relative flex gap-6">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-heading font-black shrink-0 bg-gradient-to-br from-orange-500 to-amber-500 shadow-md shadow-orange-500/25">05</div>
                </div>
                <div class="pt-1.5 flex-1">
                    <h2 class="text-lg font-heading font-black mb-2 text-slate-900">Konfirmasi Pembayaran</h2>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Periksa kembali seluruh detail sebelum konfirmasi. Setelah pesanan tersimpan, Anda akan mendapatkan kode booking resmi unik. Pesanan Anda dapat dipantau setiap saat melalui halaman <strong class="text-slate-900">Pesanan Saya</strong>.
                    </p>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="mb-12 border-t border-slate-200"></div>

        {{-- Before Ordering Checklist --}}
        <div class="mb-12">
            <h2 class="text-xl font-heading font-black mb-6 text-slate-900">Sebelum Memesan, Periksa:</h2>
            <div class="space-y-3">
                @foreach([
                    ['label' => 'Nama penumpang', 'desc' => 'Pastikan nama yang diisi sesuai dengan identitas yang akan dibawa saat boarding perjalanan.'],
                    ['label' => 'Tanggal keberangkatan', 'desc' => 'Periksa kembali apakah tanggal dan jam sudah sesuai agenda perjalanan Anda.'],
                    ['label' => 'Jumlah kursi', 'desc' => 'Setiap kursi mewakili satu penumpang resmi terdaftar.'],
                    ['label' => 'Total pembayaran', 'desc' => 'Tarif resmi tertera transparan tanpa biaya tersembunyi.'],
                ] as $item)
                <div class="flex items-start gap-4 p-4 bg-white rounded-xl border border-slate-200 shadow-2xs">
                    <div class="w-6 h-6 rounded-full shrink-0 mt-0.5 flex items-center justify-center bg-orange-50 text-orange-600 border border-orange-200/80">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-heading font-bold text-slate-900">{{ $item['label'] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $item['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Info tentang pembayaran --}}
        <div class="p-5 rounded-2xl bg-orange-50 mb-12 border border-orange-200/80">
            <h3 class="text-sm font-heading font-bold mb-1.5 text-orange-900">Konfirmasi Pembayaran</h3>
            <p class="text-xs leading-relaxed text-orange-800">
                Setelah pesanan dibuat, status awal adalah <strong class="text-amber-700">Menunggu Pembayaran</strong>. Pembayaran diverifikasi oleh sistem dan administrator PO CAN Travel. Tiket elektronik dan QR boarding pass resmi akan langsung aktif setelah status dikonfirmasi.
            </p>
        </div>

        {{-- CTA --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a
                href="{{ route('trips.index') }}"
                class="flex-1 text-center px-6 py-3 text-xs sm:text-sm font-heading font-bold text-white rounded-full transition-all bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-md shadow-orange-500/20"
            >
                Mulai Cari Perjalanan →
            </a>
            <a
                href="{{ route('faq') }}"
                class="flex-1 text-center px-6 py-3 text-xs sm:text-sm font-heading font-bold rounded-full transition-colors border border-slate-200 text-slate-800 bg-white hover:bg-slate-50 shadow-2xs"
            >
                Lihat FAQ
            </a>
        </div>

    </div>
</div>
@endsection

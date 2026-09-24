@extends('layouts.app')

@section('title', 'Cara Memesan Tiket - PO CAN Travel')
@section('meta_description', 'Panduan lengkap cara memesan tiket bus PO CAN Travel. Mulai dari pencarian jadwal, pemilihan kursi, pengisian data penumpang, hingga konfirmasi pesanan.')

@section('content')
<div class="py-12 sm:py-16 bg-[#FBFAF6]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="pb-8 mb-12 border-b border-[#D9D5CA]">
            <p class="text-xs font-bold uppercase tracking-wider text-[#21483C] mb-1">Panduan Penumpang</p>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-[#1C2522]">Cara Memesan Tiket</h1>
            <p class="mt-2 text-base leading-relaxed text-[#66716C]">
                Panduan langkah demi langkah untuk memesan tiket bus antarkota melalui PO CAN Travel secara transparan dan mandiri.
            </p>
        </div>

        {{-- Steps --}}
        <div class="space-y-0 mb-16">
            {{-- Step 1 --}}
            <div class="relative flex gap-6 pb-12">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-black shrink-0 bg-[#21483C]">01</div>
                    <div class="flex-1 w-px mt-3 bg-[#D9D5CA]"></div>
                </div>
                <div class="pt-1.5 pb-2 flex-1">
                    <h2 class="text-lg font-black mb-2 text-[#1C2522]">Cari Perjalanan</h2>
                    <p class="text-sm leading-relaxed text-[#66716C]">
                        Buka halaman <strong class="text-[#1C2522]">Cari Tiket</strong> dan masukkan kota asal, kota tujuan, serta tanggal keberangkatan. Sistem akan menampilkan seluruh jadwal bus yang tersedia sesuai filter Anda.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('customer.trips.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#21483C] hover:text-[#2F6252]">
                            Buka Halaman Pencarian
                            <span class="font-mono">→</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="relative flex gap-6 pb-12">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-black shrink-0 bg-[#21483C]">02</div>
                    <div class="flex-1 w-px mt-3 bg-[#D9D5CA]"></div>
                </div>
                <div class="pt-1.5 pb-2 flex-1">
                    <h2 class="text-lg font-black mb-2 text-[#1C2522]">Pilih Jadwal &amp; Lihat Detail</h2>
                    <p class="text-sm leading-relaxed text-[#66716C]">
                        Pilih jadwal yang sesuai dari daftar hasil pencarian. Halaman detail perjalanan menampilkan waktu keberangkatan, estimasi tiba, armada bus, durasi perjalanan, dan tarif resmi per penumpang.
                    </p>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="relative flex gap-6 pb-12">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-black shrink-0 bg-[#21483C]">03</div>
                    <div class="flex-1 w-px mt-3 bg-[#D9D5CA]"></div>
                </div>
                <div class="pt-1.5 pb-2 flex-1">
                    <h2 class="text-lg font-black mb-2 text-[#1C2522]">Pilih Kursi</h2>
                    <p class="text-sm leading-relaxed text-[#66716C]">
                        Denah bus ditampilkan secara visual dengan konfigurasi kabin resmi. Kursi berwarna putih dengan garis border tersedia untuk dipilih, sedangkan kursi berwarna abu-abu sudah dipesan oleh penumpang lain.
                    </p>
                    <div class="mt-3 flex items-center gap-4 text-xs text-[#66716C]">
                        <span class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded border inline-block border-[#D9D5CA] bg-white"></span>
                            Tersedia
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded border inline-block border-[#21483C] bg-[#21483C]"></span>
                            Dipilih
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded border inline-block border-[#D9D5CA] bg-[#D9D5CA]/50"></span>
                            Sudah dipesan
                        </span>
                    </div>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="relative flex gap-6 pb-12">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-black shrink-0 bg-[#21483C]">04</div>
                    <div class="flex-1 w-px mt-3 bg-[#D9D5CA]"></div>
                </div>
                <div class="pt-1.5 pb-2 flex-1">
                    <h2 class="text-lg font-black mb-2 text-[#1C2522]">Isi Data Penumpang</h2>
                    <p class="text-sm leading-relaxed text-[#66716C]">
                        Setiap kursi yang dipilih memerlukan data penumpang: nama lengkap dan nomor identitas (NIK KTP / Paspor). Pastikan data diisi dengan benar sesuai dokumen resmi yang dibawa saat perjalanan.
                    </p>
                </div>
            </div>

            {{-- Step 5 --}}
            <div class="relative flex gap-6">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-black shrink-0 bg-[#21483C]">05</div>
                </div>
                <div class="pt-1.5 flex-1">
                    <h2 class="text-lg font-black mb-2 text-[#1C2522]">Konfirmasi &amp; Simpan Pesanan</h2>
                    <p class="text-sm leading-relaxed text-[#66716C]">
                        Periksa kembali seluruh detail sebelum konfirmasi. Setelah pesanan tersimpan, Anda akan mendapatkan kode booking resmi unik. Pesanan Anda dapat dipantau setiap saat melalui halaman <strong class="text-[#1C2522]">Pesanan Saya</strong>.
                    </p>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="mb-12 border-t border-[#D9D5CA]"></div>

        {{-- Before Ordering Checklist --}}
        <div class="mb-12">
            <h2 class="text-xl font-black mb-6 text-[#1C2522]">Sebelum Memesan, Periksa:</h2>
            <div class="space-y-4">
                @foreach([
                    ['label' => 'Nama penumpang', 'desc' => 'Pastikan nama yang diisi sesuai dengan identitas yang akan dibawa saat boarding perjalanan.'],
                    ['label' => 'Tanggal keberangkatan', 'desc' => 'Periksa kembali apakah tanggal dan jam sudah sesuai agenda perjalanan Anda.'],
                    ['label' => 'Jumlah kursi', 'desc' => 'Setiap kursi mewakili satu penumpang resmi terdaftar.'],
                    ['label' => 'Total pembayaran', 'desc' => 'Tarif resmi tertera transparan tanpa biaya tersembunyi.'],
                ] as $item)
                <div class="flex items-start gap-4 p-4 bg-white rounded-xl border border-[#D9D5CA]">
                    <div class="w-5 h-5 rounded-full shrink-0 mt-0.5 flex items-center justify-center bg-[#21483C]">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#1C2522]">{{ $item['label'] }}</p>
                        <p class="text-xs text-[#66716C] mt-0.5">{{ $item['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Info tentang pembayaran --}}
        <div class="p-5 rounded-xl bg-[#F5F1E8] mb-12 border border-[#D9D5CA]">
            <h3 class="text-sm font-bold mb-1.5 text-[#1C2522]">Konfirmasi Pembayaran</h3>
            <p class="text-xs leading-relaxed text-[#66716C]">
                Setelah pesanan dibuat, status awal adalah <strong class="text-[#A87935]">Menunggu Pembayaran</strong>. Pembayaran diverifikasi oleh sistem dan administrator PO CAN Travel. Tiket elektronik dan QR boarding pass resmi akan langsung aktif setelah status dikonfirmasi.
            </p>
        </div>

        {{-- CTA --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a
                href="{{ route('customer.trips.index') }}"
                class="flex-1 text-center px-6 py-3 text-sm font-bold text-white rounded-xl transition-colors bg-[#21483C] hover:bg-[#2F6252]"
            >
                Mulai Cari Perjalanan
            </a>
            <a
                href="{{ route('faq') }}"
                class="flex-1 text-center px-6 py-3 text-sm font-bold rounded-xl transition-colors border border-[#D9D5CA] text-[#1C2522] bg-white hover:bg-[#F5F1E8]"
            >
                Lihat FAQ
            </a>
        </div>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'FAQ - Pertanyaan Umum PO CAN Travel')
@section('meta_description', 'Jawaban atas pertanyaan umum tentang cara memesan tiket bus PO CAN Travel, pemilihan kursi, konfirmasi pembayaran, dan status pesanan.')

@section('content')
<div class="py-12 sm:py-16 bg-[#FBFAF6]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="pb-8 mb-12 border-b border-[#D9D5CA]">
            <p class="text-xs font-bold uppercase tracking-wider text-[#21483C] mb-1">Pusat Informasi</p>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-[#1C2522]">Pertanyaan Umum</h1>
            <p class="mt-2 text-base leading-relaxed text-[#66716C]">
                Jawaban faktual atas pertanyaan yang paling sering kami terima seputar penggunaan layanan tiket dan perjalanan PO CAN Travel.
            </p>
        </div>

        {{-- FAQ Categories --}}
        <div class="space-y-10" x-data="{ open: null }">

            {{-- Kategori: Pemesanan --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest mb-4 text-[#21483C]">Pemesanan</h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button
                            @click="open = open === 'p1' ? null : 'p1'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer"
                            :aria-expanded="open === 'p1'"
                        >
                            <span class="pr-4">Bagaimana cara memesan tiket?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'p1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'p1'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Buka halaman <a href="{{ route('customer.trips.index') }}" class="font-bold text-[#21483C] hover:underline">Cari Tiket</a>, masukkan kota asal, tujuan, dan tanggal. Pilih jadwal yang sesuai, lanjut ke pemilihan kursi, isi data penumpang, kemudian konfirmasi pesanan. Anda perlu memiliki akun untuk menyelesaikan pemesanan.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button
                            @click="open = open === 'p2' ? null : 'p2'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer"
                            :aria-expanded="open === 'p2'"
                        >
                            <span class="pr-4">Bagaimana jika saya Belum memiliki akun?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'p2' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'p2'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Ya. Akun diperlukan untuk menyimpan data pesanan, mengakses tiket digital, dan memantau status pembayaran. Pendaftaran gratis dan hanya membutuhkan nama, email, dan kata sandi.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button
                            @click="open = open === 'p3' ? null : 'p3'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer"
                            :aria-expanded="open === 'p3'"
                        >
                            <span class="pr-4">Bagaimana melihat pesanan yang sudah dibuat?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'p3' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'p3'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Setelah login, buka menu <strong class="text-[#1C2522]">Pesanan Saya</strong> atau halaman <a href="{{ route('customer.dashboard') }}" class="font-bold text-[#21483C] hover:underline">Dasbor</a>. Semua pesanan aktif dan riwayat pemesanan ditampilkan di sana lengkap dengan kode pesanan dan status.</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Kategori: Kursi --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest mb-4 text-[#21483C]">Kursi</h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button @click="open = open === 'k1' ? null : 'k1'" class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer" :aria-expanded="open === 'k1'">
                            <span class="pr-4">Bagaimana cara memilih kursi?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'k1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'k1'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Di halaman pemilihan kursi, denah bus ditampilkan dengan konfigurasi kabin teratur. Klik kursi yang tersedia untuk memilihnya. Kursi abu-abu sudah dipesan dan tidak bisa dipilih.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button @click="open = open === 'k2' ? null : 'k2'" class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer" :aria-expanded="open === 'k2'">
                            <span class="pr-4">Mengapa kursi tertentu tidak bisa dipilih?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'k2' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'k2'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Kursi yang tidak bisa dipilih sudah dipesan oleh penumpang lain dan sedang dalam status menunggu pembayaran, dikonfirmasi, atau sudah selesai. Sistem mengunci kursi secara otomatis untuk mencegah pemesanan ganda.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button @click="open = open === 'k3' ? null : 'k3'" class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer" :aria-expanded="open === 'k3'">
                            <span class="pr-4">Apakah satu kursi bisa dipesan lebih dari satu penumpang?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'k3' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'k3'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Tidak. Setiap kursi hanya untuk satu penumpang. Jika memesan untuk beberapa orang, pilih jumlah kursi sesuai jumlah penumpang dan lengkapi data masing-masing.</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Kategori: Perjalanan --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest mb-4 text-[#21483C]">Perjalanan</h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button @click="open = open === 'j1' ? null : 'j1'" class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer" :aria-expanded="open === 'j1'">
                            <span class="pr-4">Berapa lama estimasi perjalanan?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'j1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'j1'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Durasi ditampilkan di setiap jadwal perjalanan. Sebagai panduan: Jakarta–Jepara ±8 jam, Jakarta–Semarang ±6 jam. Estimasi tiba dihitung berdasarkan durasi resmi rute dan bisa berbeda tergantung kondisi lalu lintas jalan tol.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button @click="open = open === 'j2' ? null : 'j2'" class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer" :aria-expanded="open === 'j2'">
                            <span class="pr-4">Kapan harus tiba di terminal?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'j2' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'j2'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Harap tiba di terminal keberangkatan minimal 30 menit sebelum jadwal keberangkatan bus agar proses verifikasi dan boarding berjalan lancar.</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Kategori: Pembayaran --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest mb-4 text-[#21483C]">Pembayaran</h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button @click="open = open === 'b1' ? null : 'b1'" class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer" :aria-expanded="open === 'b1'">
                            <span class="pr-4">Bagaimana pembayaran pesanan dikonfirmasi?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'b1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'b1'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Setelah pesanan dibuat, status awal adalah <strong class="text-[#A87935]">Menunggu Pembayaran</strong>. Konfirmasi dilakukan oleh administrator PO CAN Travel setelah pembayaran diterima. Status pesanan kemudian diperbarui menjadi <strong class="text-[#357A62]">Dikonfirmasi</strong>.</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Kategori: Pesanan --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest mb-4 text-[#21483C]">Pesanan</h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button @click="open = open === 'o1' ? null : 'o1'" class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer" :aria-expanded="open === 'o1'">
                            <span class="pr-4">Bagaimana melihat detail tiket saya?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'o1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'o1'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Login ke akun Anda, buka <strong class="text-[#1C2522]">Pesanan Saya</strong>, lalu klik pesanan yang ingin dilihat. Halaman detail menampilkan kode pesanan, rute, jadwal, daftar penumpang, dan status terkini.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-[#D9D5CA]">
                        <button @click="open = open === 'o2' ? null : 'o2'" class="w-full flex items-center justify-between px-5 py-4 text-left font-bold text-sm text-[#1C2522] cursor-pointer" :aria-expanded="open === 'o2'">
                            <span class="pr-4">Apakah pesanan bisa dibatalkan?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-[#66716C]" :class="{ 'rotate-180': open === 'o2' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'o2'" x-collapse class="px-5 pb-4 text-xs text-[#66716C] leading-relaxed border-t border-[#D9D5CA]/50">
                            <p class="pt-4">Pembatalan diproses melalui administrator PO CAN Travel. Hubungi tim kami sebelum jadwal keberangkatan bus.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- CTA --}}
        <div class="mt-14 pt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-[#D9D5CA]">
            <div>
                <p class="text-sm font-semibold text-[#1C2522]">Masih punya pertanyaan lain?</p>
                <p class="text-xs text-[#66716C] mt-0.5">Baca panduan cara memesan untuk informasi alur pemesanan lengkap.</p>
            </div>
            <a
                href="{{ route('how-to-order') }}"
                class="shrink-0 px-4 py-2.5 text-sm font-bold text-[#21483C] border border-[#21483C] rounded-lg hover:bg-[#F5F1E8] transition-colors"
            >
                Baca Cara Memesan
            </a>
        </div>

    </div>
</div>
@endsection

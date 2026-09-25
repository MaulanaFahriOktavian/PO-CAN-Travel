@extends('layouts.app')

@section('title', 'FAQ — Pertanyaan Umum PO CAN Travel')
@section('meta_description', 'Jawaban atas pertanyaan umum tentang cara memesan tiket bus PO CAN Travel, pemilihan kursi, konfirmasi pembayaran, dan status pesanan.')

@section('content')
{{-- Hero Header (Light, Clean, Modern) --}}
<div class="relative py-12 lg:py-16 bg-gradient-to-b from-orange-50/40 via-white to-slate-50 border-b border-slate-100 overflow-hidden">
    <!-- Ambient Warm Glow -->
    <div class="absolute -top-20 right-10 w-96 h-96 bg-orange-200/20 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white border border-orange-200/80 text-orange-600 text-xs font-heading font-bold shadow-2xs mb-3">
            <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Pusat Informasi</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black tracking-tight text-slate-900 mb-4">
            Pertanyaan Umum
        </h1>
        <p class="text-base text-slate-600 max-w-2xl leading-relaxed">
            Jawaban faktual atas pertanyaan yang paling sering kami terima seputar penggunaan layanan tiket dan perjalanan PO CAN Travel.
        </p>
    </div>
</div>

<div class="py-12 sm:py-16 bg-[#F8FAFC]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- FAQ Categories --}}
        <div class="space-y-10" x-data="{ open: null }">

            {{-- Kategori: Pemesanan --}}
            <div>
                <h2 class="text-xs font-heading font-bold uppercase tracking-wider mb-4 text-orange-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>Pemesanan</span>
                </h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button
                            @click="open = open === 'p1' ? null : 'p1'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors"
                            :aria-expanded="open === 'p1'"
                        >
                            <span class="pr-4">Bagaimana cara memesan tiket?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'p1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'p1'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Buka halaman <a href="{{ route('trips.index') }}" class="font-bold text-orange-600 hover:underline">Cari Tiket</a>, masukkan kota asal, tujuan, dan tanggal. Pilih jadwal yang sesuai, lanjut ke pemilihan kursi, isi data penumpang, kemudian konfirmasi pesanan. Anda perlu memiliki akun untuk menyelesaikan pemesanan.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button
                            @click="open = open === 'p2' ? null : 'p2'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors"
                            :aria-expanded="open === 'p2'"
                        >
                            <span class="pr-4">Bagaimana jika saya Belum memiliki akun?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'p2' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'p2'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Ya. Akun diperlukan untuk menyimpan data pesanan, mengakses tiket digital, dan memantau status pembayaran. Pendaftaran gratis dan hanya membutuhkan nama, email, dan kata sandi.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button
                            @click="open = open === 'p3' ? null : 'p3'"
                            class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors"
                            :aria-expanded="open === 'p3'"
                        >
                            <span class="pr-4">Bagaimana melihat pesanan yang sudah dibuat?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'p3' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'p3'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Setelah login, buka menu <strong class="text-slate-900">Pesanan Saya</strong> atau halaman <a href="{{ route('customer.dashboard') }}" class="font-bold text-orange-600 hover:underline">Dasbor</a>. Semua pesanan aktif dan riwayat pemesanan ditampilkan di sana lengkap dengan kode pesanan dan status.</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Kategori: Kursi --}}
            <div>
                <h2 class="text-xs font-heading font-bold uppercase tracking-wider mb-4 text-orange-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l1-5h12l1 5M5 11v8h14v-8M5 11h14M8 19v2m8-2v2"/></svg>
                    <span>Kursi</span>
                </h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button @click="open = open === 'k1' ? null : 'k1'" class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors" :aria-expanded="open === 'k1'">
                            <span class="pr-4">Bagaimana cara memilih kursi?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'k1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'k1'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Di halaman pemilihan kursi, denah bus ditampilkan dengan konfigurasi kabin teratur. Klik kursi yang tersedia untuk memilihnya. Kursi abu-abu sudah dipesan dan tidak bisa dipilih.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button @click="open = open === 'k2' ? null : 'k2'" class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors" :aria-expanded="open === 'k2'">
                            <span class="pr-4">Mengapa kursi tertentu tidak bisa dipilih?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'k2' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'k2'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Kursi yang tidak bisa dipilih sudah dipesan oleh penumpang lain dan sedang dalam status menunggu pembayaran, dikonfirmasi, atau sudah selesai. Sistem mengunci kursi secara otomatis untuk mencegah pemesanan ganda.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button @click="open = open === 'k3' ? null : 'k3'" class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors" :aria-expanded="open === 'k3'">
                            <span class="pr-4">Apakah satu kursi bisa dipesan lebih dari satu penumpang?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'k3' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'k3'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Tidak. Setiap kursi hanya untuk satu penumpang. Jika memesan untuk beberapa orang, pilih jumlah kursi sesuai jumlah penumpang dan lengkapi data masing-masing.</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Kategori: Perjalanan --}}
            <div>
                <h2 class="text-xs font-heading font-bold uppercase tracking-wider mb-4 text-orange-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 3h8m-9 7h10M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2zm3 0v2m8-2v2"/></svg>
                    <span>Perjalanan</span>
                </h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button @click="open = open === 'j1' ? null : 'j1'" class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors" :aria-expanded="open === 'j1'">
                            <span class="pr-4">Berapa lama estimasi perjalanan?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'j1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'j1'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Durasi ditampilkan di setiap jadwal perjalanan. Sebagai panduan: Jakarta–Jepara ±8 jam, Jakarta–Semarang ±6 jam. Estimasi tiba dihitung berdasarkan durasi resmi rute dan bisa berbeda tergantung kondisi lalu lintas jalan tol.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button @click="open = open === 'j2' ? null : 'j2'" class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors" :aria-expanded="open === 'j2'">
                            <span class="pr-4">Kapan harus tiba di terminal?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'j2' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'j2'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Harap tiba di terminal keberangkatan minimal 30 menit sebelum jadwal keberangkatan bus agar proses verifikasi dan boarding berjalan lancar.</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Kategori: Pembayaran --}}
            <div>
                <h2 class="text-xs font-heading font-bold uppercase tracking-wider mb-4 text-orange-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>Pembayaran</span>
                </h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button @click="open = open === 'b1' ? null : 'b1'" class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors" :aria-expanded="open === 'b1'">
                            <span class="pr-4">Bagaimana pembayaran pesanan dikonfirmasi?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'b1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'b1'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Setelah pesanan dibuat, status awal adalah <strong class="text-amber-700">Menunggu Pembayaran</strong>. Konfirmasi dilakukan oleh administrator PO CAN Travel setelah pembayaran diterima. Status pesanan kemudian diperbarui menjadi <strong class="text-emerald-700">Dikonfirmasi</strong>.</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Kategori: Pesanan --}}
            <div>
                <h2 class="text-xs font-heading font-bold uppercase tracking-wider mb-4 text-orange-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    <span>Pesanan</span>
                </h2>
                <div class="space-y-2">

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button @click="open = open === 'o1' ? null : 'o1'" class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors" :aria-expanded="open === 'o1'">
                            <span class="pr-4">Bagaimana melihat detail tiket saya?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'o1' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'o1'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Login ke akun Anda, buka <strong class="text-slate-900">Pesanan Saya</strong>, lalu klik pesanan yang ingin dilihat. Halaman detail menampilkan kode pesanan, rute, jadwal, daftar penumpang, dan status terkini.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                        <button @click="open = open === 'o2' ? null : 'o2'" class="w-full flex items-center justify-between px-5 py-4 text-left font-heading font-bold text-sm text-slate-900 cursor-pointer hover:bg-orange-50/50 transition-colors" :aria-expanded="open === 'o2'">
                            <span class="pr-4">Apakah pesanan bisa dibatalkan?</span>
                            <svg class="w-4 h-4 shrink-0 transition-transform text-slate-500" :class="{ 'rotate-180': open === 'o2' }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === 'o2'" x-collapse class="px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            <p class="pt-4">Pembatalan diproses melalui administrator PO CAN Travel. Hubungi tim kami sebelum jadwal keberangkatan bus.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- CTA --}}
        <div class="mt-14 pt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-slate-200">
            <div>
                <p class="text-sm font-heading font-bold text-slate-900">Masih punya pertanyaan lain?</p>
                <p class="text-xs text-slate-500 mt-0.5">Baca panduan cara memesan untuk informasi alur pemesanan lengkap.</p>
            </div>
            <a
                href="{{ route('how-to-order') }}"
                class="shrink-0 px-5 py-2.5 text-xs font-heading font-bold text-white rounded-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-md shadow-orange-500/20 transition-all"
            >
                Baca Cara Memesan →
            </a>
        </div>

    </div>
</div>
@endsection

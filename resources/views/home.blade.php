@extends('layouts.app')

@section('title', 'PO CAN Travel - Pemesanan Tiket Bus Online')

@section('content')
<!-- Hero Section -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="max-w-3xl">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight leading-tight">
                Layanan Pemesanan Tiket Bus PO CAN Travel
            </h1>
            <p class="mt-5 text-lg sm:text-xl text-slate-600 leading-relaxed">
                Temukan jadwal perjalanan bus, pilih kursi yang tersedia, dan lakukan pemesanan tiket untuk perjalanan antarkota Anda.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <a
                    href="#cari-tiket"
                    id="hero-cta-btn"
                    class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                >
                    Cari Jadwal Tiket
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Section Informasi Singkat -->
<section class="py-16 sm:py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                Tahapan Pemesanan Tiket
            </h2>
            <p class="mt-3 text-base text-slate-600">
                Langkah-langkah untuk memesan tiket perjalanan bus pada sistem PO CAN Travel.
            </p>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Langkah 1 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 font-semibold text-base mb-4">
                    1
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Cari Jadwal Perjalanan</h3>
                <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                    Pilih lokasi keberangkatan, kota tujuan, dan tanggal rencana perjalanan untuk melihat jadwal bus yang tersedia.
                </p>
            </div>

            <!-- Langkah 2 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 font-semibold text-base mb-4">
                    2
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Pilih Kursi Bus</h3>
                <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                    Tinjau denah tata letak kursi armada dan tentukan posisi nomor kursi yang masih dapat dipesan.
                </p>
            </div>

            <!-- Langkah 3 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 font-semibold text-base mb-4">
                    3
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Pemesanan</h3>
                <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                    Isi data identitas penumpang secara lengkap dan selesaikan pesanan untuk mendapatkan tiket perjalanan Anda.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Placeholder Area Pencarian Tiket -->
<section id="cari-tiket" class="py-16 sm:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-dashed border-slate-300 p-8 sm:p-12 text-center bg-slate-50/50">
            <h2 class="text-xl font-semibold text-slate-900">
                Pencarian Tiket Perjalanan
            </h2>
            <p class="mt-2 text-sm text-slate-600 max-w-xl mx-auto">
                Form pencarian rute dan jadwal bus akan dihubungkan dengan data perjalanan pada tahap berikutnya.
            </p>
        </div>
    </div>
</section>
@endsection

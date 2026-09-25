@extends('layouts.app')

@section('title', 'Tentang PO CAN Travel — Layanan Tiket Bus Antarkota')
@section('meta_description', 'Mengenal layanan pemesanan tiket bus PO CAN Travel. Pemilihan kursi mandiri, kepastian jadwal antarkota, dan tarif resmi tanpa biaya tersembunyi.')

@section('content')
{{-- Hero Header --}}
<div class="relative py-12 lg:py-16 bg-gradient-to-b from-orange-50/40 via-white to-slate-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-50 border border-orange-200/80 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4 shadow-xs">
            <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Layanan Resmi Perjalanan Antarkota</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black tracking-tight leading-tight text-slate-900 mb-4">
            Tentang PO CAN Travel
        </h1>
        <p class="text-base text-slate-600 max-w-2xl leading-relaxed">
            PO CAN Travel menyediakan platform untuk mencari perjalanan bus, melihat ketersediaan kursi secara transparan, dan melakukan pemesanan secara mandiri dan terstruktur.
        </p>
    </div>
</div>

<div class="py-12 sm:py-16 bg-[#FAFBFD]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- Prinsip Operasional --}}
        <section aria-labelledby="section-layanan" class="space-y-6">
            <div>
                <h2 id="section-layanan" class="text-xl sm:text-2xl font-heading font-black tracking-tight text-slate-900">
                    Prinsip Layanan
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Standar operasional yang kami terapkan untuk setiap perjalanan dan pemesanan:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white border rounded-3xl p-6 border-slate-200/80 shadow-xs hover:shadow-md hover:border-orange-200 transition-all">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-xs font-mono font-bold mb-4 bg-orange-50 text-orange-600 border border-orange-200/80">
                        01
                    </div>
                    <h3 class="text-base font-heading font-bold mb-2 text-slate-900">Pemilihan Kursi Mandiri</h3>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Denah kabin ditampilkan secara jelas. Anda memilih nomor kursi sendiri dan kursi yang terpesan otomatis terkunci di database.
                    </p>
                </div>

                <div class="bg-white border rounded-3xl p-6 border-slate-200/80 shadow-xs hover:shadow-md hover:border-orange-200 transition-all">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-xs font-mono font-bold mb-4 bg-orange-50 text-orange-600 border border-orange-200/80">
                        02
                    </div>
                    <h3 class="text-base font-heading font-bold mb-2 text-slate-900">Kepastian Jadwal &amp; Tarif</h3>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Tarif yang tercantum adalah harga resmi per penumpang tanpa biaya perantara. Jadwal dan durasi perjalanan tercatat jelas.
                    </p>
                </div>

                <div class="bg-white border rounded-3xl p-6 border-slate-200/80 shadow-xs hover:shadow-md hover:border-orange-200 transition-all">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-xs font-mono font-bold mb-4 bg-orange-50 text-orange-600 border border-orange-200/80">
                        03
                    </div>
                    <h3 class="text-base font-heading font-bold mb-2 text-slate-900">Manajemen Tiket Digital</h3>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Setiap pesanan menghasilkan kode booking unik dan tiket digital yang dapat diakses kapan saja melalui dasbor akun penumpang.
                    </p>
                </div>
            </div>
        </section>

        {{-- Bagaimana Sistem Bekerja --}}
        <section aria-labelledby="section-cara-kerja" class="pt-8 border-t border-slate-200/80 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2">
                <div>
                    <h2 id="section-cara-kerja" class="text-xl sm:text-2xl font-heading font-black tracking-tight text-slate-900">
                        Bagaimana Sistem Bekerja
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Empat tahapan terstruktur dari pencarian hingga keberangkatan:
                    </p>
                </div>
                <a href="{{ route('how-to-order') }}" class="inline-flex items-center gap-1 text-xs font-heading font-bold text-orange-600 hover:text-orange-700">
                    <span>Panduan Lengkap</span>
                    <span class="font-mono">→</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border rounded-2xl p-5 border-slate-200/80 shadow-xs">
                    <span class="text-xs font-heading font-bold uppercase tracking-wider block mb-1 text-orange-600">Tahap 1</span>
                    <h3 class="text-sm font-heading font-bold mb-1 text-slate-900">Cari Jadwal</h3>
                    <p class="text-xs leading-relaxed text-slate-600">
                        Tentukan rute asal, tujuan, dan tanggal keberangkatan yang sesuai.
                    </p>
                </div>

                <div class="bg-white border rounded-2xl p-5 border-slate-200/80 shadow-xs">
                    <span class="text-xs font-heading font-bold uppercase tracking-wider block mb-1 text-orange-600">Tahap 2</span>
                    <h3 class="text-sm font-heading font-bold mb-1 text-slate-900">Cara Memilih Kursi</h3>
                    <p class="text-xs leading-relaxed text-slate-600">
                        Pilih posisi tempat duduk dari denah kabin armada bus yang beroperasi.
                    </p>
                </div>

                <div class="bg-white border rounded-2xl p-5 border-slate-200/80 shadow-xs">
                    <span class="text-xs font-heading font-bold uppercase tracking-wider block mb-1 text-orange-600">Tahap 3</span>
                    <h3 class="text-sm font-heading font-bold mb-1 text-slate-900">Data Penumpang</h3>
                    <p class="text-xs leading-relaxed text-slate-600">
                        Lengkapi nama dan identitas resmi untuk tiap kursi sebelum konfirmasi.
                    </p>
                </div>

                <div class="bg-white border rounded-2xl p-5 border-slate-200/80 shadow-xs">
                    <span class="text-xs font-heading font-bold uppercase tracking-wider block mb-1 text-orange-600">Tahap 4</span>
                    <h3 class="text-sm font-heading font-bold mb-1 text-slate-900">Cara Melihat Pesanan</h3>
                    <p class="text-xs leading-relaxed text-slate-600">
                        Kode pesanan unik tersimpan di akun Anda untuk ditunjukkan saat verifikasi.
                    </p>
                </div>
            </div>
        </section>

        {{-- Rute yang Dilayani --}}
        <section aria-labelledby="section-rute" class="pt-8 border-t border-slate-200/80 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2">
                <div>
                    <h2 id="section-rute" class="text-xl sm:text-2xl font-heading font-black tracking-tight text-slate-900">
                        Rute Perjalanan yang Dilayani
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Jaringan trayek antarkota yang tercatat dalam sistem operasional PO CAN Travel:
                    </p>
                </div>
                <a href="{{ route('routes.index') }}" class="inline-flex items-center gap-1 text-xs font-heading font-bold text-orange-600 hover:text-orange-700">
                    <span>Lihat Semua Rute</span>
                    <span class="font-mono">→</span>
                </a>
            </div>

            @if ($routes->isEmpty())
                <div class="bg-white border rounded-2xl p-6 text-center text-sm text-slate-500 border-slate-200/80 shadow-xs">
                    Belum ada data rute yang aktif saat ini.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($routes as $route)
                        <div class="bg-white border rounded-2xl p-5 flex flex-col justify-between border-slate-200/80 shadow-xs hover:border-orange-300 hover:shadow-sm transition-all">
                            <div>
                                <div class="text-sm font-heading font-bold text-slate-900 flex items-center gap-1.5">
                                    <span>{{ $route->origin }}</span>
                                    <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    <span>{{ $route->destination }}</span>
                                </div>
                                <p class="text-xs mt-1 text-slate-500">
                                    Estimasi: {{ floor($route->duration / 60) }} jam {{ $route->duration % 60 > 0 ? ($route->duration % 60) . ' mnt' : '' }}
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500">
                                    {{ $route->trips_count }} jadwal aktif
                                </span>
                                <a
                                    href="{{ route('trips.index', ['origin' => $route->origin, 'destination' => $route->destination]) }}"
                                    class="font-heading font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1"
                                >
                                    <span>Cari Tiket</span>
                                    <span class="font-mono">→</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Call to Action --}}
        <section class="pt-4">
            <div class="rounded-3xl p-8 sm:p-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 bg-gradient-to-r from-orange-50 via-amber-50/60 to-orange-100/50 border border-orange-200/60 shadow-xs">
                <div class="max-w-xl">
                    <h2 class="text-xl sm:text-2xl font-heading font-black tracking-tight text-slate-900 mb-2">
                        Siap Memesan Perjalanan Anda?
                    </h2>
                    <p class="text-sm leading-relaxed text-slate-600">
                        Pilih jadwal keberangkatan bus yang sesuai rencana dan tentukan nomor kursi Anda langsung melalui platform resmi.
                    </p>
                </div>
                <div class="shrink-0">
                    <a
                        href="{{ route('trips.index') }}"
                        class="inline-flex items-center justify-center px-6 py-3 font-heading font-bold text-sm rounded-full transition-all text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-md shadow-orange-500/20"
                    >
                        <span>Cari Perjalanan</span>
                        <span class="font-mono ml-1.5">→</span>
                    </a>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Tentang PO CAN Travel - Layanan Tiket Bus Antarkota')
@section('meta_description', 'Mengenal layanan pemesanan tiket bus PO CAN Travel. Pemilihan kursi mandiri, kepastian jadwal antarkota, dan tarif resmi tanpa biaya tersembunyi.')

@section('content')
<div class="py-12 sm:py-16 bg-[#FBFAF6]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">

        {{-- Header Section --}}
        <header class="border-b pb-8 border-[#D9D5CA]">
            <p class="text-xs font-bold uppercase tracking-wider mb-2 text-[#21483C]">Layanan Resmi Perjalanan Antarkota</p>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight text-[#1C2522]">
                Tentang PO CAN Travel
            </h1>
            <p class="mt-4 text-base sm:text-lg leading-relaxed text-[#66716C]">
                PO CAN Travel menyediakan platform untuk mencari perjalanan bus, melihat ketersediaan kursi secara transparan, dan melakukan pemesanan secara mandiri dan terstruktur.
            </p>
        </header>

        {{-- Prinsip Operasional --}}
        <section aria-labelledby="section-layanan" class="space-y-6">
            <div>
                <h2 id="section-layanan" class="text-xl sm:text-2xl font-bold tracking-tight text-[#1C2522]">
                    Prinsip Layanan
                </h2>
                <p class="mt-1 text-sm text-[#66716C]">
                    Standar operasional yang kami terapkan untuk setiap perjalanan dan pemesanan:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white border rounded-xl p-6 border-[#D9D5CA]">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold mb-4 bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA]">
                        01
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1C2522]">Pemilihan Kursi Mandiri</h3>
                    <p class="text-sm leading-relaxed text-[#66716C]">
                        Denah kabin ditampilkan secara jelas. Anda memilih nomor kursi sendiri dan kursi yang terpesan otomatis terkunci di database.
                    </p>
                </div>

                <div class="bg-white border rounded-xl p-6 border-[#D9D5CA]">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold mb-4 bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA]">
                        02
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1C2522]">Kepastian Jadwal &amp; Tarif</h3>
                    <p class="text-sm leading-relaxed text-[#66716C]">
                        Tarif yang tercantum adalah harga resmi per penumpang tanpa biaya perantara. Jadwal dan durasi perjalanan tercatat jelas.
                    </p>
                </div>

                <div class="bg-white border rounded-xl p-6 border-[#D9D5CA]">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold mb-4 bg-[#F5F1E8] text-[#21483C] border border-[#D9D5CA]">
                        03
                    </div>
                    <h3 class="text-base font-bold mb-2 text-[#1C2522]">Manajemen Tiket Digital</h3>
                    <p class="text-sm leading-relaxed text-[#66716C]">
                        Setiap pesanan menghasilkan kode booking unik dan tiket digital yang dapat diakses kapan saja melalui dasbor akun penumpang.
                    </p>
                </div>
            </div>
        </section>

        {{-- Bagaimana Sistem Bekerja --}}
        <section aria-labelledby="section-cara-kerja" class="pt-8 border-t border-[#D9D5CA] space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2">
                <div>
                    <h2 id="section-cara-kerja" class="text-xl sm:text-2xl font-bold tracking-tight text-[#1C2522]">
                        Bagaimana Sistem Bekerja
                    </h2>
                    <p class="mt-1 text-sm text-[#66716C]">
                        Empat tahapan terstruktur dari pencarian hingga keberangkatan:
                    </p>
                </div>
                <a href="{{ route('how-to-order') }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                    Panduan Lengkap &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white border rounded-xl p-5 border-[#D9D5CA]">
                    <span class="text-xs font-bold uppercase tracking-wider block mb-1 text-[#21483C]">Tahap 1</span>
                    <h3 class="text-sm font-bold mb-1 text-[#1C2522]">Cari Jadwal</h3>
                    <p class="text-xs leading-relaxed text-[#66716C]">
                        Tentukan rute asal, tujuan, dan tanggal keberangkatan yang sesuai.
                    </p>
                </div>

                <div class="bg-white border rounded-xl p-5 border-[#D9D5CA]">
                    <span class="text-xs font-bold uppercase tracking-wider block mb-1 text-[#21483C]">Tahap 2</span>
                    <h3 class="text-sm font-bold mb-1 text-[#1C2522]">Cara Memilih Kursi</h3>
                    <p class="text-xs leading-relaxed text-[#66716C]">
                        Pilih posisi tempat duduk dari denah kabin armada bus yang beroperasi.
                    </p>
                </div>

                <div class="bg-white border rounded-xl p-5 border-[#D9D5CA]">
                    <span class="text-xs font-bold uppercase tracking-wider block mb-1 text-[#21483C]">Tahap 3</span>
                    <h3 class="text-sm font-bold mb-1 text-[#1C2522]">Data Penumpang</h3>
                    <p class="text-xs leading-relaxed text-[#66716C]">
                        Lengkapi nama dan identitas resmi untuk tiap kursi sebelum konfirmasi.
                    </p>
                </div>

                <div class="bg-white border rounded-xl p-5 border-[#D9D5CA]">
                    <span class="text-xs font-bold uppercase tracking-wider block mb-1 text-[#21483C]">Tahap 4</span>
                    <h3 class="text-sm font-bold mb-1 text-[#1C2522]">Cara Melihat Pesanan</h3>
                    <p class="text-xs leading-relaxed text-[#66716C]">
                        Kode pesanan unik tersimpan di akun Anda untuk ditunjukkan saat verifikasi.
                    </p>
                </div>
            </div>
        </section>

        {{-- Rute yang Dilayani --}}
        <section aria-labelledby="section-rute" class="pt-8 border-t border-[#D9D5CA] space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2">
                <div>
                    <h2 id="section-rute" class="text-xl sm:text-2xl font-bold tracking-tight text-[#1C2522]">
                        Rute Perjalanan yang Dilayani
                    </h2>
                    <p class="mt-1 text-sm text-[#66716C]">
                        Jaringan trayek antarkota yang tercatat dalam sistem operasional PO CAN Travel:
                    </p>
                </div>
                <a href="{{ route('routes.index') }}" class="text-xs font-bold text-[#21483C] hover:text-[#2F6252]">
                    Lihat Semua Rute &rarr;
                </a>
            </div>

            @if ($routes->isEmpty())
                <div class="bg-white border rounded-xl p-6 text-center text-sm text-[#66716C] border-[#D9D5CA]">
                    Belum ada data rute yang aktif saat ini.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($routes as $route)
                        <div class="bg-white border rounded-xl p-5 flex flex-col justify-between border-[#D9D5CA]">
                            <div>
                                <div class="text-sm font-bold text-[#1C2522]">
                                    {{ $route->origin }} &rarr; {{ $route->destination }}
                                </div>
                                <p class="text-xs mt-1 text-[#66716C]">
                                    Estimasi: {{ floor($route->duration / 60) }} jam {{ $route->duration % 60 > 0 ? ($route->duration % 60) . ' mnt' : '' }}
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-[#D9D5CA] flex items-center justify-between text-xs">
                                <span class="text-[#66716C]">
                                    {{ $route->trips_count }} jadwal aktif
                                </span>
                                <a
                                    href="{{ route('customer.trips.index', ['origin' => $route->origin, 'destination' => $route->destination]) }}"
                                    class="font-bold text-[#21483C] hover:text-[#2F6252]"
                                >
                                    Cari Tiket &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Call to Action --}}
        <section class="pt-6">
            <div class="rounded-2xl p-8 sm:p-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 bg-[#21483C] text-white">
                <div class="max-w-xl">
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-[#F5F1E8] mb-2">
                        Siap Memesan Perjalanan Anda?
                    </h2>
                    <p class="text-sm leading-relaxed text-[#D9D5CA]">
                        Pilih jadwal keberangkatan bus yang sesuai rencana dan tentukan nomor kursi Anda langsung melalui platform resmi.
                    </p>
                </div>
                <div class="shrink-0">
                    <a
                        href="{{ route('customer.trips.index') }}"
                        class="inline-flex items-center justify-center px-6 py-3 font-bold text-sm rounded-xl transition-colors text-center text-[#1C2522] bg-[#F5F1E8] hover:bg-white"
                    >
                        Cari Perjalanan &rarr;
                    </a>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection

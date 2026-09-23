@extends('layouts.app')

@section('title', 'Tentang PO CAN Travel - Platform Pemesanan Tiket Bus Online')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <!-- Header Section -->
        <div class="border-b border-slate-200 pb-8">
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 block mb-2">Informasi Platform</span>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">
                Tentang PO CAN Travel
            </h1>
            <p class="mt-3 text-base sm:text-lg text-slate-600 leading-relaxed">
                PO CAN Travel adalah platform pemesanan tiket bus antarkota yang memudahkan masyarakat menemukan jadwal keberangkatan, memilih nomor kursi secara mandiri, dan mengelola tiket perjalanan secara terpadu.
            </p>
        </div>

        <!-- Apa yang Dapat Dilakukan -->
        <section class="space-y-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Layanan dan Kemudahan
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Fitur utama yang dapat Anda manfaatkan di platform PO CAN Travel:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-white border border-slate-200 rounded-xl p-6">
                    <h3 class="text-base font-semibold text-slate-900 mb-2">Pencarian Jadwal Lengkap</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Cek ketersediaan jadwal perjalanan antarkota berdasarkan rute, tanggal, waktu keberangkatan, armada bus, dan tarif resmi tanpa biaya tersembunyi.
                    </p>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6">
                    <h3 class="text-base font-semibold text-slate-900 mb-2">Pemilihan Kursi Langsung</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Lihat denah tata letak bus 2+2 secara transparan dan tentukan nomor kursi yang masih tersedia sesuai preferensi kenyamanan Anda.
                    </p>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6">
                    <h3 class="text-base font-semibold text-slate-900 mb-2">Pencatatan Penumpang Mandiri</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Lengkapi nama lengkap dan nomor identitas resmi (KTP/SIM/Paspor) untuk setiap kursi demi ketertiban manifes perjalanan.
                    </p>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6">
                    <h3 class="text-base font-semibold text-slate-900 mb-2">Bukti Pesanan Digital</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Seluruh transaksi tercatat dengan kode pesanan unik yang dapat diakses kapan saja melalui menu riwayat pesanan akun Anda.
                    </p>
                </div>
            </div>
        </section>

        <!-- Bagaimana Proses Pemesanan Bekerja -->
        <section class="space-y-6 pt-4 border-t border-slate-200">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Cara Memesan Tiket
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Empat tahapan sederhana dalam memesan tiket di platform PO CAN Travel:
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <span class="text-2xl font-bold font-mono text-blue-600 block mb-1">01</span>
                    <h3 class="text-sm font-semibold text-slate-900 mb-1">Cari Perjalanan</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Tentukan kota asal, kota tujuan, dan tanggal keberangkatan yang sesuai rencana.
                    </p>
                </div>

                <div>
                    <span class="text-2xl font-bold font-mono text-blue-600 block mb-1">02</span>
                    <h3 class="text-sm font-semibold text-slate-900 mb-1">Pilih Kursi</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pilih satu atau beberapa nomor kursi yang masih tersedia pada denah bus.
                    </p>
                </div>

                <div>
                    <span class="text-2xl font-bold font-mono text-blue-600 block mb-1">03</span>
                    <h3 class="text-sm font-semibold text-slate-900 mb-1">Isi Data Penumpang</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Masukkan nama lengkap dan nomor identitas resmi untuk tiap kursi yang dipesan.
                    </p>
                </div>

                <div>
                    <span class="text-2xl font-bold font-mono text-blue-600 block mb-1">04</span>
                    <h3 class="text-sm font-semibold text-slate-900 mb-1">Selesaikan Pesanan</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Konfirmasi pemesanan Anda. Detail tiket tersimpan rapi pada akun Anda.
                    </p>
                </div>
            </div>
        </section>

        <!-- Rute yang Tersedia -->
        <section class="space-y-6 pt-4 border-t border-slate-200">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Rute Perjalanan yang Dilayani
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Daftar rute perjalanan antarkota yang terdaftar saat ini dalam sistem:
                </p>
            </div>

            @if ($routes->isEmpty())
                <div class="bg-white border border-slate-200 rounded-xl p-6 text-center text-sm text-slate-500">
                    Belum ada data rute yang aktif saat ini.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($routes as $route)
                        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between">
                            <div>
                                <div class="text-sm font-semibold text-slate-900 mb-1">
                                    {{ $route->origin }} &rarr; {{ $route->destination }}
                                </div>
                                <p class="text-xs text-slate-500">
                                    Estimasi durasi: {{ floor($route->duration / 60) }} jam {{ $route->duration % 60 > 0 ? ($route->duration % 60) . ' menit' : '' }}
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500">
                                    {{ $route->trips_count }} jadwal aktif
                                </span>
                                <a
                                    href="{{ route('customer.trips.index', ['origin' => $route->origin, 'destination' => $route->destination]) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium"
                                >
                                    Cari Tiket &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Call to Action -->
        <section class="pt-6">
            <div class="bg-slate-900 text-white rounded-2xl p-8 sm:p-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="max-w-xl">
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white mb-2">
                        Siap Memesan Tiket Bus Anda?
                    </h2>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Cari jadwal keberangkatan bus yang sesuai rencana dan amankan kursi perjalanan Anda sekarang.
                    </p>
                </div>
                <div class="shrink-0">
                    <a
                        href="{{ route('customer.trips.index') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors text-center"
                    >
                        Cari Jadwal Perjalanan &rarr;
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

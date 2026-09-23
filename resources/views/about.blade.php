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

        <!-- Cara Memilih Kursi & Melihat Pesanan -->
        <section class="space-y-6 pt-4 border-t border-slate-200">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Panduan Kursi & Pemantauan Pesanan
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Informasi penting seputar transparansi nomor kursi dan akses tiket digital:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-3 font-bold text-sm">
                        01
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 mb-2">Cara Memilih Kursi</h3>
                    <p class="text-sm text-slate-600 leading-relaxed mb-3">
                        Setiap armada bus memiliki tata letak 2+2 yang terpetakan secara digital. Kursi yang masih kosong dapat Anda klik untuk dipilih. Setelah memilih, Anda dapat melihat nomor kursi dan estimasi total biaya secara real-time sebelum melanjutkan.
                    </p>
                    <div class="text-xs text-slate-500 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <strong>Catatan:</strong> Kursi yang telah dipesan oleh penumpang lain akan terkunci secara otomatis untuk mencegah pemesanan ganda (double-booking).
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center mb-3 font-bold text-sm">
                        02
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 mb-2">Cara Melihat Pesanan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed mb-3">
                        Setelah pesanan berhasil dibuat, sistem akan menerbitkan kode pesanan unik (ORD-XXXXXXXX). Anda dapat mengakses seluruh tiket kapan saja melalui menu <strong>Pesanan Saya</strong> atau halaman <strong>Dasbor Pelanggan</strong>.
                    </p>
                    <div class="text-xs text-slate-500 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <strong>Tips:</strong> Anda dapat mencetak tiket secara langsung menggunakan tombol "Cetak Tiket" atau cukup menunjukkan e-tiket pada layar ponsel saat verifikasi boarding di terminal.
                    </div>
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
                        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between shadow-sm">
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

        <!-- FAQ Lengkap (Alpine Accordion) -->
        <section class="space-y-6 pt-4 border-t border-slate-200" x-data="{ faqOpen: null }">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Pertanyaan yang Sering Diajukan (FAQ)
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Informasi praktis seputar kebijakan pemesanan, tiket, dan perjalanan:
                </p>
            </div>

            <div class="space-y-3">
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                    <button
                        type="button"
                        @click="faqOpen = (faqOpen === 1 ? null : 1)"
                        class="w-full px-5 py-4 text-left flex items-center justify-between text-sm font-semibold text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                        <span>Apakah harga tiket di website sudah termasuk seluruh biaya?</span>
                        <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="faqOpen === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="faqOpen === 1" x-cloak class="px-5 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Ya. Tarif yang ditampilkan pada detail perjalanan dan ringkasan pemesanan adalah tarif resmi per penumpang tanpa biaya tersembunyi atau biaya administrasi tambahan.
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                    <button
                        type="button"
                        @click="faqOpen = (faqOpen === 2 ? null : 2)"
                        class="w-full px-5 py-4 text-left flex items-center justify-between text-sm font-semibold text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                        <span>Bagaimana jika penumpang belum memiliki KTP?</span>
                        <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="faqOpen === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="faqOpen === 2" x-cloak class="px-5 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Untuk penumpang anak-anak atau yang belum memiliki KTP, Anda dapat mencantumkan Nomor Induk Kependudukan (NIK) yang tertera pada Kartu Keluarga (KK) atau nomor kartu identitas pelajar.
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                    <button
                        type="button"
                        @click="faqOpen = (faqOpen === 3 ? null : 3)"
                        class="w-full px-5 py-4 text-left flex items-center justify-between text-sm font-semibold text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                        <span>Berapa kapasitas bagasi yang diperbolehkan?</span>
                        <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="faqOpen === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="faqOpen === 3" x-cloak class="px-5 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Setiap penumpang diperkenankan membawa 1 koper/tas ukuran standar untuk bagasi bawah dan 1 tas jinjing untuk kompartemen kabin atas dengan berat wajar.
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                    <button
                        type="button"
                        @click="faqOpen = (faqOpen === 4 ? null : 4)"
                        class="w-full px-5 py-4 text-left flex items-center justify-between text-sm font-semibold text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                        <span>Apakah tiket bisa dibatalkan?</span>
                        <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="faqOpen === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="faqOpen === 4" x-cloak class="px-5 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Pembatalan atau perubahan jadwal dapat dilakukan melalui konfirmasi langsung dengan petugas operasional terminal kami sebelum bus diberangkatkan sesuai dengan ketentuan yang berlaku.
                    </div>
                </div>
            </div>
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

@extends('layouts.app')

@section('title', 'PO CAN Travel - Perjalanan Antarkota, Lebih Mudah Dipesan')
@section('meta_description', 'Pesan tiket bus antarkota resmi PO CAN Travel. Pilih nomor kursi sendiri, cek rute dan jadwal real-time langsung dari armada bus terpercaya.')

@section('content')
<!-- 2. Hero & 3. Search Perjalanan -->
<section class="bg-white border-b border-slate-200 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-8">
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 block mb-2">Platform Resmi Pemesanan Bus Antarkota</span>
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight leading-tight">
                Perjalanan antarkota, lebih mudah dipesan.
            </h1>
            <p class="mt-3 text-base sm:text-lg text-slate-600 leading-relaxed">
                Temukan jadwal perjalanan bus, pilih nomor kursi Anda secara langsung dari denah bus, dan selesaikan pemesanan tanpa biaya tersembunyi.
            </p>
        </div>

        <!-- 3. Integrated Search Form with Origin/Destination Swap -->
        <div
            class="bg-slate-50 border border-slate-200 rounded-xl p-5 sm:p-7 shadow-sm"
            x-data="{
                origin: '{{ request('origin') }}',
                destination: '{{ request('destination') }}',
                swap() {
                    let temp = this.origin;
                    this.origin = this.destination;
                    this.destination = temp;
                }
            }"
        >
            <form method="GET" action="{{ route('customer.trips.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <!-- Kota Asal -->
                    <div class="md:col-span-4">
                        <label for="search_origin" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Dari (Kota Asal)
                        </label>
                        <select
                            name="origin"
                            id="search_origin"
                            x-model="origin"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                            <option value="">Semua Kota Asal</option>
                            @foreach ($origins as $origin)
                                <option value="{{ $origin }}" {{ request('origin') === $origin ? 'selected' : '' }}>
                                    {{ $origin }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Tukar Asal / Tujuan -->
                    <div class="md:col-span-1 flex items-center justify-center">
                        <button
                            type="button"
                            @click="swap()"
                            title="Tukar Kota Asal dan Tujuan"
                            aria-label="Tukar Kota Asal dan Tujuan"
                            class="w-10 h-10 flex items-center justify-center rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </button>
                    </div>

                    <!-- Kota Tujuan -->
                    <div class="md:col-span-4">
                        <label for="search_destination" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Ke (Kota Tujuan)
                        </label>
                        <select
                            name="destination"
                            id="search_destination"
                            x-model="destination"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                            <option value="">Semua Kota Tujuan</option>
                            @foreach ($destinations as $destination)
                                <option value="{{ $destination }}" {{ request('destination') === $destination ? 'selected' : '' }}>
                                    {{ $destination }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Keberangkatan -->
                    <div class="md:col-span-3">
                        <label for="search_date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tanggal Keberangkatan
                        </label>
                        <input
                            type="date"
                            name="departure_date"
                            id="search_date"
                            value="{{ request('departure_date') }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        >
                    </div>
                </div>

                <div class="mt-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 border-t border-slate-200">
                    <p class="text-xs text-slate-500">
                        Pilih kota asal, tujuan, atau tanggal untuk menemukan jadwal perjalanan resmi.
                    </p>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                    >
                        Cari Perjalanan &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- 4. Rute yang Tersedia Section -->
<section class="py-12 sm:py-16 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block mb-1">Jalur Resmi</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Rute yang Tersedia
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Jalur perjalanan antarkota aktif yang dilayani oleh armada bus PO CAN Travel.
                </p>
            </div>
            <div>
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1"
                >
                    Lihat Seluruh Jadwal &rarr;
                </a>
            </div>
        </div>

        @if ($routes->isEmpty())
            <div class="bg-white border border-slate-200 rounded-xl p-8 text-center text-sm text-slate-500">
                Belum ada rute perjalanan yang aktif saat ini.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-5 mb-10">
                @foreach ($routes as $route)
                    <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 flex flex-col justify-between hover:border-slate-300 transition-colors shadow-sm">
                        <div>
                            <div class="text-base font-semibold text-slate-900 mb-1">
                                {{ $route->origin }} &rarr; {{ $route->destination }}
                            </div>
                            <p class="text-xs text-slate-500">
                                Estimasi durasi: {{ floor($route->duration / 60) }} jam {{ $route->duration % 60 > 0 ? ($route->duration % 60) . ' menit' : '' }}
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">
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

        <!-- 5. Jadwal Keberangkatan Terdekat -->
        @if ($availableTrips->isNotEmpty())
            <div class="pt-6 border-t border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900">
                        Jadwal Keberangkatan Terdekat
                    </h3>
                    <span class="text-xs text-slate-500">Diperbarui sesuai sistem operasional</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                    @foreach ($availableTrips as $trip)
                        @php
                            $remainingSeats = max(0, $trip->bus->total_seats - ($trip->booked_seats_count ?? 0));
                        @endphp
                        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between hover:border-slate-300 transition-colors shadow-sm">
                            <div>
                                <div class="flex items-start justify-between gap-3 pb-3 mb-3 border-b border-slate-100">
                                    <div>
                                        <div class="text-base font-semibold text-slate-900">
                                            {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            {{ $trip->bus->name }} &bull; <span class="font-mono">{{ $trip->bus->code }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        @if($remainingSeats > 5)
                                            <span class="text-xs font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                Sisa {{ $remainingSeats }} kursi
                                            </span>
                                        @elseif($remainingSeats > 0)
                                            <span class="text-xs font-medium text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                                Sisa {{ $remainingSeats }} kursi
                                            </span>
                                        @else
                                            <span class="text-xs font-medium text-rose-800 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                                Habis
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-xs text-slate-600 py-1">
                                    <div>
                                        <span class="block text-slate-400">Keberangkatan:</span>
                                        <span class="font-medium text-slate-900 text-sm tabular-nums">
                                            {{ $trip->departure_at->format('H.i') }} WIB
                                        </span>
                                        <span class="block text-slate-500">
                                            {{ $trip->departure_at->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="block text-slate-400">Estimasi Tiba:</span>
                                        <span class="font-medium text-slate-900 text-sm tabular-nums">
                                            {{ $trip->arrival_at->format('H.i') }} WIB
                                        </span>
                                        <span class="block text-slate-500">
                                            {{ $trip->arrival_at->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-xs text-slate-400 block">Tarif per Kursi</span>
                                    <span class="text-lg font-bold text-slate-900 tabular-nums">
                                        Rp{{ number_format($trip->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <a
                                    href="{{ route('customer.trips.show', $trip) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs rounded-lg transition-colors"
                                >
                                    Pilih Kursi &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<!-- 6. Cara Memesan Section -->
<section class="py-12 sm:py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mb-10">
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 block mb-1">Panduan Praktis</span>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Cara Memesan Tiket
            </h2>
            <p class="mt-1.5 text-sm text-slate-600">
                Empat langkah mudah untuk memesan tiket perjalanan bus antarkota Anda tanpa kebingungan.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/50">
                <span class="text-3xl font-bold font-mono text-blue-600 block mb-2">01</span>
                <h3 class="text-base font-semibold text-slate-900 mb-1.5">Cari Perjalanan</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Tentukan kota asal, kota tujuan, dan tanggal keberangkatan yang sesuai dengan agenda bepergian Anda.
                </p>
            </div>

            <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/50">
                <span class="text-3xl font-bold font-mono text-blue-600 block mb-2">02</span>
                <h3 class="text-base font-semibold text-slate-900 mb-1.5">Pilih Kursi</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Lihat denah tata letak bus 2+2 secara transparan dan tentukan nomor kursi favorit yang masih tersedia.
                </p>
            </div>

            <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/50">
                <span class="text-3xl font-bold font-mono text-blue-600 block mb-2">03</span>
                <h3 class="text-base font-semibold text-slate-900 mb-1.5">Isi Data Penumpang</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Masukkan identitas resmi (nama lengkap & nomor KTP/identitas) untuk tiap kursi yang dipesan.
                </p>
            </div>

            <div class="p-5 rounded-xl border border-slate-100 bg-slate-50/50">
                <span class="text-3xl font-bold font-mono text-blue-600 block mb-2">04</span>
                <h3 class="text-base font-semibold text-slate-900 mb-1.5">Selesaikan Pesanan</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Pesanan Anda terbit dengan kode unik resmi dan tersimpan rapi pada akun Anda untuk verifikasi boarding.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- 7. Product Introduction Section -->
<section class="py-12 sm:py-16 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 block mb-2">Platform Pemesanan Resmi</span>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-4">
                Tentang PO CAN Travel
            </h2>
            <p class="text-sm sm:text-base text-slate-600 leading-relaxed mb-4">
                PO CAN Travel menyediakan sistem pemesanan tiket digital terpadu untuk armada bus antarkota. Kami berkomitmen memberikan kemudahan bagi penumpang agar dapat melihat jadwal keberangkatan akurat, ketersediaan nomor kursi secara mandiri, dan transparansi tarif tanpa biaya terselubung.
            </p>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                Seluruh data jadwal, tarif, armada, dan transaksi terintegrasi langsung dengan operasional keberangkatan di terminal resmi, menjamin kepastian perjalanan Anda dari kota asal hingga tujuan.
            </p>
            <div>
                <a
                    href="{{ route('about') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1"
                >
                    Pelajari Selengkapnya Tentang PO CAN Travel &rarr;
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 8. FAQ Singkat Section (Alpine.js accordion) -->
<section class="py-12 sm:py-16 bg-white border-b border-slate-200" x-data="{ activeFaq: null }">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center sm:text-left">
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 block mb-1">Informasi Penting</span>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Pertanyaan yang Sering Diajukan
            </h2>
            <p class="mt-1 text-sm text-slate-600">
                Jawaban praktis seputar pemesanan tiket, pemilihan kursi, dan keberangkatan.
            </p>
        </div>

        <div class="space-y-3">
            <!-- FAQ 1 -->
            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                <button
                    type="button"
                    @click="activeFaq = (activeFaq === 1 ? null : 1)"
                    class="w-full px-5 py-4 text-left flex items-center justify-between text-sm font-semibold text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                >
                    <span>Bagaimana cara memilih nomor kursi bus?</span>
                    <svg
                        class="w-4 h-4 text-slate-500 transition-transform duration-200"
                        :class="activeFaq === 1 ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeFaq === 1" x-cloak class="px-5 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Setelah memilih jadwal keberangkatan, Anda akan diarahkan ke denah visual bus. Kursi berwarna putih menandakan kursi tersedia. Anda dapat memilih satu atau beberapa kursi sekaligus sebelum melanjutkan ke pengisian data penumpang.
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                <button
                    type="button"
                    @click="activeFaq = (activeFaq === 2 ? null : 2)"
                    class="w-full px-5 py-4 text-left flex items-center justify-between text-sm font-semibold text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                >
                    <span>Apakah tiket digital perlu dicetak?</span>
                    <svg
                        class="w-4 h-4 text-slate-500 transition-transform duration-200"
                        :class="activeFaq === 2 ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeFaq === 2" x-cloak class="px-5 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Tidak wajib. Anda cukup memperlihatkan kode pesanan unik (misal: ORD-XXXXXXXX) dan rincian e-tiket pada layar ponsel Anda kepada petugas loket atau kondektur bus di terminal keberangkatan. Tersedia juga opsi cetak jika Anda memerlukan bukti fisik.
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                <button
                    type="button"
                    @click="activeFaq = (activeFaq === 3 ? null : 3)"
                    class="w-full px-5 py-4 text-left flex items-center justify-between text-sm font-semibold text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                >
                    <span>Di mana saya dapat melihat kembali pesanan tiket saya?</span>
                    <svg
                        class="w-4 h-4 text-slate-500 transition-transform duration-200"
                        :class="activeFaq === 3 ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeFaq === 3" x-cloak class="px-5 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Semua pesanan yang pernah Anda lakukan tersimpan di menu <strong>Pesanan Saya</strong> atau <strong>Dasbor Pelanggan</strong> setelah Anda masuk ke akun. Anda dapat melacak status, rute, daftar kursi, dan rincian transaksi kapan saja.
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                <button
                    type="button"
                    @click="activeFaq = (activeFaq === 4 ? null : 4)"
                    class="w-full px-5 py-4 text-left flex items-center justify-between text-sm font-semibold text-slate-900 hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600"
                >
                    <span>Kapan saya harus tiba di terminal bus?</span>
                    <svg
                        class="w-4 h-4 text-slate-500 transition-transform duration-200"
                        :class="activeFaq === 4 ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="activeFaq === 4" x-cloak class="px-5 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Kami merekomendasikan seluruh penumpang tiba di terminal keberangkatan selambat-lambatnya 30 menit sebelum jadwal keberangkatan yang tertera pada tiket untuk proses verifikasi identitas dan penempatan bagasi.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. CTA Section -->
<section class="py-12 sm:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 text-white rounded-2xl p-8 sm:p-12 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="max-w-xl">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white mb-2">
                    Siap Memulai Perjalanan Anda?
                </h2>
                <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                    Cari jadwal keberangkatan bus resmi dan amankan kursi perjalanan pilihan Anda sekarang juga.
                </p>
            </div>
            <div class="shrink-0">
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors text-center"
                >
                    Cari Jadwal Perjalanan &rarr;
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

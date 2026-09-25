<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'PO CAN Travel — Platform Pemesanan Tiket Bus Antarkota')</title>
    <meta name="description" content="@yield('meta_description', 'Platform pemesanan tiket bus antarkota resmi PO CAN Travel. Cari rute, pilih jadwal, tentukan nomor kursi mandiri di denah kabin bus, dan pesan tiket langsung.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Manrope (Headlines, Numbers, Navigation) + Inter (Body, UI, Metadata) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col antialiased bg-[#F7F8F6] text-[#15202B] overflow-x-hidden w-full font-sans" x-data="{ mobileMenuOpen: false }">

    <!-- ═════════════════════════════════════════════════════════════════
         GLOBAL EXECUTIVE NAVBAR (DESKTOP + MOBILE DRAWER)
         Layout: [LOGO]               [CENTER NAVIGATION]            [AUTH]
         Height: 74px, Background: White/95 with backdrop blur, Border: Slate 200
         ═════════════════════════════════════════════════════════════════ -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 w-full transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[74px] flex items-center justify-between gap-4">

            <!-- LEFT: Brand Logo (PO CAN Travel) -->
            <div class="flex items-center shrink-0">
                <a href="{{ route('home') }}" class="inline-flex items-center focus:outline-none focus:ring-2 focus:ring-[#F97316] rounded-xl py-1 group" aria-label="PO CAN Travel Beranda">
                    <img src="{{ asset('images/logo.png') }}" alt="PO CAN Travel" class="h-9 sm:h-10 md:h-11 w-auto object-contain transition-transform group-hover:scale-[1.02]">
                </a>
            </div>

            <!-- CENTER: Centered Navigation Links (Desktop) -->
            <nav class="hidden md:flex items-center justify-center gap-8 flex-1 px-4" aria-label="Navigasi Utama">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('trips.index') }}" class="nav-link {{ request()->routeIs('trips.*') ? 'active' : '' }}">
                    Tiket
                </a>
                <a href="{{ route('buses.index') }}" class="nav-link {{ request()->routeIs('buses.*') ? 'active' : '' }}">
                    Armada
                </a>
                <a href="{{ route('facilities.index') }}" class="nav-link {{ request()->routeIs('facilities.*') ? 'active' : '' }}">
                    Fasilitas
                </a>
                <a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs(['faq', 'how-to-order', 'departure-info', 'about']) ? 'active' : '' }}">
                    Bantuan
                </a>
            </nav>

            <!-- RIGHT: Actions by User Role & Mobile Hamburger Button -->
            <div class="flex items-center gap-3 shrink-0">
                @guest
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-heading font-bold text-slate-700 hover:text-orange-600 rounded-full hover:bg-orange-50/60 transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 text-xs font-heading font-bold rounded-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white transition-all shadow-xs hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-orange-500">
                            Daftar
                        </a>
                    </div>
                @else
                    @if(auth()->user()->role === 'admin')
                        <div class="hidden sm:flex items-center gap-3 text-xs font-semibold">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                Dasbor Admin
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                Kelola Pesanan
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1.5 text-xs text-slate-500 hover:text-red-600 transition-colors cursor-pointer rounded-full hover:bg-red-50 focus:outline-none">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center gap-3 text-xs font-semibold">
                            <a href="{{ route('customer.orders.index') }}" class="nav-link text-slate-800 {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                                Riwayat Pesanan
                            </a>
                            <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-orange-50 text-orange-600 border border-orange-200/80 font-bold hover:bg-orange-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>{{ Str::words(auth()->user()->name, 1, '') }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1.5 text-xs text-slate-500 hover:text-red-600 transition-colors cursor-pointer rounded-full hover:bg-red-50 focus:outline-none">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @endif
                @endguest

                <!-- Mobile Hamburger Button (min 44x44px target) -->
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    type="button"
                    class="md:hidden w-11 h-11 inline-flex items-center justify-center text-slate-700 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer"
                    aria-label="Buka Menu Navigasi"
                    :aria-expanded="mobileMenuOpen"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t border-slate-100 bg-white w-full shadow-lg"
            style="display: none;"
        >
            <nav class="max-w-7xl mx-auto px-4 py-4 flex flex-col gap-1 text-sm font-semibold">
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="px-3.5 py-2.5 rounded-xl min-h-[44px] flex items-center text-slate-700 hover:bg-orange-50 hover:text-orange-600 {{ request()->routeIs('home') ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('trips.index') }}" @click="mobileMenuOpen = false" class="px-3.5 py-2.5 rounded-xl min-h-[44px] flex items-center text-slate-700 hover:bg-orange-50 hover:text-orange-600 {{ request()->routeIs('trips.*') ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
                    Tiket
                </a>
                <a href="{{ route('buses.index') }}" @click="mobileMenuOpen = false" class="px-3.5 py-2.5 rounded-xl min-h-[44px] flex items-center text-slate-700 hover:bg-orange-50 hover:text-orange-600 {{ request()->routeIs('buses.*') ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
                    Armada
                </a>
                <a href="{{ route('facilities.index') }}" @click="mobileMenuOpen = false" class="px-3.5 py-2.5 rounded-xl min-h-[44px] flex items-center text-slate-700 hover:bg-orange-50 hover:text-orange-600 {{ request()->routeIs('facilities.*') ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
                    Fasilitas
                </a>
                <a href="{{ route('faq') }}" @click="mobileMenuOpen = false" class="px-3.5 py-2.5 rounded-xl min-h-[44px] flex items-center text-slate-700 hover:bg-orange-50 hover:text-orange-600 {{ request()->routeIs(['faq', 'how-to-order', 'departure-info', 'about']) ? 'bg-orange-50 text-orange-600 font-bold' : '' }}">
                    Bantuan
                </a>

                @auth
                    <div class="my-3 pt-3 border-t border-slate-100">
                        <span class="px-3.5 text-xs uppercase font-bold text-slate-400 tracking-wider block mb-1">Akun Saya</span>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false" class="px-3.5 py-2.5 rounded-xl min-h-[44px] flex items-center text-slate-700 hover:bg-orange-50 hover:text-orange-600">
                                Dasbor Admin
                            </a>
                            <a href="{{ route('admin.orders.index') }}" @click="mobileMenuOpen = false" class="px-3.5 py-2.5 rounded-xl min-h-[44px] flex items-center text-slate-700 hover:bg-orange-50 hover:text-orange-600">
                                Kelola Pesanan
                            </a>
                        @else
                            <a href="{{ route('customer.orders.index') }}" @click="mobileMenuOpen = false" class="px-3.5 py-2.5 rounded-xl min-h-[44px] flex items-center text-slate-700 hover:bg-orange-50 hover:text-orange-600">
                                Riwayat Pesanan
                            </a>
                            <a href="{{ route('customer.dashboard') }}" @click="mobileMenuOpen = false" class="px-3.5 py-2.5 rounded-xl min-h-[44px] flex items-center text-slate-700 hover:bg-orange-50 hover:text-orange-600">
                                Dasbor Pelanggan ({{ auth()->user()->name }})
                            </a>
                        @endif
                    </div>
                @endauth

                <div class="pt-3 mt-2 border-t border-slate-100">
                    @guest
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('login') }}" class="py-2.5 min-h-[44px] flex items-center justify-center text-center text-xs font-heading font-bold rounded-full border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="py-2.5 min-h-[44px] flex items-center justify-center text-center text-xs font-heading font-bold rounded-full bg-gradient-to-r from-orange-500 to-amber-500 text-white hover:from-orange-600 hover:to-amber-600 shadow-xs transition-colors">
                                Daftar
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-2.5 min-h-[44px] flex items-center justify-center text-center text-xs font-bold uppercase tracking-wider rounded-full border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                                Keluar dari Akun
                            </button>
                        </form>
                    @endguest
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content Canvas -->
    <main class="flex-grow w-full">
        @yield('content')
    </main>

    <!-- ═════════════════════════════════════════════════════════════════
         GLOBAL CLEAN MODERN LIGHT FOOTER
         ═════════════════════════════════════════════════════════════════ -->
    <footer class="w-full bg-white text-slate-800 mt-auto border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-12">

                <!-- Col 1: Brand & Tagline -->
                <div class="lg:col-span-5 flex flex-col gap-4">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo.png') }}" alt="PO CAN Travel" class="h-10 sm:h-11 w-auto object-contain">
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                        Perjalanan antarkota yang lebih mudah dipesan. Kepastian nomor kursi real-time, armada eksekutif modern, dan transparansi tarif tanpa biaya siluman.
                    </p>
                    <div class="flex items-center gap-3 pt-1">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 border border-orange-200/80 text-[11px] font-heading font-bold text-orange-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                            Layanan 24/7 Siaga
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-50 border border-sky-200/80 text-[11px] font-heading font-bold text-sky-600">
                            Tiket Resmi Terverifikasi
                        </span>
                    </div>
                </div>

                <!-- Col 2: Navigasi -->
                <div class="lg:col-span-3 flex flex-col gap-3">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider font-heading">Navigasi Utama</span>
                    <ul class="flex flex-col gap-2.5 text-xs text-slate-500">
                        <li><a href="{{ route('home') }}" class="hover:text-orange-600 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('trips.index') }}" class="hover:text-orange-600 transition-colors">Cari Tiket</a></li>
                        <li><a href="{{ route('buses.index') }}" class="hover:text-orange-600 transition-colors">Pilihan Armada</a></li>
                        <li><a href="{{ route('facilities.index') }}" class="hover:text-orange-600 transition-colors">Fasilitas Kabin</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-orange-600 transition-colors">Pusat Bantuan</a></li>
                    </ul>
                </div>

                <!-- Col 3: Informasi & Panduan -->
                <div class="lg:col-span-4 flex flex-col gap-3">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider font-heading">Panduan Perjalanan</span>
                    <ul class="flex flex-col gap-2.5 text-xs text-slate-500">
                        <li><a href="{{ route('how-to-order') }}" class="hover:text-orange-600 transition-colors">Cara Memesan Tiket</a></li>
                        <li><a href="{{ route('departure-info') }}" class="hover:text-orange-600 transition-colors">Informasi Keberangkatan</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-orange-600 transition-colors">Pertanyaan Umum (FAQ)</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-orange-600 transition-colors">Tentang PO CAN Travel</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Legal Bar -->
            <div class="mt-12 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} PO CAN Travel. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-5">
                    <a href="{{ route('how-to-order') }}" class="hover:text-orange-600 transition-colors">Cara Memesan</a>
                    <a href="{{ route('faq') }}" class="hover:text-orange-600 transition-colors">FAQ</a>
                    <a href="{{ route('about') }}" class="hover:text-orange-600 transition-colors">Tentang</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

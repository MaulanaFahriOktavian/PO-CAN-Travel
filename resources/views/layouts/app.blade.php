<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'PO CAN Travel — Tiket Bus Antarkota')</title>
    <meta name="description" content="@yield('meta_description', 'Cari jadwal, pilih kursi, dan pesan tiket bus antarkota PO CAN Travel. Sederhana, langsung, tanpa biaya tersembunyi.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Fonts: Inter (UI) + Fraunces (Display Headings) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500;1,9..144,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col antialiased bg-[var(--color-bg)] text-[var(--color-text)]" x-data="{ mobileMenuOpen: false }">

    <!-- ═════════════════════════════════════════════════════════════════
         NAVBAR — Logo left | Links center (true dead-center) | Auth right
         ═════════════════════════════════════════════════════════════════ -->
    <header class="sticky top-0 z-50 bg-[var(--color-bg)] border-b border-[var(--color-border)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 grid grid-cols-[auto_1fr_auto] lg:grid-cols-[220px_1fr_220px] items-center">

            <!-- LEFT: Brand Logo -->
            <div class="flex items-center justify-start">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 focus:outline-none text-[var(--color-primary)]">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect width="28" height="28" rx="6" fill="#173D2E"/>
                        <path d="M6 18V11C6 9.895 6.895 9 8 9h12c1.105 0 2 .895 2 2v7M6 18h16M8 18v2M20 18v2M7 14h14" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="10" cy="16" r="1" fill="white"/>
                        <circle cx="18" cy="16" r="1" fill="white"/>
                    </svg>
                    <span class="font-bold text-base tracking-tight text-[var(--color-text)]">
                        PO CAN <span class="text-[var(--color-primary)] font-semibold">Travel</span>
                    </span>
                </a>
            </div>

            <!-- CENTER: Mathematically Centered Navigation Links -->
            <nav class="hidden lg:flex items-center justify-center gap-8" aria-label="Navigasi Utama">
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
                <a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs(['faq', 'how-to-order', 'departure-info']) ? 'active' : '' }}">
                    Bantuan
                </a>
            </nav>

            <!-- RIGHT: Actions by User Role -->
            <div class="flex items-center justify-end gap-4">
                @guest
                    <div class="hidden sm:flex items-center gap-4 text-sm font-medium">
                        <a href="{{ route('login') }}" class="nav-link text-xs">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-3 py-1.5 text-xs font-semibold border border-[var(--color-primary)] text-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-white transition-colors">
                            Daftar
                        </a>
                    </div>
                @else
                    @if(auth()->user()->role === 'admin')
                        <div class="hidden sm:flex items-center gap-4 text-xs font-medium">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                Dasbor Admin
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                Kelola Pesanan
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-[var(--color-text-muted)] hover:text-[var(--color-danger)] transition-colors cursor-pointer">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center gap-4 text-xs font-medium">
                            <a href="{{ route('customer.orders.index') }}" class="nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                                Tiket Saya <span class="sr-only">Riwayat Pesanan</span>
                            </a>
                            <a href="{{ route('customer.dashboard') }}" class="nav-link font-semibold {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                                {{ Str::words(auth()->user()->name, 1, '') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-[var(--color-text-muted)] hover:text-[var(--color-danger)] transition-colors cursor-pointer">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @endif
                @endguest

                <!-- Mobile Hamburger Button -->
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    type="button"
                    class="lg:hidden p-2 text-[var(--color-text)] hover:text-[var(--color-primary)] transition-colors"
                    aria-label="Buka Menu"
                >
                    <svg x-show="!mobileMenuOpen" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="lg:hidden border-t border-[var(--color-border)] bg-[var(--color-bg)]"
            style="display: none;"
        >
            <nav class="max-w-7xl mx-auto px-5 py-4 flex flex-col gap-1 text-sm font-medium">
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="px-3 py-2 text-[var(--color-text)] hover:bg-[var(--color-surface)] {{ request()->routeIs('home') ? 'font-bold text-[var(--color-primary)]' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('trips.index') }}" @click="mobileMenuOpen = false" class="px-3 py-2 text-[var(--color-text)] hover:bg-[var(--color-surface)] {{ request()->routeIs('trips.*') ? 'font-bold text-[var(--color-primary)]' : '' }}">
                    Tiket
                </a>
                <a href="{{ route('buses.index') }}" @click="mobileMenuOpen = false" class="px-3 py-2 text-[var(--color-text)] hover:bg-[var(--color-surface)] {{ request()->routeIs('buses.*') ? 'font-bold text-[var(--color-primary)]' : '' }}">
                    Armada
                </a>
                <a href="{{ route('facilities.index') }}" @click="mobileMenuOpen = false" class="px-3 py-2 text-[var(--color-text)] hover:bg-[var(--color-surface)] {{ request()->routeIs('facilities.*') ? 'font-bold text-[var(--color-primary)]' : '' }}">
                    Fasilitas
                </a>
                <a href="{{ route('faq') }}" @click="mobileMenuOpen = false" class="px-3 py-2 text-[var(--color-text)] hover:bg-[var(--color-surface)] {{ request()->routeIs('faq') ? 'font-bold text-[var(--color-primary)]' : '' }}">
                    Bantuan
                </a>

                @auth
                    <div class="my-2 pt-2 border-t border-[var(--color-border)]">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 text-[var(--color-text)] hover:bg-[var(--color-surface)]">
                                Dasbor Admin
                            </a>
                            <a href="{{ route('admin.orders.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 text-[var(--color-text)] hover:bg-[var(--color-surface)]">
                                Kelola Pesanan
                            </a>
                        @else
                            <a href="{{ route('customer.orders.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 text-[var(--color-text)] hover:bg-[var(--color-surface)]">
                                Tiket Saya <span class="sr-only">Riwayat Pesanan</span>
                            </a>
                            <a href="{{ route('customer.dashboard') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 text-[var(--color-text)] hover:bg-[var(--color-surface)]">
                                Dasbor ({{ auth()->user()->name }})
                            </a>
                        @endif
                    </div>
                @endauth

                <div class="pt-3 mt-2 border-t border-[var(--color-border)]">
                    @guest
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" class="py-2 text-center text-xs font-semibold border border-[var(--color-border)] text-[var(--color-text)] hover:bg-[var(--color-surface)] transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="py-2 text-center text-xs font-semibold bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-dark)] transition-colors">
                                Daftar
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-2 text-center text-xs font-semibold border border-[var(--color-border)] text-[var(--color-text-muted)] hover:text-[var(--color-danger)] transition-colors cursor-pointer">
                                Keluar
                            </button>
                        </form>
                    @endguest
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content Canvas -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- ═════════════════════════════════════════════════════════════════
         FOOTER — Deep Forest (#173D2E) with Warm Ivory text
         ═════════════════════════════════════════════════════════════════ -->
    <footer class="w-full bg-[var(--color-primary)] text-[#FAF6EE] mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

                <!-- Col 1: Brand & Identity -->
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-2.5">
                        <svg width="26" height="26" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect width="28" height="28" rx="6" fill="#0F2A20"/>
                            <path d="M6 18V11C6 9.895 6.895 9 8 9h12c1.105 0 2 .895 2 2v7M6 18h16M8 18v2M20 18v2M7 14h14" stroke="#9DC9B5" stroke-width="1.5" stroke-linecap="round"/>
                            <circle cx="10" cy="16" r="1" fill="#9DC9B5"/>
                            <circle cx="18" cy="16" r="1" fill="#9DC9B5"/>
                        </svg>
                        <span class="font-bold text-base text-white tracking-tight">PO CAN Travel</span>
                    </div>
                    <p class="text-xs text-[#C8DDD5] leading-relaxed max-w-sm">
                        Layanan bus antarkota dengan jadwal terencana, pemilihan nomor kursi mandiri di denah kabin, dan konfirmasi pesanan transparan.
                    </p>
                </div>

                <!-- Col 2: Navigasi -->
                <div class="flex flex-col gap-2.5">
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Navigasi</span>
                    <ul class="flex flex-col gap-2 text-xs text-[#C8DDD5]">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('trips.index') }}" class="hover:text-white transition-colors">Cari Tiket</a></li>
                        <li><a href="{{ route('buses.index') }}" class="hover:text-white transition-colors">Armada Bus</a></li>
                        <li><a href="{{ route('facilities.index') }}" class="hover:text-white transition-colors">Fasilitas Bus</a></li>
                        <li><a href="{{ route('routes.index') }}" class="hover:text-white transition-colors">Daftar Rute</a></li>
                    </ul>
                </div>

                <!-- Col 3: Layanan & Informasi -->
                <div class="flex flex-col gap-2.5">
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Informasi</span>
                    <ul class="flex flex-col gap-2 text-xs text-[#C8DDD5]">
                        <li><a href="{{ route('how-to-order') }}" class="hover:text-white transition-colors">Cara Memesan Tiket</a></li>
                        <li><a href="{{ route('departure-info') }}" class="hover:text-white transition-colors">Info Keberangkatan</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-white transition-colors">Pertanyaan Umum</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Col 4: Metode Pembayaran -->
                <div class="flex flex-col gap-2.5">
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Metode Pembayaran</span>
                    <p class="text-xs text-[#9DC9B5]">Dukungan pembayaran resmi:</p>
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        <span class="px-2 py-0.5 bg-[#0F2A20] text-xs font-mono font-medium text-[#FAF6EE] border border-[#2F6252]">BCA</span>
                        <span class="px-2 py-0.5 bg-[#0F2A20] text-xs font-mono font-medium text-[#FAF6EE] border border-[#2F6252]">Mandiri</span>
                        <span class="px-2 py-0.5 bg-[#0F2A20] text-xs font-mono font-medium text-[#FAF6EE] border border-[#2F6252]">BNI</span>
                        <span class="px-2 py-0.5 bg-[#0F2A20] text-xs font-mono font-medium text-[#FAF6EE] border border-[#2F6252]">BRI</span>
                        <span class="px-2 py-0.5 bg-[#0F2A20] text-xs font-mono font-medium text-[#FAF6EE] border border-[#2F6252]">QRIS</span>
                    </div>
                </div>

            </div>

            <!-- Bottom Legal Bar -->
            <div class="mt-10 pt-6 border-t border-[#0F2A20] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#6A9080]">
                <p>&copy; {{ date('Y') }} PO CAN Travel. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('how-to-order') }}" class="hover:text-white transition-colors">Ketentuan Pemesanan</a>
                    <a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang PO CAN Travel</a>
                    <a href="{{ route('faq') }}" class="hover:text-white transition-colors">Pusat Bantuan</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
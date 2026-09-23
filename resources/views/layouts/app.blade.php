<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'PO CAN Travel - Pemesanan Tiket Bus Online')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-800 font-sans antialiased">
    <!-- Header & Navigation -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo & Name -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-md">
                        <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-600 text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-8 4h4m5 4V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2h8a2 2 0 002-2zM6 19h12" />
                            </svg>
                        </span>
                        <span class="font-bold text-lg tracking-tight text-slate-900">PO CAN Travel</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8" aria-label="Navigasi Utama">
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-blue-600 transition-colors" aria-current="page">Beranda</a>
                    <a href="#cari-tiket" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Cari Tiket</a>
                </nav>

                <!-- Desktop Auth Actions -->
                <div class="hidden md:flex items-center space-x-3">
                    <a href="#login" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                        Login
                    </a>
                    <a href="#register" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors">
                        Register
                    </a>
                </div>

                <!-- Mobile Menu Button (Alpine.js interaction) -->
                <div class="flex md:hidden">
                    <button
                        type="button"
                        id="mobile-menu-toggle"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        :aria-expanded="mobileMenuOpen"
                        aria-controls="mobile-menu"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-600"
                        aria-label="Buka menu navigasi"
                    >
                        <!-- Hamburger Icon -->
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Close Icon -->
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu (Alpine.js driven) -->
        <div
            id="mobile-menu"
            x-show="mobileMenuOpen"
            x-cloak
            @click.outside="mobileMenuOpen = false"
            class="md:hidden border-t border-slate-200 bg-white"
        >
            <div class="px-4 pt-3 pb-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-semibold text-blue-600 bg-blue-50" aria-current="page">Beranda</a>
                <a href="#cari-tiket" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100">Cari Tiket</a>
            </div>
            <div class="pt-3 pb-4 border-t border-slate-100 px-4 space-y-2">
                <a href="#login" class="block text-center w-full px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    Login
                </a>
                <a href="#register" class="block text-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-colors">
                    Register
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                <div>
                    <span class="text-base font-bold text-slate-900">PO CAN Travel</span>
                    <p class="text-sm text-slate-600 mt-1">Layanan pemesanan tiket bus online untuk perjalanan antarkota.</p>
                </div>
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} PO CAN Travel. Seluruh hak cipta dilindungi.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>

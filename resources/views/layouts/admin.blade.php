<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8FAFC]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Console — PO CAN Travel')</title>
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Manrope + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-[#F8FAFC]" x-data="{ sidebarOpen: false }">

    <div class="min-h-full flex">

        <!-- ═════════════════════════════════════════════════════════════
             MOBILE SIDEBAR BACKDROP & DRAWER
             ═════════════════════════════════════════════════════════════ -->
        <div
            x-show="sidebarOpen"
            x-cloak
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        <!-- ═════════════════════════════════════════════════════════════
             SIDEBAR — putih bersih, border kanan tipis
             ═════════════════════════════════════════════════════════════ -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/80 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:translate-x-0 shadow-sm lg:shadow-none shrink-0"
        >
            <!-- Sidebar Header: Brand -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-100 bg-white">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('images/logo.png') }}" alt="PO CAN Travel" class="h-8 w-auto object-contain transition-transform group-hover:scale-[1.02]">
                    <span class="text-[9px] font-mono uppercase tracking-wider text-orange-600 bg-orange-50 border border-orange-200 px-1.5 py-0.5 rounded font-bold shrink-0">
                        Admin
                    </span>
                </a>

                <!-- Mobile Close Button -->
                <button
                    @click="sidebarOpen = false"
                    type="button"
                    class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                    aria-label="Tutup Sidebar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Admin User Profile Snippet -->
            <div class="px-5 py-4 border-b border-slate-100 bg-orange-50/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-500 to-amber-400 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="overflow-hidden flex-1">
                    <span class="font-heading font-bold text-sm text-slate-800 block truncate">
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </span>
                    <span class="text-[11px] text-emerald-600 font-medium flex items-center gap-1.5 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Administrator Aktif</span>
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6 text-xs font-semibold" aria-label="Menu Admin">

                <!-- Section: UTAMA -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2 font-heading">
                        Utama
                    </span>
                    <div class="space-y-1">
                        <!-- Dashboard -->
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}"
                        >
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Dasbor Overview</span>
                        </a>
                    </div>
                </div>

                <!-- Section: OPERASIONAL -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2 font-heading">
                        Operasional Bisnis
                    </span>
                    <div class="space-y-1">
                        <!-- Kelola Pesanan -->
                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-orange-500 text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                <span>Kelola Pesanan</span>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 font-bold border border-amber-200">Verifikasi</span>
                        </a>

                        <!-- Jadwal Perjalanan -->
                        <a
                            href="{{ route('admin.trips.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.trips.*') ? 'bg-orange-500 text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}"
                        >
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.trips.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Jadwal Perjalanan</span>
                        </a>

                        <!-- Armada Bus -->
                        <a
                            href="{{ route('admin.buses.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.buses.*') ? 'bg-orange-500 text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}"
                        >
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.buses.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span>Armada Bus</span>
                        </a>

                        <!-- Trayek / Rute -->
                        <a
                            href="{{ route('admin.routes.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.routes.*') ? 'bg-orange-500 text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}"
                        >
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.routes.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span>Trayek &amp; Rute</span>
                        </a>
                    </div>
                </div>

                <!-- Section: AKSES & AKUN -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2 font-heading">
                        Akses &amp; Akun
                    </span>
                    <div class="space-y-1">
                        <!-- Data Pengguna -->
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-orange-500 text-white font-bold shadow-sm' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}"
                        >
                            <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Kelola Pengguna</span>
                        </a>
                    </div>
                </div>

            </nav>

            <!-- Sidebar Footer: View Public Web & Logout -->
            <div class="p-4 border-t border-slate-100 bg-white space-y-1">
                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:text-orange-600 hover:bg-orange-50 transition-colors"
                >
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Situs Publik</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-mono">Buka &rarr;</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-red-500 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ═════════════════════════════════════════════════════════════
             MAIN VIEWPORT & TOPBAR
             ═════════════════════════════════════════════════════════════ -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

            <!-- Top Header Bar -->
            <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/90 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-sm">
                
                <div class="flex items-center gap-3">
                    <!-- Mobile Hamburger Toggle -->
                    <button
                        @click="sidebarOpen = true"
                        type="button"
                        class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors"
                        aria-label="Buka Menu"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Breadcrumbs / Page Section -->
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-slate-400 font-medium hidden sm:inline">Admin</span>
                        <span class="text-slate-300 hidden sm:inline">/</span>
                        <span class="font-heading font-bold text-slate-900 text-sm">
                            @yield('page_title', 'Konsol Operasional')
                        </span>
                    </div>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>Sistem Normal</span>
                    </span>

                    <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

                    <a
                        href="{{ route('admin.trips.create') }}"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Jadwal Baru</span>
                    </a>
                </div>
            </header>

            <!-- Flash Session Alerts -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 w-full">
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 w-full">
                    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Page Content Slot -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')
</body>
</html>

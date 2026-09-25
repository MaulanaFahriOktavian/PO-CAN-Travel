@extends('layouts.app')

@section('title', 'Masuk ke Akun — PO CAN Travel')
@section('meta_description', 'Masuk ke akun PO CAN Travel untuk mengelola pemesanan tiket bus, melihat denah kursi, dan mengakses e-ticket resmi perjalanan Anda.')

@section('content')
<div class="relative py-14 sm:py-20 min-h-[80vh] flex items-center justify-center overflow-hidden bg-[#FAFBFD]">
    <!-- Ambient Glow & Subtle Grid Background -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-gradient-to-tr from-orange-500/10 via-amber-500/10 to-sky-400/5 blur-3xl pointer-events-none -z-10 rounded-full"></div>
    <div class="absolute inset-0 bg-[radial-gradient(#F97316_1px,transparent_1px)] [background-size:28px_28px] opacity-[0.025] pointer-events-none -z-10"></div>

    <div class="max-w-md mx-auto px-4 sm:px-6 w-full relative z-10">

        <!-- Top Brand Logo & Heading -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block mb-4 focus:outline-none focus:ring-2 focus:ring-orange-500 rounded-xl">
                <img src="{{ asset('images/logo.png') }}" alt="PO CAN Travel" class="h-11 w-auto mx-auto object-contain">
            </a>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                Masuk ke Akun Anda
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-500 max-w-xs mx-auto">
                Masukkan email dan kata sandi Anda untuk mengakses tiket dan riwayat perjalanan.
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-[0_16px_40px_-15px_rgba(15,23,42,0.06)]">

            @if ($errors->has('email'))
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200/80 text-xs font-semibold text-rose-700 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>{{ $errors->first('email') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-heading font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-slate-50 focus:bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-medium placeholder-slate-400"
                            placeholder="nama@email.com"
                        >
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-heading font-bold text-slate-800 uppercase tracking-wider">
                            Kata Sandi
                        </label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-slate-50 focus:bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-medium placeholder-slate-400"
                            placeholder="••••••••"
                        >
                    </div>
                    @if ($errors->has('password') && !$errors->has('email'))
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500/20 accent-orange-500"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="text-xs text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-sm rounded-full shadow-md shadow-orange-500/20 transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-orange-500"
                    >
                        <span>Masuk ke Akun</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    Belum memiliki akun pelanggan?
                    <a href="{{ route('register') }}" class="font-heading font-bold text-orange-500 hover:text-orange-600 hover:underline ml-1">
                        Daftar sekarang &rarr;
                    </a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection

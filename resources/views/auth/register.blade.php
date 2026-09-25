@extends('layouts.app')

@section('title', 'Daftar Akun Pelanggan — PO CAN Travel')
@section('meta_description', 'Daftar akun PO CAN Travel untuk memesan tiket bus antarkota, memilih kursi sendiri, dan mengunduh e-ticket resmi secara instan.')

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
                Daftar Akun Baru
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-500 max-w-xs mx-auto">
                Lengkapi identitas Anda untuk memesan tiket dan memilih nomor kursi secara mandiri.
            </p>
        </div>

        <!-- Register Card -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-[0_16px_40px_-15px_rgba(15,23,42,0.06)]">

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-heading font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50/60' }} hover:bg-slate-50 focus:bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-medium placeholder-slate-400"
                            placeholder="Nama sesuai KTP / Identitas"
                        >
                    </div>
                    @if ($errors->has('name'))
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $errors->first('name') }}</p>
                    @endif
                </div>

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
                            autocomplete="email"
                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50/60' }} hover:bg-slate-50 focus:bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-medium placeholder-slate-400"
                            placeholder="nama@email.com"
                        >
                    </div>
                    @if ($errors->has('email'))
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password" class="block text-xs font-heading font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
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
                            autocomplete="new-password"
                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/20' : 'border-slate-200 bg-slate-50/60' }} hover:bg-slate-50 focus:bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-medium placeholder-slate-400"
                            placeholder="Minimal 8 karakter"
                        >
                    </div>
                    @if ($errors->has('password'))
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-heading font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                        Konfirmasi Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-slate-50 focus:bg-white text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-medium placeholder-slate-400"
                            placeholder="Ketik ulang kata sandi Anda"
                        >
                    </div>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-heading font-bold text-sm rounded-full shadow-md shadow-orange-500/20 transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-orange-500"
                    >
                        <span>Daftar Akun Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-heading font-bold text-orange-500 hover:text-orange-600 hover:underline ml-1">
                        Masuk di sini &rarr;
                    </a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan - PO CAN Travel')

@section('content')
<div class="py-16 sm:py-24 text-center">
    <div class="max-w-md mx-auto px-4 sm:px-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-500 mb-6">
            <span class="text-2xl font-bold font-mono">404</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Halaman Tidak Ditemukan</h1>
        <p class="mt-3 text-sm text-slate-600 leading-relaxed">
            Halaman atau jadwal perjalanan yang Anda cari tidak tersedia, sudah tidak aktif, atau tautan yang Anda ikuti sudah kedaluwarsa.
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="{{ route('home') }}"
                class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
            >
                Kembali ke Beranda
            </a>
            @auth
                @if (auth()->user()->role === 'customer')
                    <a
                        href="{{ route('customer.dashboard') }}"
                        class="w-full sm:w-auto px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg transition-colors text-center"
                    >
                        Dasbor Saya
                    </a>
                @elseif (auth()->user()->role === 'admin')
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="w-full sm:w-auto px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg transition-colors text-center"
                    >
                        Dasbor Admin
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection

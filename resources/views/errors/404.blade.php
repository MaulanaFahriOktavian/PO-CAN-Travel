@extends('layouts.app')

@section('title', '404 — Jadwal atau Halaman Tidak Ditemukan — PO CAN Travel')

@section('content')
<div class="py-20 sm:py-28 bg-[#F8FAFC] text-center min-h-[70vh] flex items-center justify-center">
    <div class="max-w-lg mx-auto px-4 sm:px-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white border border-slate-200 text-[#0B132B] shadow-xs mb-6">
            <span class="text-2xl font-bold font-mono">404</span>
        </div>
        
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
            Jadwal atau Halaman Tidak Ditemukan
        </h1>

        <p class="mt-3 text-sm text-slate-600 leading-relaxed max-w-md mx-auto">
            @if(!empty($exception) && $exception->getMessage())
                {{ $exception->getMessage() }}
            @else
                Jadwal perjalanan bus yang Anda cari mungkin telah selesai beroperasi, dibatalkan, atau tautan yang dituju sudah tidak aktif.
            @endif
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="{{ route('trips.index') }}"
                class="w-full sm:w-auto px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center inline-flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Cari Jadwal Perjalanan</span>
            </a>

            <a
                href="{{ route('home') }}"
                class="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg transition-colors text-center"
            >
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection

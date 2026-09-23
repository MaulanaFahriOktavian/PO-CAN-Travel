@extends('layouts.app')

@section('title', '419 - Sesi Kedaluwarsa - PO CAN Travel')

@section('content')
<div class="py-16 sm:py-24 text-center">
    <div class="max-w-md mx-auto px-4 sm:px-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-50 text-amber-700 mb-6 border border-amber-200">
            <span class="text-2xl font-bold font-mono">419</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Sesi Halaman Berakhir</h1>
        <p class="mt-3 text-sm text-slate-600 leading-relaxed">
            Halaman ini tidak aktif dalam beberapa waktu sehingga sesi keamanan formulir Anda telah berakhir. Silakan muat ulang halaman atau masuk kembali.
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <button
                type="button"
                onclick="window.location.reload();"
                class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
            >
                Muat Ulang Halaman
            </button>
            <a
                href="{{ route('login') }}"
                class="w-full sm:w-auto px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg transition-colors text-center"
            >
                Masuk Kembali
            </a>
        </div>
    </div>
</div>
@endsection

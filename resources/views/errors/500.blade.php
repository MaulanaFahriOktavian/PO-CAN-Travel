@extends('layouts.app')

@section('title', '500 - Terjadi Kendala Sistem - PO CAN Travel')

@section('content')
<div class="py-16 sm:py-24 text-center">
    <div class="max-w-md mx-auto px-4 sm:px-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-600 mb-6">
            <span class="text-2xl font-bold font-mono">500</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Terjadi Kendala Sistem</h1>
        <p class="mt-3 text-sm text-slate-600 leading-relaxed">
            Sistem kami sedang mengalami kendala sementara saat memproses permintaan Anda. Silakan coba kembali beberapa saat lagi.
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="{{ route('home') }}"
                class="w-full sm:w-auto px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
            >
                Kembali ke Beranda
            </a>
            <button
                type="button"
                onclick="window.location.reload();"
                class="w-full sm:w-auto px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium text-sm border border-slate-300 rounded-lg transition-colors text-center"
            >
                Coba Lagi
            </button>
        </div>
    </div>
</div>
@endsection

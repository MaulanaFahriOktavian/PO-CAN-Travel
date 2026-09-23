@extends('layouts.app')

@section('title', 'Dasbor Pelanggan - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="border-b border-slate-200 pb-6 mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Dasbor Pelanggan</h1>
            <p class="mt-1.5 text-sm text-slate-600">Informasi akun dan akses layanan perjalanan Anda.</p>
        </div>

        <div class="space-y-6">
            <!-- Informasi Akun -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Informasi Akun</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-slate-500 mb-0.5">Nama Pengguna</span>
                        <span class="font-medium text-slate-900">{{ auth()->user()->name }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 mb-0.5">Alamat Email</span>
                        <span class="font-medium text-slate-900">{{ auth()->user()->email }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 mb-0.5">Peran Akun</span>
                        <span class="text-slate-900">Customer</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 mb-0.5">Status Akun</span>
                        <span class="text-slate-900">Aktif</span>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-slate-100 text-sm text-slate-600">
                    Akun Anda aktif dan siap digunakan untuk memesan tiket perjalanan bus antarkota.
                </div>
            </div>

            <!-- Akses Layanan -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-slate-900 mb-2">Pemesanan Tiket</h2>
                <p class="text-sm text-slate-600 mb-5">
                    Silakan cari jadwal keberangkatan bus yang sesuai dengan rencana perjalanan Anda.
                </p>

                <a
                    href="{{ route('home') }}#cari-tiket"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors"
                >
                    Cari Jadwal Perjalanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

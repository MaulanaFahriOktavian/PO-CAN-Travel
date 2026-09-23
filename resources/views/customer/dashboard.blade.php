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
            <p class="mt-1.5 text-sm text-slate-600">Selamat datang, {{ auth()->user()->name }}. Kelola akun dan akses cepat layanan pemesanan tiket PO CAN Travel.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Akses Layanan Cari Tiket -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-7 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-slate-900 mb-1.5">Cari Perjalanan</h2>
                    <p class="text-sm text-slate-600 mb-6">
                        Temukan jadwal keberangkatan bus dan pilih kursi perjalanan yang Anda inginkan.
                    </p>
                </div>
                <a
                    href="{{ route('customer.trips.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
                >
                    Cari Jadwal Perjalanan &rarr;
                </a>
            </div>

            <!-- Akses Riwayat Pesanan -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-7 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-slate-900 mb-1.5">Riwayat Pesanan</h2>
                    <p class="text-sm text-slate-600 mb-6">
                        Pantau status tiket bus yang aktif serta riwayat seluruh transaksi pemesanan Anda.
                    </p>
                </div>
                <a
                    href="{{ route('customer.orders.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium text-sm rounded-lg shadow-sm transition-colors text-center"
                >
                    Buka Riwayat Pesanan &rarr;
                </a>
            </div>
        </div>

        <!-- Informasi Akun -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
            <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">Informasi Akun</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="block text-slate-500 text-xs mb-0.5">Nama Pengguna</span>
                    <span class="font-medium text-slate-900">{{ auth()->user()->name }}</span>
                </div>

                <div>
                    <span class="block text-slate-500 text-xs mb-0.5">Alamat Email</span>
                    <span class="font-medium font-mono text-slate-900">{{ auth()->user()->email }}</span>
                </div>

                <div>
                    <span class="block text-slate-500 text-xs mb-0.5">Tipe Akun</span>
                    <span class="text-slate-800 capitalize">{{ auth()->user()->role }}</span>
                </div>

                <div>
                    <span class="block text-slate-500 text-xs mb-0.5">Status Akun</span>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

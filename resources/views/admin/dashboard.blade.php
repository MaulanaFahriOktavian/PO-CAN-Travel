@extends('layouts.app')

@section('title', 'Dasbor Admin - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-b border-slate-200 pb-6 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Dasbor Administrator</h1>
                <p class="mt-1.5 text-sm text-slate-600">Pusat pengelolaan operasional armada, rute, jadwal perjalanan, dan pesanan.</p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-700 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Mode Administrator
                </span>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Modul Operasional Grid -->
            <div>
                <h2 class="text-base font-semibold text-slate-900 mb-4">Modul Operasional</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Armada Bus -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 flex flex-col justify-between hover:border-slate-300 transition-colors">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-slate-900 text-base">Armada Bus</h3>
                                <span class="text-xs font-mono text-slate-600 bg-slate-100 px-2 py-0.5 rounded">Master Data</span>
                            </div>
                            <p class="text-sm text-slate-600 mb-6">
                                Pengaturan armada bus, kode armada unik, dan sinkronisasi kapasitas kursi.
                            </p>
                        </div>
                        <a
                            href="{{ route('admin.buses.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 border border-slate-200 rounded-lg transition-colors text-center"
                        >
                            Buka Kelola Armada &rarr;
                        </a>
                    </div>

                    <!-- Rute Perjalanan -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 flex flex-col justify-between hover:border-slate-300 transition-colors">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-slate-900 text-base">Rute Perjalanan</h3>
                                <span class="text-xs font-mono text-slate-600 bg-slate-100 px-2 py-0.5 rounded">Master Data</span>
                            </div>
                            <p class="text-sm text-slate-600 mb-6">
                                Pengaturan titik kota asal, kota tujuan, dan estimasi durasi tempuh perjalanan.
                            </p>
                        </div>
                        <a
                            href="{{ route('admin.routes.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 border border-slate-200 rounded-lg transition-colors text-center"
                        >
                            Buka Kelola Rute &rarr;
                        </a>
                    </div>

                    <!-- Jadwal Perjalanan -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 flex flex-col justify-between hover:border-slate-300 transition-colors">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-slate-900 text-base">Jadwal Perjalanan</h3>
                                <span class="text-xs font-mono text-slate-600 bg-slate-100 px-2 py-0.5 rounded">Operasional</span>
                            </div>
                            <p class="text-sm text-slate-600 mb-6">
                                Penjadwalan trip keberangkatan, penetapan tarif tiket, dan pembaruan status perjalanan.
                            </p>
                        </div>
                        <a
                            href="{{ route('admin.trips.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 hover:text-slate-900 border border-slate-200 rounded-lg transition-colors text-center"
                        >
                            Buka Kelola Jadwal &rarr;
                        </a>
                    </div>

                    <!-- Pesanan Pelanggan -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 flex flex-col justify-between hover:border-slate-300 transition-colors">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-slate-900 text-base">Pesanan Pelanggan</h3>
                                <span class="text-xs font-mono text-slate-600 bg-slate-100 px-2 py-0.5 rounded">Transaksi</span>
                            </div>
                            <p class="text-sm text-slate-600 mb-6">
                                Pemantauan transaksi pemesanan tiket, konfirmasi pembayaran, dan kelola status tiket.
                            </p>
                        </div>
                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 border border-blue-200 rounded-lg transition-colors text-center font-medium"
                        >
                            Buka Kelola Pesanan &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informasi Akun Admin -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-7">
                <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">Informasi Akun</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="block text-slate-500 text-xs mb-0.5">Nama Pengguna</span>
                        <span class="font-medium text-slate-900">{{ auth()->user()->name }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 text-xs mb-0.5">Alamat Email</span>
                        <span class="font-medium font-mono text-slate-900">{{ auth()->user()->email }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 text-xs mb-0.5">Peran Akun</span>
                        <span class="text-slate-800 capitalize">{{ auth()->user()->role }}</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 text-xs mb-0.5">Akses Sistem</span>
                        <span class="text-slate-800">Administrator Operasional</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

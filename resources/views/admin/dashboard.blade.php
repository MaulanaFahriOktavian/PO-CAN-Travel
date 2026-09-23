@extends('layouts.app')

@section('title', 'Dasbor Admin - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-b border-slate-200 pb-6 mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Dasbor Administrator</h1>
            <p class="mt-1.5 text-sm text-slate-600">Pusat pengelolaan operasional dan administrasi PO CAN Travel.</p>
        </div>

        <div class="space-y-6">
            <!-- Informasi Akun Admin -->
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
                        <span class="text-slate-900">Admin</span>
                    </div>

                    <div>
                        <span class="block text-slate-500 mb-0.5">Akses Sistem</span>
                        <span class="text-slate-900">Administrator Operasional</span>
                    </div>
                </div>
            </div>

            <!-- Titik Masuk Modul Administrasi -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Modul Operasional</h2>
                
                <div class="space-y-4">
                    <div class="p-5 rounded-lg border border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="font-semibold text-slate-900 text-base">Kelola Armada Bus</div>
                            <p class="text-sm text-slate-600 mt-0.5">
                                Pengaturan master data armada, kode bus, dan sinkronisasi kapasitas kursi.
                            </p>
                        </div>
                        <a href="{{ route('admin.buses.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors whitespace-nowrap">
                            Buka Armada &rarr;
                        </a>
                    </div>

                    <div class="p-5 rounded-lg border border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="font-semibold text-slate-900 text-base">Kelola Rute Perjalanan</div>
                            <p class="text-sm text-slate-600 mt-0.5">
                                Pengaturan titik kota asal, kota tujuan, dan estimasi durasi tempuh perjalanan.
                            </p>
                        </div>
                        <a href="{{ route('admin.routes.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors whitespace-nowrap">
                            Buka Rute &rarr;
                        </a>
                    </div>

                    <div class="p-5 rounded-lg border border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="font-semibold text-slate-900 text-base">Kelola Jadwal Perjalanan</div>
                            <p class="text-sm text-slate-600 mt-0.5">
                                Penjadwalan trip, pemilihan armada, penetapan tarif tiket, dan pembaruan status keberangkatan.
                            </p>
                        </div>
                        <a href="{{ route('admin.trips.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors whitespace-nowrap">
                            Buka Jadwal &rarr;
                        </a>
                    </div>

                    <div class="p-5 rounded-lg border border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="font-semibold text-slate-900 text-base">Kelola Pesanan Pelanggan</div>
                            <p class="text-sm text-slate-600 mt-0.5">
                                Pemantauan transaksi tiket, verifikasi pembayaran, dan pembaruan status pesanan.
                            </p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors whitespace-nowrap">
                            Buka Pesanan &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

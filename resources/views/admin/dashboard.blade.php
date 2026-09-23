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
                    <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/50">
                        <div class="font-medium text-slate-900">Kelola Armada Bus</div>
                        <p class="text-sm text-slate-600 mt-0.5">
                            Pengaturan master data bus, kode unit, dan kapasitas kursi penumpang. Modul ini akan tersedia pada tahap berikutnya.
                        </p>
                    </div>

                    <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/50">
                        <div class="font-medium text-slate-900">Kelola Jadwal Perjalanan</div>
                        <p class="text-sm text-slate-600 mt-0.5">
                            Pengaturan rute keberangkatan, waktu tempuh, jadwal trip, dan tarif tiket. Modul ini akan tersedia pada tahap berikutnya.
                        </p>
                    </div>

                    <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/50">
                        <div class="font-medium text-slate-900">Pesanan Tiket</div>
                        <p class="text-sm text-slate-600 mt-0.5">
                            Pencatatan dan pemantauan transaksi tiket penumpang. Modul ini akan tersedia pada tahap berikutnya.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

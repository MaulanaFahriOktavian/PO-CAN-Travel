@extends('layouts.app')

@section('title', 'Panduan & Prosedur Keberangkatan — PO CAN Travel')
@section('meta_description', 'Informasi resmi prosedur hari keberangkatan bus PO CAN Travel. Waktu tiba di pool/terminal, pemeriksaan dokumen tiket digital, pemilihan kursi, dan aturan bagasi.')

@section('content')
{{-- Hero Header --}}
<div class="relative py-12 lg:py-16 bg-gradient-to-b from-orange-50/40 via-white to-slate-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-50 border border-orange-200/80 text-orange-600 text-xs font-bold uppercase tracking-wider mb-4 shadow-xs">
            <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Panduan Operasional Resmi</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black tracking-tight text-slate-900 mb-4">
            Informasi Hari Keberangkatan
        </h1>
        <p class="text-base text-slate-600 max-w-2xl leading-relaxed">
            Petunjuk faktual sebelum Anda memulai perjalanan antarkota bersama PO CAN Travel agar proses keberangkatan berjalan lancar, aman, dan tepat waktu.
        </p>
    </div>
</div>

<div class="py-12 sm:py-16 bg-[#FAFBFD]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        {{-- Section 1: Checklist Sebelum Berangkat --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
            <h2 class="text-xl font-heading font-bold text-slate-900 border-b border-slate-100 pb-3">
                Hal yang Perlu Diperiksa Sebelum Berangkat
            </h2>

            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center font-heading font-black text-sm shrink-0 border border-orange-200/80">
                        1
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-bold text-slate-900">Periksa Status Pesanan Anda</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Pastikan pesanan tiket Anda telah berstatus <strong class="text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded-md border border-emerald-200">Dikonfirmasi</strong> sebelum tiba di lokasi keberangkatan. Status pesanan dapat diperiksa melalui menu <a href="{{ route('customer.orders.index') }}" class="text-orange-600 font-bold hover:underline">Pesanan Saya</a> di akun Anda.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center font-heading font-black text-sm shrink-0 border border-orange-200/80">
                        2
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-bold text-slate-900">Waktu Kedatangan di Titik Kumpul</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Harap tiba di pool atau terminal keberangkatan minimal <strong class="text-orange-600 font-semibold">30 menit</strong> sebelum jadwal bus diberangkatkan untuk proses verifikasi manifes dan persiapan bagasi.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center font-heading font-black text-sm shrink-0 border border-orange-200/80">
                        3
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-bold text-slate-900">Kartu Identitas Penumpang</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Bawa kartu identitas resmi (KTP, SIM, atau Paspor) yang nomor dan namanya sesuai dengan data yang Anda inputkan saat melakukan pemesanan kursi.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center font-heading font-black text-sm shrink-0 border border-orange-200/80">
                        4
                    </div>
                    <div>
                        <h3 class="text-sm font-heading font-bold text-slate-900">Tunjukkan Lembar Tiket Digital</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Cukup tunjukkan kode pesanan unik (misal: <code class="px-1.5 py-0.5 rounded bg-slate-100 font-mono text-slate-800 border border-slate-200">CAN-XXXXXXXX</code>) atau lembar tiket digital dari layar ponsel Anda kepada petugas keberangkatan di lokasi. Tidak wajib dicetak fisik.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Prosedur Kabin & Kursi --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
            <h2 class="text-xl font-heading font-bold text-slate-900 border-b border-slate-100 pb-3">
                Ketentuan Tempat Duduk &amp; Fasilitas Kabin
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-600 leading-relaxed">
                <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80">
                    <h3 class="font-heading font-bold text-slate-900 mb-1 text-sm">Nomor Kursi Mengikat</h3>
                    <p>Setiap penumpang wajib menempati nomor kursi yang tertera di tiket digital resmi. Pertukaran tempat duduk hanya dapat dilakukan atas persetujuan kedua penumpang terkait dan petugas operasional.</p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80">
                    <h3 class="font-heading font-bold text-slate-900 mb-1 text-sm">Ketentuan Bagasi</h3>
                    <p>Barang berharga seperti laptop, dokumen, dan dompet wajib dibawa ke kabin atas. Koper dan tas berukuran besar diserahkan kepada petugas untuk disimpan di bagasi lambung bus.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

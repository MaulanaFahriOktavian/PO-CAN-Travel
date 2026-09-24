@extends('layouts.app')

@section('title', 'Panduan & Prosedur Keberangkatan - PO CAN Travel')
@section('meta_description', 'Informasi resmi prosedur hari keberangkatan bus PO CAN Travel. Waktu tiba di pool/terminal, pemeriksaan dokumen tiket digital, pemilihan kursi, dan aturan bagasi.')

@section('content')
{{-- Hero Header --}}
<div class="relative py-12 lg:py-16 bg-[#1C2522] text-[#F5F1E8] border-b border-[#2F6252]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-widest text-[#B96545] mb-2">PANDUAN OPERASIONAL RESMI</p>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white mb-4">
            Informasi Hari Keberangkatan
        </h1>
        <p class="text-base text-[#D9D5CA] max-w-2xl leading-relaxed">
            Petunjuk faktual sebelum Anda memulai perjalanan antarkota bersama PO CAN Travel agar proses keberangkatan berjalan lancar, aman, dan tepat waktu.
        </p>
    </div>
</div>

<div class="py-12 sm:py-16 bg-[#FBFAF6]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Section 1: Checklist Sebelum Berangkat --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA] space-y-6">
            <h2 class="text-xl font-bold text-[#1C2522] border-b border-[#D9D5CA] pb-3">
                Hal yang Perlu Diperiksa Sebelum Berangkat
            </h2>

            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-[#F5F1E8] text-[#21483C] flex items-center justify-center font-bold text-sm shrink-0 border border-[#D9D5CA]">
                        1
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#1C2522]">Periksa Status Pesanan Anda</h3>
                        <p class="text-xs text-[#66716C] mt-1 leading-relaxed">
                            Pastikan pesanan tiket Anda telah berstatus <strong class="text-[#357A62]">Dikonfirmasi</strong> sebelum tiba di lokasi keberangkatan. Status pesanan dapat diperiksa melalui menu <a href="{{ route('customer.orders.index') }}" class="text-[#21483C] font-bold hover:underline">Pesanan Saya</a> di akun Anda.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-[#F5F1E8] text-[#B96545] flex items-center justify-center font-bold text-sm shrink-0 border border-[#D9D5CA]">
                        2
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#1C2522]">Waktu Kedatangan di Titik Kumpul</h3>
                        <p class="text-xs text-[#66716C] mt-1 leading-relaxed">
                            Harap tiba di pool atau terminal keberangkatan minimal <strong class="text-[#B96545]">30 menit</strong> sebelum jadwal bus diberangkatkan untuk proses verifikasi manifes dan persiapan bagasi.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-[#F5F1E8] text-[#21483C] flex items-center justify-center font-bold text-sm shrink-0 border border-[#D9D5CA]">
                        3
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#1C2522]">Kartu Identitas Penumpang</h3>
                        <p class="text-xs text-[#66716C] mt-1 leading-relaxed">
                            Bawa kartu identitas resmi (KTP, SIM, atau Paspor) yang nomor dan namanya sesuai dengan data yang Anda inputkan saat melakukan pemesanan kursi.
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-xl bg-[#F5F1E8] text-[#21483C] flex items-center justify-center font-bold text-sm shrink-0 border border-[#D9D5CA]">
                        4
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#1C2522]">Tunjukkan Lembar Tiket Digital</h3>
                        <p class="text-xs text-[#66716C] mt-1 leading-relaxed">
                            Cukup tunjukkan kode pesanan unik (misal: <code>CAN-XXXXXXXX</code>) atau lembar tiket digital dari layar ponsel Anda kepada petugas keberangkatan di lokasi. Tidak wajib dicetak fisik.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Prosedur Kabin & Kursi --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#D9D5CA] space-y-4">
            <h2 class="text-xl font-bold text-[#1C2522] border-b border-[#D9D5CA] pb-3">
                Ketentuan Tempat Duduk &amp; Fasilitas Kabin
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-[#66716C] leading-relaxed">
                <div class="p-4 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA]">
                    <h3 class="font-bold text-[#1C2522] mb-1 text-sm">Nomor Kursi Mengikat</h3>
                    <p>Setiap penumpang wajib menempati nomor kursi yang tertera di tiket digital resmi. Pertukaran tempat duduk hanya dapat dilakukan atas persetujuan kedua penumpang terkait dan petugas.</p>
                </div>
                <div class="p-4 rounded-xl bg-[#F5F1E8] border border-[#D9D5CA]">
                    <h3 class="font-bold text-[#1C2522] mb-1 text-sm">Ketentuan Bagasi</h3>
                    <p>Barang berharga seperti laptop, dokumen, dan dompet wajib dibawa ke kabin atas. Koper dan tas berukuran besar diserahkan kepada petugas untuk disimpan di bagasi lambung bus.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

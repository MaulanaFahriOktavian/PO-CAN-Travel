@extends('layouts.app')

@section('title', 'Tambah Armada Bus - PO CAN Travel')

@section('content')
    <div class="py-10 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('admin.buses.index') }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                    &larr; Kembali ke Daftar Armada
                </a>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight mt-2">Tambah Armada Bus</h1>
                <p class="mt-1 text-sm text-slate-600">Daftarkan armada bus baru untuk operasional perjalanan.</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ route('admin.buses.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Armada</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('name') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                            placeholder="Contoh: CAN Executive 03">
                        @if ($errors->has('name'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Kode Armada</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('code') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                            placeholder="Contoh: CAN-EX03">
                        @if ($errors->has('code'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('code') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="total_seats" class="block text-sm font-medium text-slate-700 mb-1">Kapasitas
                            Kursi</label>
                        <input type="number" id="total_seats" name="total_seats" value="{{ old('total_seats', 30) }}"
                            min="1" max="100" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('total_seats') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <p class="mt-1 text-xs text-slate-500">
                            Sistem akan secara otomatis menghasilkan daftar nomor kursi (1A, 1B, 1C, 1D, dst.) sesuai jumlah
                            kapasitas ini.
                        </p>
                        @if ($errors->has('total_seats'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('total_seats') }}</p>
                        @endif
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <a href="{{ route('admin.buses.index') }}"
                            class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">
                            Simpan Armada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
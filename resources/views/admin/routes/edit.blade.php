@extends('layouts.app')

@section('title', 'Edit Rute Perjalanan - PO CAN Travel')

@section('content')
    <div class="py-10 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('admin.routes.index') }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                    &larr; Kembali ke Daftar Rute
                </a>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight mt-2">Edit Rute Perjalanan</h1>
                <p class="mt-1 text-sm text-slate-600">Perbarui informasi kota asal, tujuan, atau durasi rute.</p>
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ route('admin.routes.update', $route) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="origin" class="block text-sm font-medium text-slate-700 mb-1">Kota Asal
                            (Keberangkatan)</label>
                        <input type="text" id="origin" name="origin" value="{{ old('origin', $route->origin) }}" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('origin') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        @if ($errors->has('origin'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('origin') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="destination" class="block text-sm font-medium text-slate-700 mb-1">Kota Tujuan
                            (Kedatangan)</label>
                        <input type="text" id="destination" name="destination"
                            value="{{ old('destination', $route->destination) }}" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('destination') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        @if ($errors->has('destination'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('destination') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-slate-700 mb-1">Estimasi Durasi
                            (Menit)</label>
                        <input type="number" id="duration" name="duration" value="{{ old('duration', $route->duration) }}"
                            min="1" max="1440" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('duration') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <p class="mt-1 text-xs text-slate-500">
                            Masukkan estimasi durasi dalam menit.
                        </p>
                        @if ($errors->has('duration'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('duration') }}</p>
                        @endif
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <a href="{{ route('admin.routes.index') }}"
                            class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
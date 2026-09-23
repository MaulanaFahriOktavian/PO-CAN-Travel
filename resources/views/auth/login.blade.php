@extends('layouts.app')

@section('title', 'Masuk - PO CAN Travel')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-md mx-auto px-4 sm:px-6">
        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-sm">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Masuk ke Akun Anda</h1>
                <p class="mt-1.5 text-sm text-slate-600">Masukkan email dan password untuk melanjutkan.</p>
            </div>

            @if ($errors->has('email'))
                <div class="mb-5 p-3.5 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder="nama@email.com"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder="••••••••"
                    >
                    @if ($errors->has('password') && !$errors->has('email'))
                        <p class="mt-1 text-xs text-red-600">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="text-sm text-slate-600">Ingat saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                    >
                        Masuk
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-600">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-700">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

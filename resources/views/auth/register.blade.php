@extends('layouts.app')

@section('title', 'Daftar Akun - PO CAN Travel')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-md mx-auto px-4 sm:px-6">
        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-sm">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Akun Baru</h1>
                <p class="mt-1.5 text-sm text-slate-600">Lengkapi data berikut untuk membuat akun pelanggan PO CAN Travel.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('name') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder="Nama lengkap Anda"
                    >
                    @if ($errors->has('name'))
                        <p class="mt-1 text-xs text-red-600">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('email') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder="nama@email.com"
                    >
                    @if ($errors->has('email'))
                        <p class="mt-1 text-xs text-red-600">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('password') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder="Minimal 8 karakter"
                    >
                    @if ($errors->has('password'))
                        <p class="mt-1 text-xs text-red-600">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder="Ketik ulang password"
                    >
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                    >
                        Daftar Akun
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-600">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

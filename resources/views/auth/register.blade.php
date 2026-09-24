@extends('layouts.app')

@section('title', 'Daftar Akun - PO CAN Travel')

@section('content')
<div class="py-12 sm:py-16 bg-[#FBFAF6] min-h-[75vh] flex items-center">
    <div class="max-w-md mx-auto px-4 sm:px-6 w-full">
        <div class="bg-white border border-[#D9D5CA] rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="mb-6">
                <span class="text-xs uppercase tracking-wider font-bold block mb-1 text-[#21483C]">Registrasi Akun</span>
                <h1 class="text-2xl font-bold text-[#1C2522] tracking-tight">Daftar Akun Baru</h1>
                <p class="mt-1.5 text-sm text-[#66716C]">Lengkapi data berikut untuk membuat akun pelanggan PO CAN Travel.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-[#1C2522] mb-1">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-[#B94A48]' : 'border-[#D9D5CA]' }} bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#21483C] focus:border-[#21483C]"
                        placeholder="Nama lengkap Anda"
                    >
                    @if ($errors->has('name'))
                        <p class="mt-1 text-xs text-[#B94A48]">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-[#1C2522] mb-1">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-[#B94A48]' : 'border-[#D9D5CA]' }} bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#21483C] focus:border-[#21483C]"
                        placeholder="nama@email.com"
                    >
                    @if ($errors->has('email'))
                        <p class="mt-1 text-xs text-[#B94A48]">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-[#1C2522] mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-[#B94A48]' : 'border-[#D9D5CA]' }} bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#21483C] focus:border-[#21483C]"
                        placeholder="Minimal 8 karakter"
                    >
                    @if ($errors->has('password'))
                        <p class="mt-1 text-xs text-[#B94A48]">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-[#1C2522] mb-1">Konfirmasi Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#21483C] focus:border-[#21483C]"
                        placeholder="Ketik ulang password"
                    >
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-[#21483C] hover:bg-[#2F6252] text-white font-bold text-sm rounded-xl shadow-xs transition-colors cursor-pointer"
                    >
                        Daftar Akun
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-[#D9D5CA] text-center">
                <p class="text-xs text-[#66716C]">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-[#21483C] hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

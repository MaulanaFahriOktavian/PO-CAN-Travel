@extends('layouts.app')

@section('title', 'Masuk - PO CAN Travel')

@section('content')
<div class="py-12 sm:py-16 bg-[#FBFAF6] min-h-[75vh] flex items-center">
    <div class="max-w-md mx-auto px-4 sm:px-6 w-full">
        <div class="bg-white border border-[#D9D5CA] rounded-2xl p-6 sm:p-8 shadow-xs">
            <div class="mb-6">
                <span class="text-xs uppercase tracking-wider font-bold block mb-1 text-[#21483C]">Akses Penumpang</span>
                <h1 class="text-2xl font-bold text-[#1C2522] tracking-tight">Masuk ke Akun Anda</h1>
                <p class="mt-1.5 text-sm text-[#66716C]">Masukkan email dan password untuk melanjutkan.</p>
            </div>

            @if ($errors->has('email'))
                <div class="mb-5 p-3.5 rounded-xl bg-[#F5F1E8] border border-[#B94A48]/30 text-sm text-[#B94A48]">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-[#1C2522] mb-1">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#21483C] focus:border-[#21483C]"
                        placeholder="nama@email.com"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-[#1C2522] mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D9D5CA] bg-[#FBFAF6] text-[#1C2522] text-sm focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#21483C] focus:border-[#21483C]"
                        placeholder="••••••••"
                    >
                    @if ($errors->has('password') && !$errors->has('email'))
                        <p class="mt-1 text-xs text-[#B94A48]">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded text-[#21483C] focus:ring-[#21483C] accent-[#21483C]"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="text-xs text-[#66716C]">Ingat saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-[#21483C] hover:bg-[#2F6252] text-white font-bold text-sm rounded-xl shadow-xs transition-colors cursor-pointer"
                    >
                        Masuk
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-[#D9D5CA] text-center">
                <p class="text-xs text-[#66716C]">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-bold text-[#21483C] hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

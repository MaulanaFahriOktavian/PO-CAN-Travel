@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - Admin PO CAN Travel')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="py-8 lg:py-10" style="background-color: #F8FAFC;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header & Stats --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-orange-700 mb-1">KONSOL OPERASIONAL</p>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900">Manajemen Pengguna</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar seluruh akun pelanggan dan administrator yang terdaftar di sistem.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Total Pengguna</span>
                    <strong class="text-slate-900 font-bold text-sm">{{ $stats['total'] }}</strong>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-orange-50 border border-orange-100 text-xs text-orange-800">
                    <span class="text-orange-500 block text-[10px] uppercase font-bold">Pelanggan</span>
                    <strong class="font-bold text-sm">{{ $stats['customers'] }}</strong>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-slate-900 text-white text-xs">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Administrator</span>
                    <strong class="font-bold text-sm">{{ $stats['admins'] }}</strong>
                </div>
            </div>
        </div>

        {{-- Filters & Search --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="flex-1 w-full relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama atau email pengguna..."
                        class="w-full pl-9 pr-4 py-2 bg-slate-50 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <div class="w-full sm:w-auto">
                    <select
                        name="role"
                        class="w-full sm:w-auto px-3 py-2 bg-slate-50 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-orange-600"
                    >
                        <option value="">Semua Peran</option>
                        <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Pelanggan</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-white rounded-xl shadow-xs transition-all"
                    style="background-color: #1D4ED8;"
                >
                    Filter
                </button>

                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-3 py-2 text-xs font-semibold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 uppercase tracking-wider text-slate-400 font-bold border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-4">Nama Lengkap</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">Peran (Role)</th>
                            <th class="py-3.5 px-4">Total Pesanan</th>
                            <th class="py-3.5 px-4">Terdaftar Sejak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $u->name }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-mono">
                                    {{ $u->email }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($u->role === 'admin')
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-900 text-white uppercase tracking-wider">
                                            Admin
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-orange-50 text-orange-700 uppercase tracking-wider border border-orange-100">
                                            Pelanggan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900">{{ $u->orders_count }}</span>
                                    <span class="text-slate-400 text-[11px]">pesanan</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">
                                    {{ $u->created_at->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500">
                                    Tidak ada data pengguna yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</div>
@endsection

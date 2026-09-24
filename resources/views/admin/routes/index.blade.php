@extends('layouts.app')

@section('title', 'Kelola Rute Perjalanan - PO CAN Travel')

@section('content')
    <div class="py-10 sm:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-8 border-b border-slate-200">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Rute Perjalanan</h1>
                    <p class="mt-1 text-sm text-slate-600">Kelola daftar kota asal, kota tujuan, dan estimasi waktu tempuh
                        antarkota.</p>
                </div>
                <div>
                    <a href="{{ route('admin.routes.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">
                        Tambah Rute
                    </a>
                </div>
            </div>

            <!-- Flash Feedback -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Table Container -->
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                @if ($routes->isEmpty())
                    <div class="p-8 text-center text-sm text-slate-500">
                        Belum ada rute perjalanan yang terdaftar.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr
                                    class="border-b border-slate-200 bg-slate-50/75 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th scope="col" class="py-3.5 px-4 sm:px-6">Kota Asal</th>
                                    <th scope="col" class="py-3.5 px-4">Kota Tujuan</th>
                                    <th scope="col" class="py-3.5 px-4">Estimasi Durasi</th>
                                    <th scope="col" class="py-3.5 px-4">Jadwal Perjalanan</th>
                                    <th scope="col" class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($routes as $route)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-4 sm:px-6 font-medium text-slate-900">
                                            {{ $route->origin }}
                                        </td>
                                        <td class="py-4 px-4 font-medium text-slate-900">
                                            {{ $route->destination }}
                                        </td>
                                        <td class="py-4 px-4 text-slate-700">
                                            {{ $route->duration }} menit
                                            <span class="text-xs text-slate-500">({{ round($route->duration / 60, 1) }} jam)</span>
                                        </td>
                                        <td class="py-4 px-4 text-slate-600">
                                            {{ $route->trips_count }} Trip
                                        </td>
                                        <td class="py-4 px-4 sm:px-6 text-right space-x-2">
                                            <a href="{{ route('admin.routes.edit', $route) }}"
                                                class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.routes.destroy', $route) }}" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus rute ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 font-medium transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
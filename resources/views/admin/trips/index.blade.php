@extends('layouts.app')

@section('title', 'Kelola Jadwal Perjalanan - PO CAN Travel')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-8 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Jadwal Perjalanan</h1>
                <p class="mt-1 text-sm text-slate-600">Kelola jadwal trip armada bus, penetapan tarif tiket, dan status perjalanan.</p>
            </div>
            <div>
                <a
                    href="{{ route('admin.trips.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors"
                >
                    Tambah Perjalanan
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
            @if ($trips->isEmpty())
                <div class="p-8 text-center text-sm text-slate-500">
                    Belum ada jadwal perjalanan yang terdaftar.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                <th scope="col" class="py-3.5 px-4 sm:px-6">Rute</th>
                                <th scope="col" class="py-3.5 px-4">Armada Bus</th>
                                <th scope="col" class="py-3.5 px-4">Keberangkatan</th>
                                <th scope="col" class="py-3.5 px-4">Kedatangan</th>
                                <th scope="col" class="py-3.5 px-4">Tarif</th>
                                <th scope="col" class="py-3.5 px-4">Status</th>
                                <th scope="col" class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($trips as $trip)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-4 sm:px-6 font-medium text-slate-900">
                                        {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                                    </td>
                                    <td class="py-4 px-4 text-slate-700">
                                        <div>{{ $trip->bus->name }}</div>
                                        <div class="text-xs font-mono text-slate-500">{{ $trip->bus->code }}</div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-700">
                                        <div>{{ $trip->departure_at->translatedFormat('d M Y') }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $trip->departure_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-700">
                                        <div>{{ $trip->arrival_at->translatedFormat('d M Y') }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $trip->arrival_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="py-4 px-4 font-medium text-slate-900">
                                        Rp {{ number_format($trip->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4">
                                        @if ($trip->status === 'scheduled')
                                            <span class="text-blue-700 font-medium">Scheduled</span>
                                        @elseif ($trip->status === 'departed')
                                            <span class="text-amber-700 font-medium">Departed</span>
                                        @elseif ($trip->status === 'completed')
                                            <span class="text-emerald-700 font-medium">Completed</span>
                                        @elseif ($trip->status === 'cancelled')
                                            <span class="text-red-700 font-medium">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 sm:px-6 text-right space-x-2">
                                        <a
                                            href="{{ route('admin.trips.edit', $trip) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium transition-colors"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.trips.destroy', $trip) }}"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal perjalanan ini?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-red-600 hover:text-red-800 font-medium transition-colors"
                                            >
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

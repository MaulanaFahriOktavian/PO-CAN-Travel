@extends('layouts.app')

@section('title', 'Edit Jadwal Perjalanan - PO CAN Travel')

@section('content')
    <div class="py-10 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('admin.trips.index') }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                    &larr; Kembali ke Daftar Jadwal
                </a>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight mt-2">Edit Jadwal Perjalanan</h1>
                <p class="mt-1 text-sm text-slate-600">Perbarui rute, armada bus, waktu keberangkatan, atau status
                    perjalanan.</p>
            </div>

            @if (session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-sm">
                <form method="POST" action="{{ route('admin.trips.update', $trip) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="route_id" class="block text-sm font-medium text-slate-700 mb-1">Rute Perjalanan</label>
                        <select id="route_id" name="route_id" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('route_id') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            @foreach ($routes as $route)
                                <option value="{{ $route->id }}" {{ old('route_id', $trip->route_id) == $route->id ? 'selected' : '' }}>
                                    {{ $route->origin }} &rarr; {{ $route->destination }} ({{ $route->duration }} menit)
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('route_id'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('route_id') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="bus_id" class="block text-sm font-medium text-slate-700 mb-1">Armada Bus</label>
                        <select id="bus_id" name="bus_id" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('bus_id') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            @foreach ($buses as $bus)
                                <option value="{{ $bus->id }}" {{ old('bus_id', $trip->bus_id) == $bus->id ? 'selected' : '' }}>
                                    {{ $bus->name }} ({{ $bus->code }} - {{ $bus->total_seats }} Kursi)
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('bus_id'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('bus_id') }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="departure_at" class="block text-sm font-medium text-slate-700 mb-1">Waktu
                                Keberangkatan</label>
                            <input type="datetime-local" id="departure_at" name="departure_at"
                                value="{{ old('departure_at', $trip->departure_at ? $trip->departure_at->format('Y-m-d\TH:i') : '') }}"
                                required
                                class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('departure_at') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            @if ($errors->has('departure_at'))
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('departure_at') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="arrival_at" class="block text-sm font-medium text-slate-700 mb-1">Waktu
                                Kedatangan</label>
                            <input type="datetime-local" id="arrival_at" name="arrival_at"
                                value="{{ old('arrival_at', $trip->arrival_at ? $trip->arrival_at->format('Y-m-d\TH:i') : '') }}"
                                required
                                class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('arrival_at') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            @if ($errors->has('arrival_at'))
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('arrival_at') }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Tarif Tiket
                            (Rupiah)</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $trip->price) }}" min="0"
                            step="1000" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('price') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        <p class="mt-1 text-xs text-slate-500">Masukkan harga dalam rupiah.</p>
                        @if ($errors->has('price'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('price') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status Perjalanan</label>
                        <select id="status" name="status" required
                            class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('status') ? 'border-red-300' : 'border-slate-300' }} text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            <option value="scheduled" {{ old('status', $trip->status) === 'scheduled' ? 'selected' : '' }}>
                                Scheduled</option>
                            <option value="departed" {{ old('status', $trip->status) === 'departed' ? 'selected' : '' }}>
                                Departed</option>
                            <option value="completed" {{ old('status', $trip->status) === 'completed' ? 'selected' : '' }}>
                                Completed</option>
                            <option value="cancelled" {{ old('status', $trip->status) === 'cancelled' ? 'selected' : '' }}>
                                Cancelled</option>
                        </select>
                        @if ($errors->has('status'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('status') }}</p>
                        @endif
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <a href="{{ route('admin.trips.index') }}"
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
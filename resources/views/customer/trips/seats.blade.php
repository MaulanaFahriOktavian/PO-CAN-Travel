@extends('layouts.app')

@section('title', 'Pilih Kursi - ' . $trip->route->origin . ' ke ' . $trip->route->destination . ' - PO CAN Travel')

@section('content')
<div
    class="py-10 sm:py-12"
    x-data="{
        selectedSeats: {{ json_encode(old('seat_ids', session('selected_seats', []))) }},
        selectedSeatNumbers: [],
        pricePerSeat: {{ $trip->price }},
        seatMap: {
            @foreach($seats as $seat)
                {{ $seat->id }}: '{{ $seat->seat_number }}',
            @endforeach
        },
        init() {
            this.updateNumbers();
        },
        toggleSeat(seatId) {
            const idx = this.selectedSeats.indexOf(seatId);
            if (idx > -1) {
                this.selectedSeats.splice(idx, 1);
            } else {
                this.selectedSeats.push(seatId);
            }
            this.updateNumbers();
        },
        isSelected(seatId) {
            return this.selectedSeats.includes(seatId);
        },
        updateNumbers() {
            this.selectedSeatNumbers = this.selectedSeats
                .map(id => this.seatMap[id] || id)
                .sort((a, b) => a.localeCompare(b, undefined, { numeric: true }));
        },
        get selectedCount() {
            return this.selectedSeats.length;
        },
        get totalPrice() {
            return this.selectedCount * this.pricePerSeat;
        },
        formatRupiah(amount) {
            return 'Rp' + amount.toLocaleString('id-ID');
        }
    }"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation -->
        <div class="mb-6">
            <a
                href="{{ route('customer.trips.show', $trip) }}"
                class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
            >
                <span class="mr-1.5">&larr;</span> Kembali ke Detail Perjalanan
            </a>
        </div>

        <!-- Page Header -->
        <div class="border-b border-slate-200 pb-6 mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                Pilih Kursi Perjalanan
            </h1>
            <p class="mt-1.5 text-sm text-slate-600">
                Silakan pilih satu atau beberapa nomor kursi yang tersedia pada denah bus di bawah ini.
            </p>
        </div>

        <!-- Flash Messages & Validation Errors -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-sm text-rose-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Seat Map -->
            <div class="lg:col-span-7 bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Tata Letak Kursi Bus</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $trip->bus->name }} ({{ $trip->bus->total_seats }} Kursi)</p>
                    </div>

                    <!-- Legend -->
                    <div class="flex items-center gap-4 text-xs text-slate-600">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3.5 h-3.5 rounded border border-slate-300 bg-white inline-block"></span>
                            <span>Tersedia</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3.5 h-3.5 rounded bg-blue-600 border border-blue-600 inline-block"></span>
                            <span>Dipilih</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3.5 h-3.5 rounded bg-slate-100 border border-slate-200 inline-block"></span>
                            <span>Sudah dipesan</span>
                        </div>
                    </div>
                </div>

                <!-- Bus Cabin Container (Responsive scrollable if needed) -->
                <div class="overflow-x-auto pb-2">
                    <div class="min-w-[280px]">
                        <!-- Bus Cabin Front Indicator -->
                        <div class="mb-6 pb-2 text-center border-b border-dashed border-slate-200">
                            <span class="text-xs uppercase tracking-wider font-semibold text-slate-400">Bagian Depan / Sopir</span>
                        </div>

                        <!-- Seat Grid (2-2 Configuration) -->
                        <div class="space-y-3">
                            @foreach ($rows as $rowNum => $rowSeats)
                                <div class="flex items-center justify-center gap-6 sm:gap-8">
                                    <!-- Left Side: A & B -->
                                    <div class="flex items-center gap-2">
                                        @foreach (['A', 'B'] as $letter)
                                            @if (isset($rowSeats[$letter]))
                                                @php
                                                    $seat = $rowSeats[$letter];
                                                    $isBooked = in_array($seat->id, $bookedSeatIds);
                                                @endphp

                                                @if ($isBooked)
                                                    <!-- Booked Seat -->
                                                    <button
                                                        type="button"
                                                        disabled
                                                        class="w-12 h-12 rounded-lg border border-slate-200 bg-slate-100 text-slate-400 font-mono text-xs font-medium cursor-not-allowed flex items-center justify-center select-none"
                                                        title="Kursi {{ $seat->seat_number }} sudah dipesan"
                                                    >
                                                        {{ $seat->seat_number }}
                                                    </button>
                                                @else
                                                    <!-- Available / Selectable Seat -->
                                                    <button
                                                        type="button"
                                                        @click="toggleSeat({{ $seat->id }})"
                                                        :class="isSelected({{ $seat->id }})
                                                            ? 'bg-blue-600 text-white border-blue-600 font-semibold shadow-sm'
                                                            : 'bg-white text-slate-800 border-slate-300 hover:border-blue-500 hover:bg-blue-50/50'"
                                                        class="w-12 h-12 rounded-lg border font-mono text-xs transition-colors flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-blue-600"
                                                    >
                                                        {{ $seat->seat_number }}
                                                    </button>
                                                @endif
                                            @else
                                                <!-- Spacer if seat not present -->
                                                <div class="w-12 h-12"></div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- Corridor / Gang -->
                                    <div class="w-5 text-center text-xs font-mono text-slate-300 select-none">
                                        {{ $rowNum }}
                                    </div>

                                    <!-- Right Side: C & D -->
                                    <div class="flex items-center gap-2">
                                        @foreach (['C', 'D'] as $letter)
                                            @if (isset($rowSeats[$letter]))
                                                @php
                                                    $seat = $rowSeats[$letter];
                                                    $isBooked = in_array($seat->id, $bookedSeatIds);
                                                @endphp

                                                @if ($isBooked)
                                                    <!-- Booked Seat -->
                                                    <button
                                                        type="button"
                                                        disabled
                                                        class="w-12 h-12 rounded-lg border border-slate-200 bg-slate-100 text-slate-400 font-mono text-xs font-medium cursor-not-allowed flex items-center justify-center select-none"
                                                        title="Kursi {{ $seat->seat_number }} sudah dipesan"
                                                    >
                                                        {{ $seat->seat_number }}
                                                    </button>
                                                @else
                                                    <!-- Available / Selectable Seat -->
                                                    <button
                                                        type="button"
                                                        @click="toggleSeat({{ $seat->id }})"
                                                        :class="isSelected({{ $seat->id }})
                                                            ? 'bg-blue-600 text-white border-blue-600 font-semibold shadow-sm'
                                                            : 'bg-white text-slate-800 border-slate-300 hover:border-blue-500 hover:bg-blue-50/50'"
                                                        class="w-12 h-12 rounded-lg border font-mono text-xs transition-colors flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-blue-600"
                                                    >
                                                        {{ $seat->seat_number }}
                                                    </button>
                                                @endif
                                            @else
                                                <!-- Spacer if seat not present -->
                                                <div class="w-12 h-12"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Submission -->
            <div class="lg:col-span-5 bg-white border border-slate-200 rounded-xl p-6 sm:p-8 space-y-6">
                <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100">
                    Ringkasan Pilihan
                </h2>

                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-slate-500">Rute Perjalanan</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">
                            {{ $trip->route->origin }} &rarr; {{ $trip->route->destination }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Waktu Keberangkatan</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">
                            {{ $trip->departure_at->translatedFormat('d M Y') }}, {{ $trip->departure_at->format('H.i') }} WIB
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Tarif per Kursi</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">
                            Rp{{ number_format($trip->price, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <dt class="text-slate-500">Kursi Dipilih</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">
                            <span x-text="selectedSeatNumbers.join(', ') || '-'">-</span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Jumlah Kursi</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">
                            <span x-text="selectedCount">0</span> kursi
                        </dd>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <dt class="text-slate-500">Estimasi Total</dt>
                        <dd class="mt-1 text-xl font-bold text-slate-900">
                            <span x-text="formatRupiah(totalPrice)">Rp0</span>
                        </dd>
                    </div>
                </dl>

                <!-- Submission Form -->
                <form method="POST" action="{{ route('customer.trips.seats.store', $trip) }}" class="pt-2">
                    @csrf

                    <!-- Dynamic Hidden Inputs for Selected Seats -->
                    <template x-for="seatId in selectedSeats" :key="seatId">
                        <input type="hidden" name="seat_ids[]" :value="seatId">
                    </template>

                    <div class="space-y-3">
                        <button
                            type="submit"
                            :disabled="selectedCount === 0"
                            :class="selectedCount === 0
                                ? 'bg-slate-200 text-slate-400 cursor-not-allowed'
                                : 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm'"
                            class="w-full py-2.5 px-4 rounded-lg font-medium text-sm transition-colors text-center focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                            Lanjutkan
                        </button>

                        <p class="text-xs text-slate-500 text-center">
                            Pilihan kursi akan dikonfirmasi saat pemesanan.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

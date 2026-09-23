<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreSeatSelectionRequest;
use App\Models\OrderItem;
use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SeatSelectionController extends Controller
{
    /**
     * Menampilkan denah pemilihan kursi untuk trip pelanggan.
     */
    public function show(Trip $trip): View
    {
        // Hanya trip berstatus scheduled yang dapat diakses
        if ($trip->status !== 'scheduled') {
            abort(404);
        }

        // Eager load relasi bus beserta kursi dan rute
        $trip->load(['bus.seats', 'route']);

        // Mengambil daftar seat_id yang sudah berstatus booked pada trip ini
        // Status order yang terhitung booked: pending, confirmed, completed
        $bookedSeatIds = OrderItem::whereHas('order', function ($query) use ($trip) {
            $query->where('trip_id', $trip->id)
                  ->whereIn('status', ['pending', 'confirmed', 'completed']);
        })->pluck('seat_id')->all();

        // Ambil seluruh kursi milik bus trip ini
        $seats = $trip->bus->seats()->orderBy('id')->get();

        // Susun tata letak kursi per baris dan posisi huruf (A, B, C, D)
        $rows = [];
        foreach ($seats as $seat) {
            if (preg_match('/^(\d+)([A-D])$/', $seat->seat_number, $matches)) {
                $rowNum = (int) $matches[1];
                $letter = $matches[2];
                $rows[$rowNum][$letter] = $seat;
            }
        }
        ksort($rows);

        return view('customer.trips.seats', compact('trip', 'seats', 'rows', 'bookedSeatIds'));
    }

    /**
     * Memvalidasi pilihan kursi yang dikirimkan customer.
     * Tidak menyimpan Order atau OrderItem baru pada Phase 5.
     */
    public function store(Trip $trip, StoreSeatSelectionRequest $request): RedirectResponse
    {
        // Memastikan kembali trip masih berstatus scheduled
        if ($trip->status !== 'scheduled') {
            abort(404);
        }

        $seatIds = $request->validated('seat_ids');
        $selectedCount = count($seatIds);
        $totalPrice = $selectedCount * $trip->price;

        return redirect()
            ->route('customer.trips.seats', $trip)
            ->with('selected_seats', $seatIds)
            ->with('success', 'Pilihan kursi berhasil divalidasi. Kursi akan dikonfirmasi saat pemesanan.');
    }
}

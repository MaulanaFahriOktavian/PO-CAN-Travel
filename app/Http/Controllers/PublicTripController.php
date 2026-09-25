<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Route;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicTripController extends Controller
{
    /**
     * Menampilkan katalog pencarian jadwal perjalanan publik.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'origin' => ['nullable', 'string', 'max:100'],
            'destination' => ['nullable', 'string', 'max:100', 'different:origin'],
            'departure_date' => ['nullable', 'date'],
            'date' => ['nullable', 'date'],
            'sort' => ['nullable', 'string', 'in:departure_asc,departure_desc,price_asc,price_desc'],
        ], [
            'destination.different' => 'Kota tujuan tidak boleh sama dengan kota asal.',
            'departure_date.date' => 'Format tanggal keberangkatan tidak valid.',
            'date.date' => 'Format tanggal keberangkatan tidak valid.',
        ]);

        $origins = Route::select('origin')->distinct()->orderBy('origin')->pluck('origin');
        $destinations = Route::select('destination')->distinct()->orderBy('destination')->pluck('destination');

        $bookedSeatsCountSubquery = OrderItem::selectRaw('count(*)')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereColumn('orders.trip_id', 'trips.id')
            ->whereIn('orders.status', ['pending', 'confirmed', 'completed']);

        $query = Trip::with(['bus.facilities', 'route'])
            ->select('trips.*')
            ->selectSub($bookedSeatsCountSubquery, 'booked_seats_count')
            ->where('status', 'scheduled')
            ->where('departure_at', '>=', now());

        if ($request->filled('origin')) {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('origin', $request->origin);
            });
        }

        if ($request->filled('destination')) {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('destination', $request->destination);
            });
        }

        $departureDate = $request->input('departure_date', $request->input('date'));
        if (!empty($departureDate)) {
            $query->whereDate('departure_at', $departureDate);
        }

        // Sorting
        $sort = $request->query('sort', 'departure_asc');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'departure_desc':
                $query->orderBy('departure_at', 'desc');
                break;
            case 'departure_asc':
            default:
                $query->orderBy('departure_at', 'asc');
                break;
        }

        $trips = $query->paginate(10)->withQueryString();

        return view('trips.index', compact('trips', 'origins', 'destinations'));
    }

    /**
     * Menampilkan detail perjalanan antarkota publik (timeline, armada, fasilitas, harga).
     */
    public function show(Trip $trip): View
    {
        if ($trip->status !== 'scheduled' || $trip->departure_at < now()) {
            abort(404, 'Perjalanan tidak tersedia atau sudah tidak aktif.');
        }

        $trip->load(['bus.facilities', 'bus.images', 'bus.seats', 'route']);

        // Hitung kursi terpesan
        $bookedSeatIds = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.trip_id', $trip->id)
            ->whereIn('orders.status', ['pending', 'confirmed', 'completed'])
            ->pluck('order_items.seat_id')
            ->toArray();

        $totalSeats = $trip->bus->total_seats;
        $bookedCount = count($bookedSeatIds);
        $availableCount = max(0, $totalSeats - $bookedCount);

        return view('trips.show', compact('trip', 'bookedSeatIds', 'totalSeats', 'bookedCount', 'availableCount'));
    }
}

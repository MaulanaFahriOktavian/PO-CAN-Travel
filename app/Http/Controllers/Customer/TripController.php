<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\SearchTripRequest;
use App\Models\OrderItem;
use App\Models\Route;
use App\Models\Trip;
use Illuminate\Contracts\View\View;

class TripController extends Controller
{
    /**
     * Menampilkan formulir pencarian dan daftar perjalanan yang tersedia untuk customer.
     */
    public function index(SearchTripRequest $request): View
    {
        // Opsi origin dan destination dari rute yang terdaftar
        $origins = Route::select('origin')->distinct()->orderBy('origin')->pluck('origin');
        $destinations = Route::select('destination')->distinct()->orderBy('destination')->pluck('destination');

        // Subquery untuk menghitung kursi terpesan pada order aktif (pending, confirmed, completed)
        $bookedSeatsCountSubquery = OrderItem::selectRaw('count(*)')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereColumn('orders.trip_id', 'trips.id')
            ->whereIn('orders.status', ['pending', 'confirmed', 'completed']);

        // Query perjalanan dengan eager loading dan subquery kursi terpesan
        $tripsQuery = Trip::with(['bus', 'route'])
            ->select('trips.*')
            ->selectSub($bookedSeatsCountSubquery, 'booked_seats_count')
            ->where('status', 'scheduled')
            ->when($request->filled('origin'), function ($query) use ($request) {
                $query->whereHas('route', function ($q) use ($request) {
                    $q->where('origin', $request->origin);
                });
            })
            ->when($request->filled('destination'), function ($query) use ($request) {
                $query->whereHas('route', function ($q) use ($request) {
                    $q->where('destination', $request->destination);
                });
            })
            ->when($request->filled('departure_date'), function ($query) use ($request) {
                $query->whereDate('departure_at', $request->departure_date);
            });

        // Pengurutan dinamis
        $sort = $request->query('sort', 'departure_asc');
        match ($sort) {
            'departure_desc' => $tripsQuery->orderBy('departure_at', 'desc'),
            'price_asc' => $tripsQuery->orderBy('price', 'asc'),
            'price_desc' => $tripsQuery->orderBy('price', 'desc'),
            default => $tripsQuery->orderBy('departure_at', 'asc'),
        };

        $trips = $tripsQuery->get();

        return view('customer.trips.index', compact('trips', 'origins', 'destinations'));
    }

    /**
     * Menampilkan detail perjalanan untuk customer.
     */
    public function show(Trip $trip): View
    {
        // Customer hanya dapat melihat perjalanan yang masih berstatus scheduled
        if ($trip->status !== 'scheduled') {
            abort(404);
        }

        $trip->load(['bus', 'route']);

        $bookedSeatsCount = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.trip_id', $trip->id)
            ->whereIn('orders.status', ['pending', 'confirmed', 'completed'])
            ->count();

        $availableSeatsCount = max(0, $trip->bus->total_seats - $bookedSeatsCount);

        return view('customer.trips.show', compact('trip', 'availableSeatsCount'));
    }
}

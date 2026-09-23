<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\SearchTripRequest;
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

        // Query perjalanan dengan eager loading dan filter dinamis
        $trips = Trip::with(['bus', 'route'])
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
            })
            ->orderBy('departure_at', 'asc')
            ->get();

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

        return view('customer.trips.show', compact('trip'));
    }
}

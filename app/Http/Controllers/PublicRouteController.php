<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\View\View;

class PublicRouteController extends Controller
{
    /**
     * Menampilkan direktori rute antarkota publik.
     */
    public function index(): View
    {
        $allRoutes = Route::with(['trips' => function ($q) {
            $q->where('status', 'scheduled')
              ->where('departure_at', '>=', now())
              ->orderBy('departure_at');
        }])->orderBy('origin')->orderBy('destination')->get();

        $allRoutes->each(function ($route) {
            $active = $route->trips;
            $route->scheduled_trips_count = $active->count();
            $route->min_price = $active->min('price');
        });

        $routesByOrigin = $allRoutes->groupBy('origin');

        return view('routes.index', compact('routesByOrigin'));
    }

    /**
     * Menampilkan detail spesifik rute, diagram perjalanan, armada yang bertugas, dan jadwal aktif.
     */
    public function show(Route $route): View
    {
        $route->load(['trips' => function ($q) {
            $q->with('bus.facilities')
              ->where('status', 'scheduled')
              ->where('departure_at', '>=', now())
              ->orderBy('departure_at', 'asc');
        }]);

        // Armada yang melayani rute ini
        $buses = $route->trips->pluck('bus')->unique('id');

        return view('routes.show', compact('route', 'buses'));
    }
}

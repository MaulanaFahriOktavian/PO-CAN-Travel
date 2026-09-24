<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FleetController extends Controller
{
    /**
     * Menampilkan daftar armada bus resmi PO CAN Travel.
     */
    public function index(): View
    {
        $buses = Bus::with(['facilities', 'images', 'trips.route'])
            ->withCount(['seats', 'trips' => function ($q) {
                $q->where('status', 'scheduled')
                  ->where('departure_at', '>=', now());
            }])
            ->orderBy('code', 'asc')
            ->get();

        return view('buses.index', compact('buses'));
    }

    /**
     * Menampilkan detail armada bus, spesifikasi, denah kursi, dan fasilitas.
     */
    public function show(Bus $bus): View
    {
        $bus->load(['facilities', 'images', 'seats', 'trips' => function ($q) {
            $q->with('route')
              ->where('status', 'scheduled')
              ->where('departure_at', '>=', now())
              ->orderBy('departure_at', 'asc')
              ->take(5);
        }]);

        // Urutkan kursi berdasarkan baris dan nomor
        $seats = $bus->seats->sortBy(function ($seat) {
            preg_match('/(\d+)([A-Z])/', $seat->seat_number, $matches);
            $row = isset($matches[1]) ? (int)$matches[1] : 0;
            $col = isset($matches[2]) ? $matches[2] : '';
            return sprintf('%03d%s', $row, $col);
        });

        return view('buses.show', compact('bus', 'seats'));
    }
}

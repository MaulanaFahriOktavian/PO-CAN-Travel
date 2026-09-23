<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Trip;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dasbor administrator operasional.
     */
    public function index(): View
    {
        // 1. Pesanan membutuhkan perhatian (status: pending)
        $pendingOrders = Order::with(['user', 'trip.route', 'trip.bus'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 2. Perjalanan hari ini
        $todayTrips = Trip::with(['bus', 'route'])
            ->whereDate('departure_at', today())
            ->orderBy('departure_at', 'asc')
            ->get();

        // 3. Perjalanan mendatang
        $upcomingTrips = Trip::with(['bus', 'route'])
            ->where('status', 'scheduled')
            ->where('departure_at', '>', now())
            ->orderBy('departure_at', 'asc')
            ->take(5)
            ->get();

        // 4. Pesanan terbaru
        $recentOrders = Order::with(['user', 'trip.route'])
            ->withCount('orderItems')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'pendingOrders',
            'todayTrips',
            'upcomingTrips',
            'recentOrders'
        ));
    }
}

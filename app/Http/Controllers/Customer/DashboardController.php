<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dasbor pelanggan.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $activeOrders = Order::with(['trip.route', 'trip.bus', 'orderItems'])
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('created_at', 'desc')
            ->get();

        $recentOrders = Order::with(['trip.route', 'trip.bus'])
            ->withCount('orderItems')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('activeOrders', 'recentOrders'));
    }
}

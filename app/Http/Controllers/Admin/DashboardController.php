<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Order;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dasbor administrator operasional.
     */
    public function index(): View
    {
        // ── KPI Cards ──────────────────────────────────────────────
        $pendingOrders = Order::with(['user', 'trip.route', 'trip.bus'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $todayTrips = Trip::with(['bus', 'route'])
            ->whereDate('departure_at', today())
            ->orderBy('departure_at', 'asc')
            ->get();

        $upcomingTrips = Trip::with(['bus', 'route'])
            ->where('status', 'scheduled')
            ->where('departure_at', '>', now())
            ->orderBy('departure_at', 'asc')
            ->take(6)
            ->get();

        $recentOrders = Order::with(['user', 'trip.route'])
            ->withCount('orderItems')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // ── Aggregate Stats (SQLite-safe: no MONTH/YEAR functions) ─
        $totalRevenue = Order::whereIn('status', ['confirmed', 'completed'])->sum('total_amount');

        $revenueThisMonth = Order::whereIn('status', ['confirmed', 'completed'])
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<=', now()->endOfMonth())
            ->sum('total_amount');

        $revenueLastMonth = Order::whereIn('status', ['confirmed', 'completed'])
            ->where('created_at', '>=', now()->subMonthNoOverflow()->startOfMonth())
            ->where('created_at', '<=', now()->subMonthNoOverflow()->endOfMonth())
            ->sum('total_amount');

        $totalOrders     = Order::count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $totalBuses        = Bus::count();
        $totalRoutes       = Route::count();
        $totalCustomers    = User::where('role', 'customer')->count();
        $newCustomersMonth = User::where('role', 'customer')
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<=', now()->endOfMonth())
            ->count();

        // ── Chart: Monthly Orders — PHP-side aggregation (SQLite-safe) ──
        $chartStart = now()->subMonths(11)->startOfMonth();
        $allChartOrders = Order::select('created_at', 'total_amount')
            ->where('created_at', '>=', $chartStart)
            ->whereIn('status', ['confirmed', 'completed'])
            ->get();

        $chartLabels  = [];
        $chartOrders  = [];
        $chartRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $dt    = now()->subMonths($i);
            $ymKey = $dt->format('Y-m');
            $chartLabels[] = $dt->format('M');

            $bucket = $allChartOrders->filter(fn ($o) => $o->created_at->format('Y-m') === $ymKey);
            $chartOrders[]  = $bucket->count();
            $chartRevenue[] = (float) $bucket->sum('total_amount');
        }

        // ── Top Routes (grouped by route via trip) ─────────────────
        $topRoutes = Order::select('trip_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total_amount) as total_revenue'))
            ->with('trip.route')
            ->join('trips', 'orders.trip_id', '=', 'trips.id')
            ->whereIn('orders.status', ['confirmed', 'completed'])
            ->groupBy('trip_id')
            ->orderByDesc('order_count')
            ->take(5)
            ->get();

        // ── Order Status Distribution ──────────────────────────────
        $orderStatusDist = [
            'pending'   => Order::where('status', 'pending')->count(),
            'confirmed' => $confirmedOrders,
            'completed' => $completedOrders,
            'cancelled' => $cancelledOrders,
        ];

        return view('admin.dashboard', compact(
            'pendingOrders',
            'todayTrips',
            'upcomingTrips',
            'recentOrders',
            'totalRevenue',
            'revenueThisMonth',
            'revenueLastMonth',
            'totalOrders',
            'confirmedOrders',
            'completedOrders',
            'cancelledOrders',
            'totalBuses',
            'totalRoutes',
            'totalCustomers',
            'newCustomersMonth',
            'chartLabels',
            'chartOrders',
            'chartRevenue',
            'topRoutes',
            'orderStatusDist'
        ));
    }
}

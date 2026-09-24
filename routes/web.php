<?php

use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\SeatSelectionController;
use App\Http\Controllers\Customer\TripController as CustomerTripController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Beranda (Publik)
Route::get('/', function () {
    $origins = \App\Models\Route::select('origin')->distinct()->orderBy('origin')->pluck('origin');
    $destinations = \App\Models\Route::select('destination')->distinct()->orderBy('destination')->pluck('destination');
    $routes = \App\Models\Route::withCount(['trips' => function ($q) {
        $q->where('status', 'scheduled');
    }])->withMin(['trips' => function ($q) {
        $q->where('status', 'scheduled');
    }], 'price')->orderBy('origin')->get();
    $bookedSeatsCountSubquery = \App\Models\OrderItem::selectRaw('count(*)')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->whereColumn('orders.trip_id', 'trips.id')
        ->whereIn('orders.status', ['pending', 'confirmed', 'completed']);

    $availableTrips = \App\Models\Trip::with(['bus', 'route'])
        ->select('trips.*')
        ->selectSub($bookedSeatsCountSubquery, 'booked_seats_count')
        ->where('status', 'scheduled')
        ->where('departure_at', '>=', now())
        ->orderBy('departure_at', 'asc')
        ->take(4)
        ->get();

    $buses = \App\Models\Bus::with(['facilities', 'images'])->orderBy('name')->take(3)->get();
    $facilities = \App\Models\Facility::take(6)->get();

    return view('home', compact('origins', 'destinations', 'routes', 'availableTrips', 'buses', 'facilities'));
})->name('home');

// Halaman Tentang (Publik)
Route::get('/tentang', function () {
    $routes = \App\Models\Route::withCount(['trips' => function ($q) {
        $q->where('status', 'scheduled');
    }])->orderBy('origin')->get();

    return view('about', compact('routes'));
})->name('about');

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\PublicRouteController;
use App\Http\Controllers\PublicTripController;

// Halaman Rute — discovery page (Publik)
Route::get('/rute', [PublicRouteController::class, 'index'])->name('routes.index');
Route::get('/rute/{route}', [PublicRouteController::class, 'show'])->name('routes.show');

// Halaman Armada — fleet discovery (Publik)
Route::get('/armada', [FleetController::class, 'index'])->name('buses.index');
Route::get('/armada/{bus}', [FleetController::class, 'show'])->name('buses.show');
Route::get('/fleet', [FleetController::class, 'index'])->name('fleet.index');
Route::get('/fleet/{bus}', [FleetController::class, 'show'])->name('fleet.show');

// Halaman Fasilitas — fleet facilities (Publik)
Route::get('/fasilitas', [FacilityController::class, 'index'])->name('facilities.index');

// Halaman Perjalanan Publik — trip discovery (Publik)
Route::get('/perjalanan', [PublicTripController::class, 'index'])->name('trips.index');
Route::get('/perjalanan/{trip}', [PublicTripController::class, 'show'])->name('trips.show');

// Halaman Cara Memesan (Publik)
Route::get('/cara-pemesanan', function () {
    return view('how-to-order');
})->name('how-to-order');

// Halaman Panduan Keberangkatan (Publik)
Route::get('/informasi-keberangkatan', function () {
    return view('departure-info');
})->name('departure-info');

// Halaman FAQ (Publik)
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Autentikasi Pengguna (Khusus Tamu / Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (Pengguna Terautentikasi)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Area Pelanggan (Customer Area)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('customer.dashboard');
    });
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/trips', [CustomerTripController::class, 'index'])->name('trips.index');
    Route::get('/trips/{trip}', [CustomerTripController::class, 'show'])->name('trips.show');
    Route::get('/trips/{trip}/seats', [SeatSelectionController::class, 'show'])->name('trips.seats');
    Route::post('/trips/{trip}/seats', [SeatSelectionController::class, 'store'])->name('trips.seats.store');
    Route::get('/trips/{trip}/booking', [OrderController::class, 'create'])->name('trips.booking');
    Route::post('/trips/{trip}/booking', [OrderController::class, 'store'])->name('trips.booking.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Area Administrator (Admin Area)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('buses', BusController::class)->except(['show']);
    Route::resource('routes', RouteController::class)->except(['show']);
    Route::resource('trips', TripController::class)->except(['show']);

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
});

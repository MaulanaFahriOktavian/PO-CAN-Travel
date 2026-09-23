<?php

use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
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
    return view('home');
})->name('home');

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
});

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTripRequest;
use App\Http\Requests\Admin\UpdateTripRequest;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TripController extends Controller
{
    /**
     * Tampilkan daftar jadwal perjalanan.
     */
    public function index(): View
    {
        $trips = Trip::with(['bus', 'route'])->withCount('orders')->orderBy('departure_at', 'desc')->get();

        return view('admin.trips.index', compact('trips'));
    }

    /**
     * Tampilkan formulir tambah jadwal perjalanan.
     */
    public function create(): View
    {
        $buses = Bus::orderBy('name')->get();
        $routes = Route::orderBy('origin')->get();

        return view('admin.trips.create', compact('buses', 'routes'));
    }

    /**
     * Simpan jadwal perjalanan baru.
     */
    public function store(StoreTripRequest $request): RedirectResponse
    {
        Trip::create($request->validated());

        return redirect()->route('admin.trips.index')->with('success', 'Jadwal perjalanan berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit jadwal perjalanan.
     */
    public function edit(Trip $trip): View
    {
        $buses = Bus::orderBy('name')->get();
        $routes = Route::orderBy('origin')->get();

        return view('admin.trips.edit', compact('trip', 'buses', 'routes'));
    }

    /**
     * Perbarui data jadwal perjalanan.
     */
    public function update(UpdateTripRequest $request, Trip $trip): RedirectResponse
    {
        $trip->update($request->validated());

        return redirect()->route('admin.trips.index')->with('success', 'Data jadwal perjalanan berhasil diperbarui.');
    }

    /**
     * Hapus jadwal perjalanan jika belum memiliki pesanan.
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        if ($trip->orders()->exists()) {
            return back()->with('error', 'Perjalanan tidak dapat dihapus karena sudah memiliki pesanan.');
        }

        $trip->delete();

        return redirect()->route('admin.trips.index')->with('success', 'Jadwal perjalanan berhasil dihapus.');
    }
}

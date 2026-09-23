<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRouteRequest;
use App\Http\Requests\Admin\UpdateRouteRequest;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RouteController extends Controller
{
    /**
     * Tampilkan daftar rute perjalanan.
     */
    public function index(): View
    {
        $routes = Route::withCount('trips')->orderBy('id', 'desc')->get();

        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Tampilkan formulir tambah rute perjalanan.
     */
    public function create(): View
    {
        return view('admin.routes.create');
    }

    /**
     * Simpan rute perjalanan baru.
     */
    public function store(StoreRouteRequest $request): RedirectResponse
    {
        Route::create($request->validated());

        return redirect()->route('admin.routes.index')->with('success', 'Rute perjalanan berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit rute perjalanan.
     */
    public function edit(Route $route): View
    {
        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Perbarui data rute perjalanan.
     */
    public function update(UpdateRouteRequest $request, Route $route): RedirectResponse
    {
        $route->update($request->validated());

        return redirect()->route('admin.routes.index')->with('success', 'Data rute perjalanan berhasil diperbarui.');
    }

    /**
     * Hapus rute perjalanan jika belum memiliki trip.
     */
    public function destroy(Route $route): RedirectResponse
    {
        if ($route->trips()->exists()) {
            return back()->with('error', 'Rute tidak dapat dihapus karena sudah digunakan pada perjalanan.');
        }

        $route->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Rute perjalanan berhasil dihapus.');
    }
}

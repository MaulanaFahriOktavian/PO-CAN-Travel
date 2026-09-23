<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBusRequest;
use App\Http\Requests\Admin\UpdateBusRequest;
use App\Models\Bus;
use App\Models\Seat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BusController extends Controller
{
    /**
     * Tampilkan daftar armada bus.
     */
    public function index(): View
    {
        $buses = Bus::withCount('trips')->orderBy('id', 'desc')->get();

        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Tampilkan formulir tambah armada bus.
     */
    public function create(): View
    {
        return view('admin.buses.create');
    }

    /**
     * Simpan armada bus baru beserta kursi secara atomik.
     */
    public function store(StoreBusRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $bus = Bus::create($request->validated());

            $seatNumbers = Bus::generateSeatNumbers($bus->total_seats);
            foreach ($seatNumbers as $seatNumber) {
                Seat::create([
                    'bus_id' => $bus->id,
                    'seat_number' => $seatNumber,
                ]);
            }
        });

        return redirect()->route('admin.buses.index')->with('success', 'Armada bus berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit armada bus dan struktur tata letak kursi.
     */
    public function edit(Bus $bus): View
    {
        $seats = $bus->seats()->orderBy('id')->get();

        return view('admin.buses.edit', compact('bus', 'seats'));
    }

    /**
     * Perbarui data armada bus dan sinkronkan jumlah kursi secara aman.
     */
    public function update(UpdateBusRequest $request, Bus $bus): RedirectResponse
    {
        $newTotal = (int) $request->total_seats;
        $oldTotal = (int) $bus->total_seats;

        $hasError = false;
        $errorMessage = '';

        DB::transaction(function () use ($request, $bus, $newTotal, $oldTotal, &$hasError, &$errorMessage) {
            if ($newTotal < $oldTotal) {
                $currentSeats = $bus->seats()->orderBy('id')->get();
                $seatsToRemove = $currentSeats->slice($newTotal);

                // Cek apakah ada kursi yang akan dihapus yang sudah memiliki histori order_items
                foreach ($seatsToRemove as $seat) {
                    if ($seat->orderItems()->exists()) {
                        $hasError = true;
                        $errorMessage = 'Jumlah kursi tidak dapat dikurangi karena kursi yang akan dihapus telah memiliki histori pesanan.';
                        return;
                    }
                }

                // Jika aman, hapus hanya kursi paling akhir yang berlebih
                foreach ($seatsToRemove as $seat) {
                    $seat->delete();
                }
            } elseif ($newTotal > $oldTotal) {
                $allGenerated = Bus::generateSeatNumbers($newTotal);
                $newSeatNumbers = array_slice($allGenerated, $oldTotal);

                foreach ($newSeatNumbers as $seatNumber) {
                    Seat::create([
                        'bus_id' => $bus->id,
                        'seat_number' => $seatNumber,
                    ]);
                }
            }

            $bus->update($request->validated());
        });

        if ($hasError) {
            return back()->withInput()->with('error', $errorMessage);
        }

        return redirect()->route('admin.buses.index')->with('success', 'Data armada bus berhasil diperbarui.');
    }

    /**
     * Hapus armada bus jika belum digunakan dalam perjalanan.
     */
    public function destroy(Bus $bus): RedirectResponse
    {
        if ($bus->trips()->exists()) {
            return back()->with('error', 'Armada tidak dapat dihapus karena sudah digunakan pada perjalanan.');
        }

        $bus->delete();

        return redirect()->route('admin.buses.index')->with('success', 'Armada bus berhasil dihapus.');
    }
}

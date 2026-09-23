<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seat;
use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar riwayat pesanan milik customer yang sedang login.
     */
    public function index(Request $request): View
    {
        $allowedStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        $selectedStatus = $request->query('status');
        $search = trim($request->query('search', ''));

        $query = Order::where('user_id', Auth::id());

        // Whitelist filter status
        if ($selectedStatus && in_array($selectedStatus, $allowedStatuses, true)) {
            $query->where('status', $selectedStatus);
        } else {
            $selectedStatus = null;
        }

        // Filter pencarian kode pesanan
        if ($search !== '') {
            $query->where('order_code', 'like', "%{$search}%");
        }

        $orders = $query->with(['trip.route', 'trip.bus'])
            ->withCount('orderItems')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('customer.orders.index', compact('orders', 'selectedStatus', 'search'));
    }

    /**
     * Menampilkan formulir data penumpang untuk kursi yang dipilih.
     */
    public function create(Trip $trip): View|RedirectResponse
    {
        // Hanya trip berstatus scheduled yang dapat diproses
        if ($trip->status !== 'scheduled') {
            abort(404);
        }

        $sessionTripId = session('booking.trip_id');
        $sessionSeatIds = session('booking.seat_ids', []);

        // Validasi keberadaan session pemilihan kursi
        if (!$sessionTripId || $sessionTripId != $trip->id || empty($sessionSeatIds)) {
            return redirect()->route('customer.trips.seats', $trip)
                ->with('error', 'Silakan pilih kursi terlebih dahulu.');
        }

        $trip->load(['bus', 'route']);

        // Mengambil data kursi sesuai session
        $seats = Seat::where('bus_id', $trip->bus_id)
            ->whereIn('id', $sessionSeatIds)
            ->orderBy('id')
            ->get();

        // Pastikan seluruh kursi yang di-request benar-benar milik bus trip ini
        if ($seats->count() !== count(array_unique($sessionSeatIds))) {
            return redirect()->route('customer.trips.seats', $trip)
                ->with('error', 'Sesi pemilihan kursi tidak valid. Silakan pilih kursi kembali.');
        }

        return view('customer.orders.create', compact('trip', 'seats'));
    }

    /**
     * Menyimpan data pemesanan (Order + OrderItems) di dalam database transaction dengan row locking.
     */
    public function store(StoreOrderRequest $request, Trip $trip): RedirectResponse
    {
        // Trip harus berstatus scheduled
        if ($trip->status !== 'scheduled') {
            abort(404);
        }

        $sessionTripId = session('booking.trip_id');
        $sessionSeatIds = session('booking.seat_ids', []);

        // Validasi konsistensi route trip ID dengan session
        if (!$sessionTripId || $sessionTripId != $trip->id || empty($sessionSeatIds)) {
            return redirect()->route('customer.trips.seats', $trip)
                ->with('error', 'Sesi pemilihan kursi tidak valid atau sudah kedaluwarsa. Silakan pilih kursi kembali.');
        }

        // ALUR TRANSACTION:
        // BEGIN -> lock Trip -> lock Seat rows -> validasi seat -> cek order aktif -> buat Order -> buat OrderItem -> COMMIT
        $order = DB::transaction(function () use ($trip, $request, $sessionSeatIds) {
            // 1. Lock Trip row
            $lockedTrip = Trip::where('id', $trip->id)->lockForUpdate()->first();
            if (!$lockedTrip || $lockedTrip->status !== 'scheduled') {
                throw ValidationException::withMessages([
                    'trip' => 'Jadwal perjalanan tidak tersedia atau sudah tidak aktif.',
                ]);
            }

            // Pastikan tidak ada duplikasi seat_id di session
            if (count($sessionSeatIds) !== count(array_unique($sessionSeatIds))) {
                throw ValidationException::withMessages([
                    'seats' => 'Pilihan kursi tidak boleh duplikat.',
                ]);
            }

            // 2. Lock Seat rows
            $lockedSeats = Seat::whereIn('id', $sessionSeatIds)
                ->lockForUpdate()
                ->get();

            // 3. Validasi Seat: jumlah sesuai dan seluruh kursi milik bus trip ini
            if ($lockedSeats->count() !== count($sessionSeatIds)) {
                throw ValidationException::withMessages([
                    'seats' => 'Salah satu atau lebih kursi yang dipilih tidak ditemukan.',
                ]);
            }

            foreach ($lockedSeats as $seat) {
                if ($seat->bus_id !== $lockedTrip->bus_id) {
                    throw ValidationException::withMessages([
                        'seats' => 'Salah satu kursi bukan milik armada perjalanan ini.',
                    ]);
                }
            }

            // 4. Cek OrderItem / Order aktif pada trip ini
            // Status order yang memblokir kursi: pending, confirmed, completed
            $bookedSeatIds = OrderItem::whereHas('order', function ($query) use ($lockedTrip) {
                $query->where('trip_id', $lockedTrip->id)
                      ->whereIn('status', ['pending', 'confirmed', 'completed']);
            })->whereIn('seat_id', $sessionSeatIds)
              ->pluck('seat_id')
              ->all();

            if (!empty($bookedSeatIds)) {
                throw ValidationException::withMessages([
                    'seats' => 'Salah satu atau lebih kursi yang Anda pilih sudah dipesan oleh pelanggan lain.',
                ]);
            }

            // 5. Hitung total amount murni dari database trip price
            $price = $lockedTrip->price;
            $totalAmount = count($sessionSeatIds) * $price;

            // Generate order_code server-side
            $orderCode = Order::generateOrderCode();

            // 6. Buat Order (status default: pending)
            $newOrder = Order::create([
                'user_id' => Auth::id(),
                'trip_id' => $lockedTrip->id,
                'order_code' => $orderCode,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // 7. Buat OrderItem untuk setiap kursi
            $passengers = $request->input('passengers');
            foreach ($lockedSeats as $seat) {
                $passengerData = $passengers[$seat->id];
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'seat_id' => $seat->id,
                    'passenger_name' => $passengerData['name'],
                    'passenger_identity' => $passengerData['identity'],
                    'price' => $price,
                ]);
            }

            return $newOrder;
        });

        // Bersihkan session booking setelah transaksi berhasil
        session()->forget(['booking.trip_id', 'booking.seat_ids', 'selected_seats']);

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Pemesanan berhasil dibuat dengan kode ' . $order->order_code . '.');
    }

    /**
     * Menampilkan ringkasan detail pesanan yang berhasil dibuat.
     */
    public function show(Order $order): View
    {
        // Pastikan hanya pemilik order yang dapat melihat detail pesanan
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['trip.bus', 'trip.route', 'orderItems.seat']);

        return view('customer.orders.show', compact('order'));
    }
}

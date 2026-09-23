<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar seluruh pesanan pelanggan untuk admin.
     */
    public function index(Request $request): View
    {
        $allowedStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        $selectedStatus = $request->query('status');
        $search = $request->query('search');

        $query = Order::query();

        // Filter status pesanan (whitelist)
        if ($selectedStatus && in_array($selectedStatus, $allowedStatuses, true)) {
            $query->where('status', $selectedStatus);
        } else {
            $selectedStatus = null;
        }

        // Pencarian berdasarkan kode pesanan, nama customer, atau email customer
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->with(['trip.route', 'trip.bus', 'user'])
            ->withCount('orderItems')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'selectedStatus', 'search'));
    }

    /**
     * Menampilkan rincian pesanan spesifik untuk admin.
     */
    public function show(Order $order): View
    {
        $order->load(['trip.bus', 'trip.route', 'user', 'orderItems.seat']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Memperbarui status pesanan sesuai dengan aturan transisi status.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $newStatus = $request->input('status');

        if (!$order->canChangeStatusTo($newStatus)) {
            return back()->withErrors([
                'status' => "Perubahan status dari '{$order->status}' ke '{$newStatus}' tidak diperbolehkan.",
            ]);
        }

        $order->update(['status' => $newStatus]);

        $statusLabels = [
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            'pending' => 'Menunggu Pembayaran',
        ];

        $label = $statusLabels[$newStatus] ?? ucfirst($newStatus);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', "Status pesanan {$order->order_code} berhasil diperbarui menjadi {$label}.");
    }
}

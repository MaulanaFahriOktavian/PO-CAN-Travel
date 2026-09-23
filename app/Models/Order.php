<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'order_code',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'integer',
    ];

    /**
     * Menghasilkan kode pesanan unik berformat PCT-YYYYMMDD-XXXXXX.
     */
    public static function generateOrderCode(): string
    {
        $date = \Carbon\Carbon::now('Asia/Jakarta')->format('Ymd');
        do {
            $random = strtoupper(\Illuminate\Support\Str::random(6));
            $code = "PCT-{$date}-{$random}";
        } while (static::where('order_code', $code)->exists());

        return $code;
    }

    /**
     * Definisi aturan transisi status pesanan yang diperbolehkan.
     */
    public static function statusTransitions(): array
    {
        return [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];
    }

    /**
     * Memeriksa apakah status saat ini dapat diubah ke status tujuan.
     */
    public function canChangeStatusTo(string $newStatus): bool
    {
        $allowed = static::statusTransitions()[$this->status] ?? [];
        return in_array($newStatus, $allowed, true);
    }

    /**
     * Order belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order belongs to a trip.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Order has many order items.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

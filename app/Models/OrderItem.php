<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'seat_id',
        'passenger_name',
        'passenger_identity',
        'price',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    /**
     * Order item belongs to an order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Order item belongs to a seat.
     */
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
}

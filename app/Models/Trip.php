<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_at',
        'arrival_at',
        'price',
        'status',
    ];

    protected $casts = [
        'departure_at' => 'datetime',
        'arrival_at' => 'datetime',
        'price' => 'integer',
    ];

    /**
     * Trip belongs to a bus.
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Trip belongs to a route.
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Trip has many orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

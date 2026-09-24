<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'image_path',
        'caption',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Foto merupakan bagian dari satu armada bus.
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
}

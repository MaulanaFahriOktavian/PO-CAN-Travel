<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'total_seats',
    ];

    protected $casts = [
        'total_seats' => 'integer',
    ];

    /**
     * Menghasilkan urutan nomor kursi (1A, 1B, 1C, 1D, 2A, ...) sejumlah total kursi.
     *
     * @param int $totalSeats
     * @return array<int, string>
     */
    public static function generateSeatNumbers(int $totalSeats): array
    {
        $seatLetters = ['A', 'B', 'C', 'D'];
        $seats = [];
        $seatCount = 0;
        $row = 1;

        while ($seatCount < $totalSeats) {
            foreach ($seatLetters as $letter) {
                if ($seatCount >= $totalSeats) {
                    break;
                }
                $seats[] = "{$row}{$letter}";
                $seatCount++;
            }
            $row++;
        }

        return $seats;
    }

    /**
     * Bus has many seats.
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Bus has many trips.
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}

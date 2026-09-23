<?php

namespace App\Http\Requests\Customer;

use App\Models\OrderItem;
use App\Models\Seat;
use Illuminate\Foundation\Http\FormRequest;

class StoreSeatSelectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'seat_ids' => ['required', 'array', 'min:1'],
            'seat_ids.*' => ['integer'],
        ];
    }

    /**
     * Configure the validator instance with custom business rules.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $seatIds = $this->input('seat_ids');

            if (!is_array($seatIds) || empty($seatIds)) {
                return;
            }

            // 1. Validasi keunikan nomor kursi (tidak boleh duplikat)
            if (count($seatIds) !== count(array_unique($seatIds))) {
                $validator->errors()->add('seat_ids', 'Pilihan nomor kursi tidak boleh duplikat.');
            }

            /** @var \App\Models\Trip|null $trip */
            $trip = $this->route('trip');
            if (!$trip || $trip->status !== 'scheduled') {
                $validator->errors()->add('seat_ids', 'Perjalanan tidak tersedia atau sudah tidak aktif.');
                return;
            }

            // 2. Validasi keberadaan kursi dan kepemilikan oleh armada bus trip
            $validBusSeats = Seat::where('bus_id', $trip->bus_id)
                ->whereIn('id', $seatIds)
                ->pluck('id')
                ->all();

            if (count($validBusSeats) !== count(array_unique($seatIds))) {
                $validator->errors()->add('seat_ids', 'Salah satu atau lebih kursi yang dipilih tidak valid atau bukan milik armada perjalanan ini.');
                return;
            }

            // 3. Validasi status kursi: tidak boleh ada kursi yang sudah booked
            $bookedSeatIds = OrderItem::whereHas('order', function ($query) use ($trip) {
                $query->where('trip_id', $trip->id)
                      ->whereIn('status', ['pending', 'confirmed', 'completed']);
            })->whereIn('seat_id', $seatIds)->pluck('seat_id')->all();

            if (!empty($bookedSeatIds)) {
                $validator->errors()->add('seat_ids', 'Salah satu atau lebih kursi yang Anda pilih sudah dipesan oleh penumpang lain.');
            }
        });
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'seat_ids.required' => 'Silakan pilih minimal 1 kursi perjalanan.',
            'seat_ids.array' => 'Format pilihan kursi tidak valid.',
            'seat_ids.min' => 'Silakan pilih minimal 1 kursi perjalanan.',
            'seat_ids.*.integer' => 'ID kursi harus berupa angka.',
        ];
    }
}

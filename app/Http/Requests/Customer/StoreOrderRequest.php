<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'passengers' => ['required', 'array'],
            'passengers.*.name' => ['required', 'string', 'max:100'],
            'passengers.*.identity' => ['required', 'string', 'max:50'],
        ];
    }

    /**
     * Configure the validator instance with custom business rules.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $sessionTripId = session('booking.trip_id');
            $sessionSeatIds = session('booking.seat_ids', []);

            /** @var \App\Models\Trip|null $trip */
            $trip = $this->route('trip');

            // Jika session tidak valid/mismatch, biarkan OrderController::store() melakukan redirect ke seat selection
            if (!$trip || !$sessionTripId || $sessionTripId != $trip->id || empty($sessionSeatIds)) {
                return;
            }

            $passengers = $this->input('passengers');
            if (!is_array($passengers)) {
                return;
            }

            // Validasi: key array passengers harus tepat sesuai dengan seat_ids yang dipilih
            $passengerSeatIds = array_map('intval', array_keys($passengers));
            $expectedSeatIds = array_map('intval', array_values(array_unique($sessionSeatIds)));

            sort($passengerSeatIds);
            sort($expectedSeatIds);

            if ($passengerSeatIds !== $expectedSeatIds) {
                $validator->errors()->add('passengers', 'Data penumpang harus tepat satu untuk setiap kursi yang dipilih.');
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
            'passengers.required' => 'Data penumpang wajib diisi.',
            'passengers.array' => 'Format data penumpang tidak valid.',
            'passengers.*.name.required' => 'Nama lengkap penumpang wajib diisi.',
            'passengers.*.name.string' => 'Nama lengkap penumpang harus berupa teks.',
            'passengers.*.name.max' => 'Nama lengkap penumpang maksimal 100 karakter.',
            'passengers.*.identity.required' => 'Nomor identitas penumpang wajib diisi.',
            'passengers.*.identity.string' => 'Nomor identitas penumpang harus berupa teks.',
            'passengers.*.identity.max' => 'Nomor identitas penumpang maksimal 50 karakter.',
        ];
    }
}

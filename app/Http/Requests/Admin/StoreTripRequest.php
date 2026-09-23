<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bus_id' => ['required', 'exists:buses,id'],
            'route_id' => ['required', 'exists:routes,id'],
            'departure_at' => ['required', 'date'],
            'arrival_at' => ['required', 'date', 'after:departure_at'],
            'price' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:scheduled,departed,completed,cancelled'],
        ];
    }

    public function messages(): array
    {
        return [
            'bus_id.required' => 'Armada bus wajib dipilih.',
            'bus_id.exists' => 'Armada bus yang dipilih tidak valid.',
            'route_id.required' => 'Rute perjalanan wajib dipilih.',
            'route_id.exists' => 'Rute perjalanan yang dipilih tidak valid.',
            'departure_at.required' => 'Waktu keberangkatan wajib diisi.',
            'departure_at.date' => 'Format waktu keberangkatan tidak valid.',
            'arrival_at.required' => 'Waktu kedatangan wajib diisi.',
            'arrival_at.date' => 'Format waktu kedatangan tidak valid.',
            'arrival_at.after' => 'Waktu kedatangan harus setelah waktu keberangkatan.',
            'price.required' => 'Harga tiket wajib diisi.',
            'price.integer' => 'Harga tiket harus berupa nominal angka rupiah.',
            'price.min' => 'Harga tiket tidak boleh negatif.',
            'status.required' => 'Status perjalanan wajib dipilih.',
            'status.in' => 'Status perjalanan tidak valid.',
        ];
    }
}

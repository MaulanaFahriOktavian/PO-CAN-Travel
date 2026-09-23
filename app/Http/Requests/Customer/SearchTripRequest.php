<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class SearchTripRequest extends FormRequest
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
            'origin' => ['nullable', 'string', 'max:100'],
            'destination' => ['nullable', 'string', 'max:100'],
            'departure_date' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'origin' => 'kota asal',
            'destination' => 'kota tujuan',
            'departure_date' => 'tanggal keberangkatan',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'origin.string' => 'Kota asal harus berupa teks.',
            'origin.max' => 'Kota asal maksimal 100 karakter.',
            'destination.string' => 'Kota tujuan harus berupa teks.',
            'destination.max' => 'Kota tujuan maksimal 100 karakter.',
            'departure_date.date' => 'Format tanggal keberangkatan tidak valid.',
        ];
    }
}

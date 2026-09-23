<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin' => ['required', 'string', 'max:100'],
            'destination' => ['required', 'string', 'max:100', 'different:origin'],
            'duration' => ['required', 'integer', 'min:1', 'max:1440'],
        ];
    }

    public function messages(): array
    {
        return [
            'origin.required' => 'Kota asal wajib diisi.',
            'destination.required' => 'Kota tujuan wajib diisi.',
            'destination.different' => 'Kota asal dan kota tujuan tidak boleh sama.',
            'duration.required' => 'Durasi perjalanan wajib diisi.',
            'duration.min' => 'Durasi minimal 1 menit.',
            'duration.max' => 'Durasi maksimal 1440 menit (24 jam).',
        ];
    }
}

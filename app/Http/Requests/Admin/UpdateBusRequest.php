<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bus = $this->route('bus');
        $busId = is_object($bus) ? $bus->id : $bus;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('buses', 'code')->ignore($busId),
            ],
            'total_seats' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama armada wajib diisi.',
            'code.required' => 'Kode armada wajib diisi.',
            'code.unique' => 'Kode armada sudah digunakan.',
            'total_seats.required' => 'Jumlah kursi wajib diisi.',
            'total_seats.min' => 'Jumlah kursi minimal 1.',
            'total_seats.max' => 'Jumlah kursi maksimal 100.',
        ];
    }
}

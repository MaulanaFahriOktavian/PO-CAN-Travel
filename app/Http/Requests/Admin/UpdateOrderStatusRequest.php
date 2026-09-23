<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
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
            'status' => ['required', 'string', 'in:pending,confirmed,cancelled,completed'],
        ];
    }

    /**
     * Configure the validator instance with state transition business rules.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            /** @var \App\Models\Order|null $order */
            $order = $this->route('order');
            $newStatus = $this->input('status');

            if ($order && is_string($newStatus) && in_array($newStatus, ['pending', 'confirmed', 'cancelled', 'completed'], true)) {
                if (!$order->canChangeStatusTo($newStatus)) {
                    $validator->errors()->add('status', "Perubahan status dari '{$order->status}' ke '{$newStatus}' tidak diperbolehkan.");
                }
            }
        });
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.string' => 'Format status pesanan tidak valid.',
            'status.in' => 'Status pesanan yang dipilih tidak valid.',
        ];
    }
}

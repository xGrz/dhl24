<?php

namespace xGrz\Dhl24\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recipient' => ['required', 'array'],
            'recipient.name' => ['required', 'string'],
            'recipient.postal_code' => ['required', 'string'],
            'recipient.city' => ['required', 'string'],
            'recipient.street' => ['required', 'string'],
            'recipient.house_number' => ['required', 'string'],
            'recipient.apartment_number' => ['required', 'string'],

            'contact' => ['required', 'array'],
            'contact.name' => ['nullable', 'string'],
            'contact.email' => ['nullable', 'string'],
            'contact.phone' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

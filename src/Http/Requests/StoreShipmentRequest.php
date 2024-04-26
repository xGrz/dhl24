<?php

namespace xGrz\Dhl24\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recipient' => ['required', 'array'],
            'recipient.name' => ['required', 'string', 'max:60'],
            'recipient.postalCode' => ['required', 'string', 'max:10'],
            'recipient.city' => ['required', 'string', 'max:17'],
            'recipient.street' => ['required', 'string', 'max:35'],
            'recipient.houseNumber' => ['required', 'string', 'max:10'],

            'contact' => ['required', 'array'],
            'contact.name' => ['nullable', 'string', 'max:60'],
            'contact.email' => ['nullable', 'email', 'max:60'],
            'contact.phone' => ['nullable', 'string', 'max:20'],

            'items' => ['required', 'array'],
            'items.*.type' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.weight' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'items.*.length' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'items.*.width' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'items.*.height' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'items.*.nonStandard' => ['nullable', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function getRulesForRecipient(): array
    {
        return collect($this->rules())
            ->filter(fn($value, $key) => str($key)->startsWith('recipient.'))
            ->undot()
            ->get('recipient');
    }

    public function getRulesForContact(): array
    {
        return collect($this->rules())
            ->filter(fn($value, $key) => str($key)->startsWith('contact.'))
            ->undot()
            ->get('contact');
    }

    public function getRulesForItems(): array
    {
        return collect($this->rules())
            ->filter(fn($value, $key) => str($key)->startsWith('items'))
            ->toArray();
    }

    public function getRulesForItem()
    {
        $itemRules = [];
        collect($this->rules())
            ->filter(fn($value, $key) => str($key)->startsWith('items.'))
            ->map(function ($item, $key) use (&$itemRules) {
                $newKey = str_replace('items.*.', '', $key);
                $itemRules[$newKey] = $item;
            })
            ->toArray();
        return $itemRules;
    }
}

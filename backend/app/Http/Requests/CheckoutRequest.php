<?php

namespace App\Http\Requests;

use App\Services\ProductService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function expectsJson(): bool
    {
        return true;
    }

    /**
     * After base validation: if any cart item requires shipping,
     * the shipping address fields become required.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $items = $this->input('items', []);
            $productService = app(ProductService::class);

            $needsShipping = collect($items)->contains(
                fn ($item) => isset($item['product_code'])
                    && $productService->requiresShipping((string) $item['product_code'])
            );

            if (!$needsShipping) {
                return;
            }

            foreach (['shipping_address', 'shipping_city', 'shipping_postal_code', 'shipping_country'] as $field) {
                if (blank($this->input($field))) {
                    $validator->errors()->add($field, 'Shipping address is required for physical items.');
                }
            }
        });
    }

    public function rules(): array
    {
        return [
            'locale' => ['required', 'string', 'in:lt,en,ru'],
            'customer_email' => ['required', 'email:rfc', 'max:255'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_code' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'shipping_address' => ['nullable', 'string', 'max:500'],
            'shipping_city' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:20'],
            'shipping_country' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Cart cannot be empty.',
            'items.min' => 'Cart cannot be empty.',
            'customer_email.required' => 'Customer email is required.',
            'customer_email.email' => 'A valid email address is required.',
            'customer_name.required' => 'Customer name is required.',
            'locale.in' => 'Locale must be lt, en, or ru.',
        ];
    }
}

<?php

namespace App\Http\Requests\Shop;

use App\Services\Cart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // anyone can shop, logged in or not
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')->where('is_active', true)->whereNull('deleted_at')],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:'.Cart::MAX_QUANTITY],
        ];
    }

    public function messages(): array
    {
        return ['product_id.exists' => 'Sorry, that item is no longer available.'];
    }
}

<?php

namespace App\Http\Requests\Shop;

use App\Services\Cart;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // 0 removes the line (the "−" button on a quantity of 1).
        return ['quantity' => ['required', 'integer', 'min:0', 'max:'.Cart::MAX_QUANTITY]];
    }
}

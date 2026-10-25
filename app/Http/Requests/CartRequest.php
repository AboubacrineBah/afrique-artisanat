<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Ajout au panier (POST, avec product_id) et mise à jour de quantité (PATCH). */
class CartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [Rule::requiredIf($this->isMethod('POST')), 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:0', 'max:' . config('shop.max_quantity_per_line')],
        ];
    }
}

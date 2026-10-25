<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    public function rules(): array
    {
        $product = $this->route('product'); // null à la création

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'artisan_id' => ['nullable', 'integer', 'exists:artisans,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:170', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('products', 'slug')->ignore($product?->id)],
            'description' => ['required', 'string', 'max:5000'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'material' => ['nullable', 'string', 'max:100'],
            'origin' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'images' => ['nullable', 'array', 'max:' . config('shop.max_images_per_product')],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}

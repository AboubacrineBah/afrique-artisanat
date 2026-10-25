<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // Adresse du carnet (facultatif) : doit appartenir au client connecté
            'address_id' => ['nullable', 'integer', Rule::exists('addresses', 'id')->where('user_id', $this->user()->id)],
            'shipping_name' => ['required', 'string', 'max:120'],
            'shipping_phone' => ['required', 'string', 'regex:/^\+?[0-9\s\-]{8,20}$/'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'accept_terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_phone.regex' => 'Le numéro de téléphone n’est pas valide.',
            'accept_terms.accepted' => 'Vous devez accepter les conditions générales de vente.',
        ];
    }

    public function paymentMethod(): PaymentMethod
    {
        return PaymentMethod::from($this->validated('payment_method'));
    }
}

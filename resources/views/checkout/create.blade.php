@extends('layouts.app')

@section('title', 'Commande')

@section('content')
@php
    $user = auth()->user();
    $default = $addresses->firstWhere('is_default', true) ?? $addresses->first();
@endphp
<div class="container pb-5">
    @include('partials.breadcrumb', ['items' => [['Panier', route('cart.index')], ['Commande', null]]])
    <h1 class="mb-4">Commande</h1>

    <form method="POST" action="{{ route('checkout.store') }}" class="row g-4">
        @csrf
        <div class="col-lg-8">
            <fieldset class="panel mb-4">
                <legend class="h4">Livraison</legend>

                @if ($addresses->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="address_id">Adresse enregistrée</label>
                        <select class="form-select" id="address_id" name="address_id">
                            <option value="">Saisir une autre adresse</option>
                            @foreach ($addresses as $address)
                                <option value="{{ $address->id }}" @selected((int) old('address_id', $default?->id) === $address->id)
                                        data-name="{{ $address->recipient_name }}" data-phone="{{ $address->phone }}" data-city="{{ $address->city }}" data-address="{{ $address->address_line }}">
                                    {{ $address->label ?: 'Adresse' }} — {{ $address->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="row">
                    <x-input class="col-md-6" name="shipping_name" label="Nom du destinataire" :value="$default?->recipient_name ?? $user->name" required />
                    <x-input class="col-md-6" name="shipping_phone" type="tel" label="Téléphone" :value="$default?->phone ?? $user->phone" required />
                    <x-input class="col-md-6" name="shipping_city" label="Ville" :value="$default?->city" required />
                    <x-input class="col-md-6" name="shipping_address" label="Adresse" :value="$default?->address_line" required />
                </div>
                <x-textarea name="notes" label="Instructions pour le livreur (facultatif)" rows="3" />
            </fieldset>

            <fieldset class="panel mb-4">
                <legend class="h4">Paiement</legend>
                @foreach ($paymentMethods as $method)
                    <div class="form-check border rounded p-3 ps-5 mb-2 bg-white">
                        <input class="form-check-input" type="radio" name="payment_method" id="pm-{{ $method->value }}" value="{{ $method->value }}" @checked(old('payment_method', 'cod') === $method->value)>
                        <label class="form-check-label fw-semibold" for="pm-{{ $method->value }}">{{ $method->label() }}</label>
                    </div>
                @endforeach
                @error('payment_method')<div class="text-danger small">{{ $message }}</div>@enderror
                <p class="small mt-2 mb-0">Les paiements Mobile Money sont simulés : aucune transaction réelle.</p>
            </fieldset>

            <x-check name="accept_terms" label="J’accepte les conditions générales de vente" />
            <button type="submit" class="btn btn-primary btn-lg">Confirmer la commande</button>
        </div>

        <aside class="col-lg-4">
            <div class="panel">
                <h2 class="h4 mb-3">Votre commande</h2>
                @foreach ($cart['items'] as $item)
                    <div class="d-flex justify-content-between gap-3 py-1">
                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span class="text-nowrap">@money($item->lineTotal())</span>
                    </div>
                @endforeach
                <div class="d-flex justify-content-between py-1 mt-2"><span>Livraison</span><span>@if ($cart['shipping_fee'] > 0) @money($cart['shipping_fee']) @else Offerte @endif</span></div>
                <div class="d-flex justify-content-between border-top border-2 border-info mt-3 pt-3 fs-5 fw-bold"><span>Total</span><span>@money($cart['total'])</span></div>
            </div>
        </aside>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Remplit les champs de livraison à partir de l'adresse enregistrée choisie
    document.getElementById('address_id')?.addEventListener('change', function () {
        const option = this.selectedOptions[0];
        if (!option || !option.value) return;
        const map = { name: 'shipping_name', phone: 'shipping_phone', city: 'shipping_city', address: 'shipping_address' };
        for (const [key, id] of Object.entries(map)) {
            document.getElementById('f-' + id).value = option.dataset[key] ?? '';
        }
    });
</script>
@endpush

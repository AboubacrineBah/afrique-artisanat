@extends('layouts.legal')
@section('title', 'Conditions générales de vente')
@section('legal')
    <h2 class="h4 mt-4">Objet</h2>
    <p>[Présentation de l’objet des conditions]</p>
    <h2 class="h4 mt-4">Prix</h2>
    <p>Les prix sont affichés en francs CFA (FCFA). Livraison : @money(config('shop.shipping_fee')), offerte dès @money(config('shop.free_shipping_threshold')).</p>
    <h2 class="h4 mt-4">Paiement</h2>
    <p>Paiement à la livraison ; Orange Money et Moov Money en simulation (démonstration).</p>
    <h2 class="h4 mt-4">Livraison</h2>
    <p>[Délais et zones de livraison]</p>
    <h2 class="h4 mt-4">Retours et réclamations</h2>
    <p>[Modalités à définir]</p>
@endsection

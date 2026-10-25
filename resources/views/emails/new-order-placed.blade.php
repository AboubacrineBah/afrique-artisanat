<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle commande</title>
</head>

<body>
    <h1>Nouvelle commande sur Afrique Artisanat {{ $order->number }}</h1>
    <p><strong>Client :</strong> {{ $order->user->name }}</p>
    <p><strong>Email :</strong> {{ $order->user->email }}</p>
    <p><strong>Contact de livraison :</strong> {{ $order->shipping_phone }}</p>
    <p><strong>Adresse :</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
    <p><strong>Paiement :</strong> {{ $order->payment_method->label() }}</p>

    <h2>Articles commandés </h2>
    <ul>
        @foreach ($order->items as $item)
            <li>
                {{ $item->product_name }} x {{ $item->quantity }} = {{ $item->line_total_formatted }}
            </li>
        @endforeach
    </ul>

    <p><strong>Sous-total :</strong> {{ $order->subtotal_formatted }}</p>
    <p><strong>Livraison :</strong> {{ $order->shipping_fee_formatted }}</p>
    <p><strong>Total :</strong> {{ $order->total_formatted }}</p>
</body>

</html>
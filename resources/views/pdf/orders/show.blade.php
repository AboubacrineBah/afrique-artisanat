<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Commande {{ $order->number }}</title>
    <style>
        @page {
            margin: 32px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #252525;
        }

        h1 {
            margin-bottom: 4px;
            font-size: 22px;
        }

        h2 {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .muted {
            color: #666;
        }

        .section {
            margin-top: 22px;
        }

        .columns {
            width: 100%;
        }

        .columns td {
            width: 50%;
            vertical-align: top;
        }

        table.items {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .items th,
        .items td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .items th {
            background-color: #f3f3f3;
        }

        .number {
            text-align: right !important;
            white-space: nowrap;
        }

        .total td {
            border-top: 2px solid #333;
            border-bottom: 0;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Fiche de commande</h1>
    <div class="muted">N° {{ $order->number }}</div>

    <table class="columns section">
        <tr>
            <td>
                <h2>Client</h2>
                {{ $order->user->name }}<br>
                {{ $order->user->email }}
            </td>
            <td>
                <h2>Livraison</h2>
                {{ $order->shipping_name }}<br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}<br>
                {{ $order->shipping_phone }}
            </td>
        </tr>
    </table>

    <div class="section">
        <strong>Date :</strong> {{ $order->placed_at?->format('d/m/Y à H:i') }}<br>
        <strong>Statut :</strong> {{ $order->status->label() }}<br>
        <strong>Paiement :</strong> {{ $order->payment_method->label() }}
        — {{ $order->payment_status->label() }}
        @if ($order->payment_reference)
            <br><strong>Référence de paiement :</strong> {{ $order->payment_reference }}
        @endif
        @if ($order->notes)
            <br><strong>Instructions de livraison :</strong> {{ $order->notes }}
        @endif
    </div>

    <div class="section">
        <h2>Articles commandés</h2>
        <table class="items">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th class="number">Prix unitaire</th>
                    <th class="number">Quantité</th>
                    <th class="number">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td class="number">{{ $item->unit_price_formatted }}</td>
                        <td class="number">{{ $item->quantity }}</td>
                        <td class="number">{{ $item->line_total_formatted }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="number">Sous-total</td>
                    <td class="number">{{ $order->subtotal_formatted }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="number">Livraison</td>
                    <td class="number">{{ $order->shipping_fee_formatted }}</td>
                </tr>
                <tr class="total">
                    <td colspan="3" class="number">Total</td>
                    <td class="number">{{ $order->total_formatted }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Confirmation de commande</title>
</head>

<body style="margin:0;padding:24px;background:#faf3e8;font-family:Arial,Helvetica,sans-serif;color:#3b2a1d;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;padding:24px;border-radius:8px;">
        <h2 style="margin-top:0;color:#1f3a5f;">Merci pour votre commande, {{ $order->shipping_name }} !</h2>

        <p>Votre commande <strong>{{ $order->number }}</strong> a bien été enregistrée.</p>

        <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse;">
            @foreach ($order->items as $item)
                <tr style="border-bottom:1px solid #eee;">
                    <td>{{ $item->product_name }} × {{ $item->quantity }}</td>
                    <td align="right">{{ $item->line_total_formatted }}</td>
                </tr>
            @endforeach
            <tr>
                <td>Livraison</td>
                <td align="right">{{ $order->shipping_fee_formatted }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Total</strong>
                </td>
                <td align="right">
                    <strong>{{ $order->total_formatted }}</strong>
                </td>
            </tr>
        </table>

        <p style="margin-top:16px;">
            Mode de paiement : {{ $order->payment_method->label() }}<br>
            Livraison à : {{ $order->shipping_address }}, {{ $order->shipping_city }}
        </p>

        <p style="font-size:12px;color:#7a6a5c;">
            Annonnce : aucune vente réelle n’est effectuée.
        </p>
    </div>
</body>

</html>
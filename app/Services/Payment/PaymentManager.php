<?php

namespace App\Services\Payment;

use App\Models\Order;

class PaymentManager
{
    /** Choisit la passerelle selon le moyen de paiement, puis met à jour la commande. */
    public function process(Order $order): PaymentResult
    {
        $gateway = $order->payment_method->isMobileMoney()
            ? app(MobileMoneyDemoGateway::class)
            : app(CashOnDeliveryGateway::class);

        $result = $gateway->process($order);

        $order->update([
            'payment_status' => $result->status,
            'payment_reference' => $result->reference,
        ]);

        return $result;
    }
}

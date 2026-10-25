<?php

namespace App\Services\Payment;

use App\Enums\PaymentStatus;
use App\Models\Order;

class CashOnDeliveryGateway implements PaymentGateway
{
    /** Rien n'est encaissé à la commande : le paiement reste "à payer" jusqu'à la livraison. */
    public function process(Order $order): PaymentResult
    {
        return new PaymentResult(PaymentStatus::Pending, null, 'Paiement à régler à la livraison.');
    }
}

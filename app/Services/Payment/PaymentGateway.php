<?php

namespace App\Services\Payment;

use App\Models\Order;

/**
 * Contrat commun des moyens de paiement.
 * Pour passer au paiement réel (V2), il suffit d'ajouter une classe qui implémente cette interface.
 */
interface PaymentGateway
{
    public function process(Order $order): PaymentResult;
}

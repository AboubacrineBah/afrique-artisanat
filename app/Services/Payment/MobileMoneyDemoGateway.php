<?php

namespace App\Services\Payment;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Simulation de paiement Mobile Money : aucune transaction réelle, aucun appel externe.
 * Le paiement réussit toujours et renvoie une référence fictive.
 */
class MobileMoneyDemoGateway implements PaymentGateway
{
    public function process(Order $order): PaymentResult
    {
        $operator = $order->payment_method === PaymentMethod::OrangeMoneyDemo ? 'OM' : 'MM';
        $reference = 'DEMO-' . $operator . '-' . strtoupper(Str::random(8));

        return new PaymentResult(PaymentStatus::Paid, $reference, 'Paiement simulé accepté (démonstration).');
    }
}

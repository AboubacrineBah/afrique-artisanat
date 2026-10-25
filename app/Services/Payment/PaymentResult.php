<?php

namespace App\Services\Payment;

use App\Enums\PaymentStatus;

/** Résultat renvoyé par une passerelle de paiement. */
class PaymentResult
{
    public function __construct(
        public readonly PaymentStatus $status,
        public readonly ?string $reference = null,
        public readonly string $message = '',
    ) {
    }

    public function isSuccessful(): bool
    {
        return $this->status !== PaymentStatus::Failed;
    }
}

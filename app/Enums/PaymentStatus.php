<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'À payer',
            self::Paid => 'Payée',
            self::Failed => 'Échec',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::Pending => 'text-bg-warning',
            self::Paid => 'text-bg-success',
            self::Failed => 'text-bg-danger',
        };
    }
}

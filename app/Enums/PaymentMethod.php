<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CashOnDelivery = 'cod';
    case OrangeMoneyDemo = 'orange_money_demo';
    case MoovMoneyDemo = 'moov_money_demo';

    public function label(): string
    {
        return match ($this) {
            self::CashOnDelivery => 'Paiement à la livraison',
            self::OrangeMoneyDemo => 'Orange Money (démo)',
            self::MoovMoneyDemo => 'Moov Money (démo)',
        };
    }

    public function isMobileMoney(): bool
    {
        return $this !== self::CashOnDelivery;
    }
}

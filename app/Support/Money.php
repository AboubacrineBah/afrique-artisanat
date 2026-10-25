<?php

namespace App\Support;

/**
 * Les montants sont stockés en entiers (le franc CFA n'a pas de décimales).
 */
class Money
{
    public static function format(int $amount): string
    {
        // Espace fine insécable entre les milliers, espace insécable avant l'unité
        return number_format($amount, 0, ',', "\u{202F}") . "\u{00A0}" . config('shop.currency', 'FCFA');
    }
}

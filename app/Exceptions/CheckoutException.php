<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Erreur métier survenue pendant la validation d'une commande.
 * Le message est destiné à être affiché au client.
 */
class CheckoutException extends RuntimeException
{
    public static function emptyCart(): self
    {
        return new self('Votre panier est vide.');
    }

    public static function unavailable(string $productName): self
    {
        return new self("Le produit « {$productName} » n’est plus disponible.");
    }

    public static function insufficientStock(string $productName, int $available): self
    {
        return new self("Stock insuffisant pour « {$productName} » : {$available} disponible(s).");
    }
}

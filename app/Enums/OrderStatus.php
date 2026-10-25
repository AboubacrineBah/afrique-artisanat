<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Confirmed => 'Confirmée',
            self::Shipped => 'Expédiée',
            self::Delivered => 'Livrée',
            self::Cancelled => 'Annulée',
        };
    }

    /** Classe de badge Bootstrap 5 associée au statut. */
    public function badge(): string
    {
        return match ($this) {
            self::Pending => 'text-bg-warning',
            self::Confirmed => 'text-bg-primary',
            self::Shipped => 'text-bg-info',
            self::Delivered => 'text-bg-success',
            self::Cancelled => 'text-bg-secondary',
        };
    }

    /** Statuts vers lesquels la commande peut évoluer depuis le statut courant. */
    public function nextStatuses(): array
    {
        return match ($this) {
            self::Pending => [self::Confirmed, self::Cancelled],
            self::Confirmed => [self::Shipped, self::Cancelled],
            self::Shipped => [self::Delivered],
            self::Delivered, self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->nextStatuses(), true);
    }

    /** Une commande livrée ou annulée ne peut plus être modifiée. */
    public function isFinal(): bool
    {
        return $this->nextStatuses() === [];
    }
}

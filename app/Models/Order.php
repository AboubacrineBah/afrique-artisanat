<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Support\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'number',
        'status',
        'payment_method',
        'payment_status',
        'payment_reference',
        'subtotal',
        'shipping_fee',
        'total',
        'shipping_name',
        'shipping_phone',
        'shipping_city',
        'shipping_address',
        'notes',
        'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_method' => PaymentMethod::class,
            'payment_status' => PaymentStatus::class,
            'subtotal' => 'integer',
            'shipping_fee' => 'integer',
            'total' => 'integer',
            'placed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (blank($order->number)) {
                // Ex. AA-261018-K3F9QZ (préfixe, date AAMMJJ, code aléatoire)
                $order->number = 'AA-' . now()->format('ymd') . '-' . strtoupper(Str::random(6));
            }
            $order->placed_at ??= now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeWithStatus(Builder $query, ?string $status): Builder
    {
        return $query->when($status, fn (Builder $q, string $s) => $q->where('status', $s));
    }

    protected function totalFormatted(): Attribute
    {
        return Attribute::get(fn () => Money::format($this->total));
    }

    protected function subtotalFormatted(): Attribute
    {
        return Attribute::get(fn () => Money::format($this->subtotal));
    }

    protected function shippingFeeFormatted(): Attribute
    {
        return Attribute::get(fn () => Money::format($this->shipping_fee));
    }
}

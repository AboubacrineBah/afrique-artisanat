<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['product_id', 'product_name', 'unit_price', 'quantity', 'line_total'];

    protected function casts(): array
    {
        return [
            'unit_price' => 'integer',
            'quantity' => 'integer',
            'line_total' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** Le produit peut avoir été archivé (soft delete) : on le charge quand même. */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    protected function unitPriceFormatted(): Attribute
    {
        return Attribute::get(fn () => Money::format($this->unit_price));
    }

    protected function lineTotalFormatted(): Attribute
    {
        return Attribute::get(fn () => Money::format($this->line_total));
    }
}

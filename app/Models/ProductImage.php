<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = ['path', 'alt', 'sort_order', 'is_primary'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** Les images de démonstration vivent dans public/images ; les téléversements dans storage/app/public. */
    protected function url(): Attribute
    {
        return Attribute::get(fn () => str_starts_with($this->path, 'images/')
            ? asset($this->path)
            : Storage::disk('public')->url($this->path));
    }
}

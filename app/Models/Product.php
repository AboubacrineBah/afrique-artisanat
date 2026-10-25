<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    /** Tris proposés dans le catalogue (clé => libellé). */
    public const SORTS = [
        'newest' => 'Nouveautés',
        'price_asc' => 'Prix croissant',
        'price_desc' => 'Prix décroissant',
        'name' => 'Nom (A-Z)',
    ];

    protected $fillable = [
        'category_id',
        'artisan_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'material',
        'origin',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    /* ---------- Relations ---------- */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    /** Images triées : image principale d'abord, puis ordre d'affichage. */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /* ---------- Scopes ---------- */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Filtres du catalogue : q, category, artisan, material, min_price, max_price, in_stock, sort.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, function (Builder $q, string $term) {
                $like = '%' . addcslashes($term, '%_\\') . '%';
                $q->where(fn (Builder $w) => $w
                    ->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('material', 'like', $like));
            })
            ->when($filters['category'] ?? null, fn (Builder $q, string $slug) => $q->whereHas('category', fn (Builder $c) => $c->where('slug', $slug)))
            ->when($filters['artisan'] ?? null, fn (Builder $q, string $slug) => $q->whereHas('artisan', fn (Builder $a) => $a->where('slug', $slug)))
            ->when($filters['material'] ?? null, fn (Builder $q, string $material) => $q->where('material', $material))
            ->when(isset($filters['min_price']) && $filters['min_price'] !== '', fn (Builder $q) => $q->where('price', '>=', (int) $filters['min_price']))
            ->when(isset($filters['max_price']) && $filters['max_price'] !== '', fn (Builder $q) => $q->where('price', '<=', (int) $filters['max_price']))
            ->when(! empty($filters['in_stock']), fn (Builder $q) => $q->inStock())
            ->when(true, function (Builder $q) use ($filters) {
                match ($filters['sort'] ?? 'newest') {
                    'price_asc' => $q->orderBy('price'),
                    'price_desc' => $q->orderByDesc('price'),
                    'name' => $q->orderBy('name'),
                    default => $q->latest(),
                };
            });
    }

    /* ---------- Accessors ---------- */

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            $image = $this->images->first();

            return $image ? $image->url : asset('images/placeholder.svg');
        });
    }

    protected function priceFormatted(): Attribute
    {
        return Attribute::get(fn () => Money::format($this->price));
    }

    protected function inStock(): Attribute
    {
        return Attribute::get(fn () => $this->stock > 0);
    }
}

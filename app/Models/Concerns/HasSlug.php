<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Génère un slug unique à partir du champ "name" à la création,
 * et utilise le slug comme clé de route (URL lisibles).
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (blank($model->slug)) {
                $model->slug = static::uniqueSlug($model->name);
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'element';
        $slug = $base;
        $i = 2;

        while (static::slugExists($slug, $ignoreId)) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    protected static function slugExists(string $slug, ?int $ignoreId): bool
    {
        $query = static::query();

        // La contrainte d'unicité en base couvre aussi les lignes supprimées (soft delete)
        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            $query->withTrashed();
        }

        return $query->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

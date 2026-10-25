<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artisan extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['name', 'slug', 'region', 'bio', 'photo_path'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

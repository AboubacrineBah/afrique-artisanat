<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['textiles-bogolan', 'Textiles & bogolan', 'Pagnes, écharpes et coussins en coton, teints et tissés à la main.'],
            ['bijoux', 'Bijoux', 'Colliers, boucles d’oreilles et bracelets inspirés des savoir-faire d’Afrique de l’Ouest.'],
            ['sculptures', 'Sculptures', 'Masques, statuettes et objets sculptés dans le bois.'],
            ['maroquinerie', 'Maroquinerie', 'Sacs, sandales et petits articles en cuir travaillé à la main.'],
            ['poterie-ceramique', 'Poterie & céramique', 'Jarres, bols et vases en terre cuite.'],
            ['decoration', 'Décoration', 'Paniers, tabourets et objets pour la maison.'],
        ];

        foreach ($categories as [$slug, $name, $description]) {
            Category::create(['slug' => $slug, 'name' => $name, 'description' => $description, 'is_active' => true]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /** Produits fictifs, tous illustrés par l'image de substitution public/images/placeholder.svg. */
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');
        $artisans = Artisan::pluck('id', 'slug');

        // [catégorie, artisan, nom, description, prix FCFA, stock, matière, origine, vedette]
        $products = [
            ['textiles-bogolan', 'atelier-bogolan-segou', 'Pagne bogolan ocre et indigo', 'Grand pagne en coton, motifs géométriques dessinés à la main sur fond teint à la boue.', 28000, 8, 'Coton', 'Ségou', true],
            ['textiles-bogolan', 'atelier-bogolan-segou', 'Écharpe en coton tissé à la main', 'Écharpe légère aux rayures naturelles, idéale en toutes saisons.', 9500, 15, 'Coton', 'Ségou', false],
            ['textiles-bogolan', 'atelier-bogolan-segou', 'Coussin bogolan carré', 'Housse de coussin carrée avec fermeture à glissière, motifs ton sur ton.', 12000, 12, 'Coton', 'Ségou', false],

            ['bijoux', 'bijouterie-mopti', 'Collier de perles de verre', 'Collier multicolore monté à la main sur fil résistant.', 7500, 20, 'Verre', 'Mopti', false],
            ['bijoux', 'bijouterie-mopti', 'Boucles d’oreilles en laiton martelé', 'Créoles légères en laiton martelé, finition brossée.', 6000, 18, 'Laiton', 'Mopti', true],
            ['bijoux', 'bijouterie-mopti', 'Bracelet en cuir et cauris', 'Bracelet ajustable en cuir orné de cauris.', 4500, 25, 'Cuir', 'Mopti', false],

            ['sculptures', 'sculpteurs-bandiagara', 'Masque en bois sculpté', 'Masque décoratif sculpté à la main, patine foncée.', 35000, 4, 'Bois', 'Mopti', true],
            ['sculptures', 'sculpteurs-bandiagara', 'Statuette de maternité', 'Statuette en bois représentant une mère et son enfant.', 22000, 6, 'Bois', 'Mopti', false],
            ['sculptures', 'sculpteurs-bandiagara', 'Balafon miniature décoratif', 'Reproduction décorative à poser, lames en bois et calebasses.', 15000, 0, 'Bois', 'Bamako', false],

            ['maroquinerie', 'atelier-cuir-bamako', 'Sac bandoulière en cuir de chèvre', 'Sac souple à bandoulière réglable, cousu main, teinture naturelle.', 32000, 7, 'Cuir', 'Bamako', true],
            ['maroquinerie', 'atelier-cuir-bamako', 'Sandales en cuir tressé', 'Sandales plates en cuir tressé, semelle souple.', 11000, 14, 'Cuir', 'Bamako', false],
            ['maroquinerie', 'atelier-cuir-bamako', 'Portefeuille en cuir teint', 'Portefeuille compact avec compartiments cartes et monnaie.', 8500, 3, 'Cuir', 'Bamako', false],

            ['poterie-ceramique', 'cooperative-poterie-kalabougou', 'Jarre en terre cuite', 'Grande jarre décorative en terre cuite, cuisson traditionnelle.', 14000, 5, 'Terre cuite', 'Ségou', false],
            ['poterie-ceramique', 'cooperative-poterie-kalabougou', 'Set de trois bols en céramique', 'Trois bols assortis, émail mat, passent au lave-vaisselle.', 10500, 10, 'Céramique', 'Ségou', false],
            ['poterie-ceramique', 'cooperative-poterie-kalabougou', 'Vase en terre cuite gravé', 'Vase aux motifs gravés à la main, parfait pour les fleurs séchées.', 9000, 9, 'Terre cuite', 'Ségou', false],

            ['decoration', 'vannerie-sikasso', 'Panier en raphia tressé', 'Panier rond avec anses, utile pour le linge, les courses ou la décoration.', 6500, 22, 'Raphia', 'Sikasso', true],
            ['decoration', 'sculpteurs-bandiagara', 'Tabouret en bois sculpté', 'Tabouret d’une seule pièce, assise creusée et pieds sculptés.', 18000, 6, 'Bois', 'Mopti', false],
            ['decoration', 'vannerie-sikasso', 'Calebasse décorée', 'Calebasse séchée, décor gravé et brûlé à la main.', 5500, 16, 'Calebasse', 'Sikasso', false],
        ];

        foreach ($products as [$category, $artisan, $name, $description, $price, $stock, $material, $origin, $featured]) {
            $product = Product::create([
                'category_id' => $categories[$category],
                'artisan_id' => $artisans[$artisan],
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'material' => $material,
                'origin' => $origin,
                'is_active' => true,
                'is_featured' => $featured,
            ]);

            $product->images()->create([
                'path' => 'images/placeholder.svg',
                'alt' => $name,
                'sort_order' => 1,
                'is_primary' => true,
            ]);
        }
    }
}

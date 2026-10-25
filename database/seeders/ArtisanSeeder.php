<?php

namespace Database\Seeders;

use App\Models\Artisan;
use Illuminate\Database\Seeder;

class ArtisanSeeder extends Seeder
{
    /** Ateliers fictifs créés pour la démonstration. */
    public function run(): void
    {
        $artisans = [
            ['atelier-bogolan-segou', 'Atelier Bogolan de Ségou', 'Ségou', 'Atelier fictif spécialisé dans les textiles teints à la boue et les tissages en coton.'],
            ['cooperative-poterie-kalabougou', 'Coopérative des Potières', 'Ségou', 'Coopérative fictive de potières qui façonnent jarres, bols et vases en terre cuite.'],
            ['bijouterie-mopti', 'Bijouterie de Mopti', 'Mopti', 'Atelier fictif de bijoux en laiton, perles de verre et cuir.'],
            ['atelier-cuir-bamako', 'Atelier du Cuir de Bamako', 'Bamako', 'Maroquinerie fictive : sacs, sandales et portefeuilles cousus main.'],
            ['sculpteurs-bandiagara', 'Sculpteurs de Bandiagara', 'Mopti', 'Collectif fictif de sculpteurs sur bois : masques, statuettes et tabourets.'],
            ['vannerie-sikasso', 'Vannerie de Sikasso', 'Sikasso', 'Atelier fictif de vannerie : paniers en raphia et calebasses décorées.'],
        ];

        foreach ($artisans as [$slug, $name, $region, $bio]) {
            Artisan::create(['slug' => $slug, 'name' => $name, 'region' => $region, 'bio' => $bio]);
        }
    }
}

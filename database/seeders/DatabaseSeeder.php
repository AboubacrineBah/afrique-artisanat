<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Ordre important : utilisateurs, catalogue, puis commandes de démonstration. */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ArtisanSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
        ]);
    }
}

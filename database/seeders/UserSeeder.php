<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** Comptes de démonstration (mot de passe : "password"). À ne jamais utiliser en production. */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Administrateur',
            'email' => 'admin@afriqueartisanat.test',
        ]);

        User::factory()->create([
            'name' => 'Client Démo',
            'email' => 'client@afriqueartisanat.test',
            'phone' => '+223 00 00 00 00',
        ]);

        User::factory()->count(3)->create();
    }
}

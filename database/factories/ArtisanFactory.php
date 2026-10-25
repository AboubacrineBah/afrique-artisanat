<?php

namespace Database\Factories;

use App\Models\Artisan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Artisan>
 */
class ArtisanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Atelier ' . fake()->unique()->lastName(),
            'region' => fake()->randomElement(['Bamako', 'Ségou', 'Mopti', 'Sikasso', 'Kayes']),
            'bio' => fake()->paragraph(),
        ];
    }
}

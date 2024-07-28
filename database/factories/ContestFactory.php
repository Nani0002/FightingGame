<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $herotype = fake()->randomElement(["melee", "ranged", "special"]);
        $enemytype = fake()->randomElement(["melee", "ranged", "special"]);
        $herodmg = fake()->numberBetween(0, 20);
        $enemydmg = fake()->numberBetween(0, 20);
        return [
            'win' => fake()->boolean(),
            'history' => "HERO_NAME: {$herotype} - {$herodmg} damage|ENEMY_NAME: {$enemytype} - {$enemydmg} damage"
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filename = fake()->boolean() ? 'city.png' : 'field.png';
        return [
            'name' => fake()->city(),
            'imagename' => $filename,
            'imagename_hash' => $filename,
        ];
    }
}

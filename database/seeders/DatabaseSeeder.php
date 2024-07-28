<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\PlaceFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            "name" => "Józsi",
            "email" => "email@gmail.com",
            "password" => "password",
            "admin" => true
        ]);

        User::factory()->create([
            "name" => "Józsi2",
            "email" => "email2@gmail.com",
            "password" => "palacsinta",
            "admin" => true
        ]);

        User::factory()->create([
            "name" => "nemadmin",
            "email" => "nemadmin@gmail.com",
            "password" => "nemadmin",
        ]);

        User::factory(4)->create();

        $this->call([
            PlaceSeeder::class,
            CharacterSeeder::class,
            ContestSeeder::class,
        ]);

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}

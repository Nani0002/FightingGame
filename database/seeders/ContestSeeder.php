<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            $place = Place::all()->random();
            $hero = Character::all()->where("enemy", false)->where("user_id", $user->id)->random();
            $enemy = Character::all()->where("enemy", true)->random();
            $contest = Contest::factory()
                ->for($user)
                ->for($place)
                ->create();
            $win = $contest->win;
            if ($win == true) {
                $contest->characters()->attach([
                    $hero->id => ["enemy_hp" => 0],
                    $enemy->id => ["enemy_hp" => 0]
                ]);
            } else {
                $contest->characters()->attach([
                    $hero->id => ["hero_hp" => 0],
                    $enemy->id => ["hero_hp" => 00]
                ]);
            }
        }
    }
}

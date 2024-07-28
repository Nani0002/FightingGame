<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        foreach($users as $user){
            if($user->admin != true){
                Character::factory(3)
                    ->for($user)
                    ->create();
            }
            else{
                Character::factory(2)
                    ->for($user)
                    ->create();
                Character::factory()
                    ->for($user)
                    ->create(["enemy" => true]);
            }
        }
    }
}

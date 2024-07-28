<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $hero = Auth::user()->characters()->where("id", $request->character_id)->first()->id;
        if (!$hero) {
            abort(404);
        }

        $enemies = Character::all()->where("user_id", "!=", Auth::id())->where("enemy", true);
        if($enemies->count() == 0){
            return redirect()->route('characters.show', ["character" => $hero]);
        }
        $enemy = $enemies->random()->id;

        $contest = new Contest;
        $contest->win = null;
        $contest->history = " ";
        $contest->user_id = Auth::id();
        $contest->place_id = Place::all()->random()->id;
        $contest->save();

        $contest->characters()->attach([$hero, $enemy]);

        return \redirect()->route('contests.show', ['contest' => $contest->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contest = Contest::all()->where('id', $id)->first();
        $place = $contest->place()->first();
        $enemy = $contest->characters()->where('enemy', true)->first();
        $hero = $contest->characters()->where('enemy', false)->first();
        return \view()->make('contests.contest', ["contest" => $contest, "place" => $place, "enemy" => $enemy, "hero" => $hero]);
    }

    /**
     * Player makes an attack
     */
    public function attack(string $id, string $attackType)
    {
        $contest = Contest::all()->where('id', $id)->first();

        $enemy = $contest->characters()->where('enemy', true)->first();
        $hero = $contest->characters()->where('enemy', false)->first();
        $ATT = [
            "HP" => $hero->pivot->hero_hp,
            "DEFENCE" => $hero->defence,
            "STRENGTH" => $hero->strength,
            "ACCURACY" => $hero->accuracy,
            "MAGIC" => $hero->magic,
        ];
        $DEF = [
            "HP" => $enemy->pivot->enemy,
            "DEFENCE" => $enemy->defence,
            "STRENGTH" => $enemy->strength,
            "ACCURACY" => $enemy->accuracy,
            "MAGIC" => $enemy->magic,
        ];

        $dmg = $this->calculate($attackType, $ATT, $DEF);
        $enemy_hp = $enemy->pivot->enemy_hp - $dmg <= 0 ? 0 : $enemy->pivot->enemy_hp - $dmg;

        if (strlen($contest->history) == 1) {
            $contest->history = "{$hero->name}: {$attackType} attack - {$dmg} damage";
        } else {
            $contest->history = "{$contest->history}|{$hero->name}: {$attackType} attack - {$dmg} damage";
        }

        $hero_hp = $hero->pivot->hero_hp;
        if ($enemy_hp > 0) {
            $DEF = [
                "DEFENCE" => $hero->defence,
                "STRENGTH" => $hero->strength,
                "ACCURACY" => $hero->accuracy,
                "MAGIC" => $hero->magic,
            ];
            $ATT = [
                "DEFENCE" => $enemy->defence,
                "STRENGTH" => $enemy->strength,
                "ACCURACY" => $enemy->accuracy,
                "MAGIC" => $enemy->magic,
            ];

            $enemyAttackType = \fake()->numberBetween(0, 2);
            switch ($enemyAttackType) {
                case 0:
                    $dmg = $this->calculate('melee', $ATT, $DEF);
                    $contest->history = "{$contest->history}|{$enemy->name}: melee attack - {$dmg} damage";
                    break;
                case 1:
                    $dmg = $this->calculate('ranged', $ATT, $DEF);
                    $contest->history = "{$contest->history}|{$enemy->name}: ranged attack - {$dmg} damage";
                    break;
                case 2:
                    $dmg = $this->calculate('special', $ATT, $DEF);
                    $contest->history = "{$contest->history}|{$enemy->name}: special attack - {$dmg} damage";
                    break;
            }
            $hero_hp = $hero_hp - $dmg <= 0 ? 0 : $hero_hp - $dmg;
        }

        $contest->characters()->updateExistingPivot($hero->id, [
            'hero_hp' => $hero_hp,
            'enemy_hp' => $enemy_hp
        ]);
        $contest->characters()->updateExistingPivot($enemy->id, [
            'hero_hp' => $hero_hp,
            'enemy_hp' => $enemy_hp
        ]);

        if ($hero_hp <= 0) {
            $contest->win = false;
            $contest->update();
        } else if ($enemy_hp <= 0) {
            $contest->win = true;
            $contest->update();
        } else {
            $contest->update();
        }

        return \redirect()->route('contests.show', ["contest" => $id]);
    }

    /**
     * Calculate the attack
     */
    public function calculate(string $mode, array $ATT, array $DEF): float
    {
        $dmg = 0;
        switch ($mode) {
            case 'melee':
                $dmg = (($ATT["STRENGTH"] * 0.7 + $ATT["ACCURACY"] * 0.1 + $ATT["MAGIC"] * 0.1) - $DEF["DEFENCE"]);
                break;
            case 'ranged':
                $dmg = (($ATT["STRENGTH"] * 0.1 + $ATT["ACCURACY"] * 0.7 + $ATT["MAGIC"] * 0.1) - $DEF["DEFENCE"]);
                break;
            case 'special':
                $dmg = (($ATT["STRENGTH"] * 0.1 + $ATT["ACCURACY"] * 0.1 + $ATT["MAGIC"] * 0.7) - $DEF["DEFENCE"]);
                break;
            default:
                abort(404);
        }
        if ($dmg <= 0) {
            return 0;
        }
        return $dmg;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

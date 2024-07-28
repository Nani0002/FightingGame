<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \view()->make('characters.characters', ["characters" => Auth::user()->characters()->get(), "admin" => Auth::user()->admin]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return \view()->make('characters.character_form', ["admin" => Auth::user()->admin]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "name" => "required|string",
            "defence" => "required|integer",
            "strength" => "required|integer",
            "accuracy" => "required|integer",
            "magic" => "required|integer",
        ]);

        $validator->after(function($validator) use ($request) {
            $sum = $request["defence"] + $request["strength"] + $request["accuracy"] + $request["magic"];
            if($sum > 20){
                $validator->errors()->add("too_powerful", "A karakterképességek összege maghaladja a 20 pontot!");
            }

            if($request["defence"] < 0){
                $validator->errors()->add("invalid_defence", "A védelemnek 0-nál nagyobb egyenlőnek kell lennie!");
            }
            if($request["defence"] > 3){
                $validator->errors()->add("invalid_defence", "A védelem maghaladja a maximális 3 pontot!");
            }

            if($request["strength"] < 0){
                $validator->errors()->add("invalid_strength", "Az erőnek 0-nál nagyobb egyenlőnek kell lennie!");
            }
            if($request["strength"] > 20){
                $validator->errors()->add("invalid_strength", "Az erő maghaladja a maximális 20 pontot!");
            }

            if($request["accuracy"] < 0){
                $validator->errors()->add("invalid_accuracy", "A pontosságnak 0-nál nagyobb egyenlőnek kell lennie!");
            }
            if($request["accuracy"] > 20){
                $validator->errors()->add("invalid_accuracy", "A pontosság maghaladja a maximális 20 pontot!");
            }

            if($request["magic"] < 0){
                $validator->errors()->add("invalid_magic", "A varázserőnek 0-nál nagyobb egyenlőnek kell lennie!");
            }
            if($request["magic"] > 20){
                $validator->errors()->add("invalid_magic", "A varázserő maghaladja a maximális 20 pontot!");
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $validated = $validator->validated();
        $validated["user_id"] = Auth::id();
        $validated["enemy"] = $request->has('enemy');
        $character = Character::create($validated);

        return \redirect()->route("characters.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $character = Auth::user()->characters()->with([
            'contests' => function ($i) {
                $i->with(['place', 'characters']);
            }
        ])->findOrFail($id);
        if (!$character) {
            abort(404);
        }
        $contests = $character->contests;
        return view()->make('characters.character', ["character" => $character, "contests" => $contests, "admin" => Auth::user()->admin]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $character = Auth::user()->characters()->where("id", $id)->first();
        if(!$character){
            abort(404);
        }
        return \view()->make('characters.character_form', ["character" => $character, "admin" => Auth::user()->admin]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            "name" => "required|string",
            "defence" => "required|integer",
            "strength" => "required|integer",
            "accuracy" => "required|integer",
            "magic" => "required|integer",
        ]);
        $validator->after(function($validator) use ($request) {
            $sum = $request["defence"] + $request["strength"] + $request["accuracy"] + $request["magic"];
            if($sum > 20){
                $validator->errors()->add("too_powerful", "A karakterképességek összege maghaladja a 20 pontot!");
            }

            if($request["defence"] < 0){
                $validator->errors()->add("invalid_defence", "A védelemnek 0-nál nagyobbnak kell lennie!");
            }
            if($request["defence"] > 3){
                $validator->errors()->add("invalid_defence", "A védelem maghaladja a maximális 3 pontot!");
            }

            if($request["strength"] < 0){
                $validator->errors()->add("invalid_strength", "Az erőnek 0-nál nagyobbnak kell lennie!");
            }
            if($request["strength"] > 20){
                $validator->errors()->add("invalid_strength", "Az erő maghaladja a maximális 20 pontot!");
            }

            if($request["accuracy"] < 0){
                $validator->errors()->add("invalid_accuracy", "A pontosságnak 0-nál nagyobbnak kell lennie!");
            }
            if($request["accuracy"] > 20){
                $validator->errors()->add("invalid_accuracy", "A pontosság maghaladja a maximális 20 pontot!");
            }

            if($request["magic"] < 0){
                $validator->errors()->add("invalid_magic", "A varázserőnek 0-nál nagyobbnak kell lennie!");
            }
            if($request["magic"] > 20){
                $validator->errors()->add("invalid_magic", "A varázserő maghaladja a maximális 20 pontot!");
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $character = Auth::user()->characters()->where('characters.id', $id)->first();
        if(!$character){
            \abort(404);
        }

        $validated = $validator->validated();

        $character->update($validated);

        return \redirect()->route('characters.show', ['character' => $character->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $character = Auth::user()->characters()->where("id", $id)->first();
        if (!$character) {
            abort(404);
        }

        $character->delete();

        return \redirect()->route("characters.index");
    }
}

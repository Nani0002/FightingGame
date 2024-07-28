<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->admin) {
            return \redirect()->route('characters.index');
        }
        return \view()->make('places.places', ["places" => Place::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->admin) {
            return \redirect()->route('characters.index');
        }
        return \view()->make('places.place_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->admin) {
            return \redirect()->route('characters.index');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
            'file' => 'required|file'
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->hasFile('file')) {
                $extension = $request->file('file')->getClientOriginalExtension();
                if ($extension != 'png' && $extension != 'jpg' && $extension != 'jpeg') {
                    $validator->errors()->add("invalid_file", "Nem megfelelő fájl kiterjesztés! Csak .png, .jpg és .jpeg fájlok fogadhatóak el!");
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $path = $request->file('file')->store('public');
        $filenamehash = \basename($path);
        $filename = $request->file('file')->getClientOriginalName();

        $place = new Place;
        $place->name = $request->name;
        $place->imagename = $filename;
        $place->imagename_hash = $filenamehash;

        $place->save();


        return \redirect()->route('places.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::user()->admin) {
            return \redirect()->route('characters.index');
        }
        $place = Place::all()->where("id", $id)->first();
        if (!$place) {
            abort(404);
        }
        return \view()->make("places.place_form", ["place" => $place]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::user()->admin) {
            return \redirect()->route('characters.index');
        }

        $place = Place::all()->where("id", $id)->first();
        if (!$place) {
            abort(404);
        }

        $request->validate([
            'name' => 'string|required',
        ]);

        $place->name = $request->name;

        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists($place->imagename_hash) && $place->imagename_hash != 'city.png' && $place->imagename_hash != 'field.png') {
                Storage::disk('public')->delete($place->imagename_hash);
            }
            $path = $request->file('file')->store('public');
            $place->imagename_hash = \basename($path);
            $place->imagename = $request->file('file')->getClientOriginalName();
        }
        $place->update();

        return \redirect()->route('places.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::user()->admin) {
            return \redirect()->route('characters.index');
        }

        $place = Place::all()->where("id", $id)->first();
        if (!$place) {
            abort(404);
        }

        if (Storage::disk('public')->exists($place->imagename_hash) && $place->imagename_hash != 'city.png' && $place->imagename_hash != 'field.png') {
            Storage::disk('public')->delete($place->imagename_hash);
        }

        $place->delete();

        return \redirect()->route("places.index");
    }
}

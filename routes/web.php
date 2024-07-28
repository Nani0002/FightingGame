<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\PlaceController;
use App\Models\Character;
use App\Models\Contest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index', ["ChCount" => Character::count(), "CoCount" => Contest::count()]);
});

Route::middleware('auth')->group(function () {
    Route::resource('characters', CharacterController::class);
    Route::resource('places', PlaceController::class);
    Route::resource('contests', ContestController::class);
    Route::get('contests/{id}/{attackType}', [ContestController::class, "attack"])->name('contests.attack');
});

require __DIR__.'/auth.php';

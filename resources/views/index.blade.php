@extends('layouts.layout')

@section('title', 'Főoldal')

@section('content')

    <p class="px-5 py-2"> Ez a játék egy egyszerűsített egy játékos módú, körökre osztott, arcade típusú harcolós játék, ami laravelben van
        írva. Ha be vagy jelentkezve, el tudod érni a saját karaktereidet, sőt még csinálni is tudsz újat, ha nagyon
        szeretnéd még akár módosításra és törlésre is van lehetőség! Ha szeretnéd, hogy egy karaktered valakivel
        harcolhasson, tudsz véletlenszerűen választani az adminok által biztosított ellenfelek közül, ám te sajnos ilyet nem hozhatsz létre...
        (Hacsak nem vagy egy admin!). Esetleg ha admin vagy, akkor helyet is csinálatsz, ahol a meccsek fognak történni!
        <br>
        <br>
        Jelenleg {{ $ChCount }} darab karakter van, és {{ $CoCount }} darab meccset játszottak le!
    </p>


@endsection

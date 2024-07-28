@extends('layouts.layout')

@section('title')
    {{ $contest->win == null ? 'HARC!' : ($contest->win ? 'Győzelem' : 'Vereség') }}
@endsection

@section('image')
    {{ asset('storage/' . $place->imagename_hash) }}
@endsection


@section('content')
    <div class='row'>
        <div class="col-sm-3 mb-3 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $hero->name }}</h5>
                    <p class="card-text">
                    <ul style="list-style: none">
                        <li>Védelem: {{ $hero->defence }}</li>
                        <li>Erő: {{ $hero->strength }}</li>
                        <li>Pontosság: {{ $hero->accuracy }}</li>
                        <li>Varázserő: {{ $hero->magic }}</li>
                        <li>Életerő: <span
                                class="{{ $hero->pivot->hero_hp >= 10 ? 'text-success' : ($hero->pivot->hero_hp >= 3 ? 'text-warning' : 'text-danger') }}">{{ $hero->pivot->hero_hp }}</span>
                        </li>
                    </ul>
                    </p>
                    @if ($contest->win === null && $hero->user_id == Auth::id())
                        <a href="{{ route('contests.attack', ['id' => $contest->id, 'attackType' => 'melee']) }}"
                            class="btn btn-primary">Melee</a>
                        <a href="{{ route('contests.attack', ['id' => $contest->id, 'attackType' => 'ranged']) }}"
                            class="btn btn-primary">Ranged</a>
                        <a href="{{ route('contests.attack', ['id' => $contest->id, 'attackType' => 'special']) }}"
                            class="btn btn-primary">Special</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-4 offset-sm-1 mb-3 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $contest->win === null ? 'History' : ($contest->win === true ? 'Győzelem' : 'Vereség') }} |
                        {{ $place->name }}</h5>
                    <p class="card-text">
                        @foreach (explode('|', $contest->history) as $history)
                            {{ $history }}<br>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-3 offset-sm-1 mb-3 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $enemy->name }}</h5>
                    <p class="card-text">
                    <ul style="list-style: none">
                        <li>Védelem: {{ $enemy->defence }}</li>
                        <li>Erő: {{ $enemy->strength }}</li>
                        <li>Pontosság: {{ $enemy->accuracy }}</li>
                        <li>Varázserő: {{ $enemy->magic }}</li>
                        <li>Életerő: <span
                                class="{{ $enemy->pivot->enemy_hp >= 10 ? 'text-success' : ($enemy->pivot->enemy_hp >= 3 ? 'text-warning' : 'text-danger') }}">{{ $enemy->pivot->enemy_hp }}</span>
                        </li>
                    </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

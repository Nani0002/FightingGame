@extends('layouts.layout')

@section('title', $character->name. ' részletei')

@section('content')
    <h2>{{ $character->name }}</h2>


    <table class="table table-striped">
        <tr>
            <td>
                <form action={{ route('characters.destroy', ['character' => $character->id]) }} method="post">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger">
                        Törlés
                    </button>
                </form>
            </td>
            <td>
                <a href={{ route('characters.edit', ['character' => $character->id]) }} class="btn btn-info">Szerkesztés</a>
            </td>
        </tr>
        @if ($admin)
            <tr>
                <th>Ellenfél</th>
                <td>{{ $character->enemy ? 'Igen' : 'Nem' }}</td>
            </tr>
        @endif
        <tr>
            <th>Védelem</th>
            <td>{{ $character->defence }}</td>
        </tr>
        <tr>
            <th>Erő</th>
            <td>{{ $character->strength }}</td>
        </tr>
        <tr>
            <th>Pontosság</th>
            <td>{{ $character->accuracy }}</td>
        </tr>
        <tr>
            <th>Varázserő</th>
            <td>{{ $character->magic }}</td>
        </tr>
    </table>
    <h2 class="mt-3">Mérkőzések</h2>
    <table class="table table-striped">
        @if (!$character->enemy)
            <tr>
                <td colspan="2">
                    <form method="post" action="{{ route('contests.store', ['character' => $character->id]) }}">
                        @csrf
                        <input type="hidden" name="character_id" id="character_id" value='{{ $character->id }}'>
                        <button class="btn btn-success">Új mérkőzés</button>
                    </form>
                </td>
            </tr>
        @endif
        @foreach ($contests as $key => $contest)
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <a href={{ route('contests.show', ['contest' => $contest->id]) }} class="btn btn-info">Mérkőzés
                        {{ $key + 1 }}</a>
                </td>
            </tr>
            <tr>
                <th>Helyszín</th>
                <td>{{ $contest->place->name }}</td>
            </tr>
            <tr>
                <th>Ellenfél</th>
                <td>{{ $contest->characters->where('enemy', '!=', $character->enemy)->first()->name }}</td>
            </tr>
        @endforeach
    </table>
@endsection

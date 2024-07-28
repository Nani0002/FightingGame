@extends('layouts.layout')

@section('title', 'Karakterek')

@section('content')
    <table class="table table-striped">
        <tr>
            <td colspan="{{ ($admin ? 1 : 0) + 8}}">
                <a href={{ route('characters.create') }} class="btn btn-success">Új karakter</a>
            </td>
        </tr>
        <tr>
            <th>Név</th>
            @if ($admin)
                <th>Ellenfél</th>
            @endif
            <th>Védelem</th>
            <th>Erő</th>
            <th>Pontosság</th>
            <th>Varázserő</th>
            <th colspan="3"></th>
        </tr>
        @foreach ($characters as $character)
            <tr>
                <td class="align-middle">{{ $character->name }}</td>
                @if ($admin)
                    <td class="align-middle">{{ $character->enemy ? 'Igen' : 'Nem' }}</td>
                @endif
                <td class="align-middle">{{ $character->defence }}</td>
                <td class="align-middle">{{ $character->strength }}</td>
                <td class="align-middle">{{ $character->accuracy }}</td>
                <td class="align-middle">{{ $character->magic }}</td>
                <td class="align-middle"><a href={{ route('characters.show', ['character' => $character->id]) }}
                        class="btn btn-info">Részletek</a></td>
                <td class="align-middle"><a href={{ route('characters.edit', ['character' => $character->id]) }}
                        class="btn btn-warning">Szerkesztés</a></td>
                <td class="align-middle">
                    <form action={{ route('characters.destroy', ['character' => $character->id]) }} method="post">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger">
                            Törlés
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection

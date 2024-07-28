@extends('layouts.layout')

@section('title', !isset($character) ? 'Új karakter' : $character->name . ' szerkesztése')

@section('content')
    <form
        action="{{ isset($character) ? route('characters.update', ['character' => $character->id]) : route('characters.store') }}"
        method="POST">
        @csrf
        @isset($character)
            @method('patch')
        @endisset
        @error('name')
            <div class="mt-2 text-danger">
                Név megadása kötelező!
            </div>
        @enderror
        <div class="my-3 form-floating">
            <input type="text" name="name" id="name" value="{{ old('name', $character->name ?? '') }}"
                class="form-control" placeholder="Név">
            <label for="name">Név</label>
        </div>
        @if ($admin && !isset($character))
            <input type="checkbox" name="enemy" id="enemy" value="1"
                {{ old('enemy', $character->enemy ?? '') == 1 ? 'checked' : '' }} class="btn-check">
            <label for="enemy" class="btn btn-outline-danger">Ellenfél</label>
        @endif

        @if ($errors->has('too_powerful'))
            <div class="mt-2 text-danger">
                {{ $errors->first('too_powerful') }}
            </div>
        @endif

        @error('defence')
            <div class="mt-2 text-danger">
                Védelem megadása kötelező
            </div>
        @enderror
        @if ($errors->has('invalid_defence'))
            <div class="mt-2 text-danger">
                {{ $errors->first('invalid_defence') }}
            </div>
        @endif
        <div class="my-3 form-floating">
            <input type="text" name="defence" id="defence" value="{{ old('defence', $character->defence ?? '') }}"
                class="form-control" placeholder="Védelem">
            <label for="defence">Védelem</label>
        </div>

        @error('strength')
            <div class="mt-2 text-danger">
                Erő megadása kötelező
            </div>
        @enderror
        @if ($errors->has('invalid_strength'))
            <div class="mt-2 text-danger">
                {{ $errors->first('invalid_strength') }}
            </div>
        @endif
        <div class="my-3 form-floating">
            <input type="text" name="strength" id="strength" value="{{ old('strength', $character->strength ?? '') }}"
                class="form-control" placeholder="Erő">
            <label for="strength">Erő</label>
        </div>

        @error('accuracy')
            <div class="mt-2 text-danger">
                Pontosság megadása kötelező
            </div>
        @enderror
        @if ($errors->has('invalid_accuracy'))
            <div class="mt-2 text-danger">
                {{ $errors->first('invalid_accuracy') }}
            </div>
        @endif
        <div class="my-3 form-floating">
            <input type="text" name="accuracy" id="accuracy" value="{{ old('accuracy', $character->accuracy ?? '') }}"
                class="form-control" placeholder="Pontosság">
            <label for="accuracy">Pontosság</label>
        </div>

        @error('magic')
            <div class="mt-2 text-danger">
                Varázserő megadása kötelező
            </div>
        @enderror
        @if ($errors->has('invalid_magic'))
            <div class="mt-2 text-danger">
                {{ $errors->first('invalid_magic') }}
            </div>
        @endif
        <div class="my-3 form-floating">
            <input type="text" name="magic" id="magic" value="{{ old('magic', $character->magic ?? '') }}"
                class="form-control" placeholder="Varázserő">
            <label for="magic">Varázserő</label>
        </div>

        <input type="submit" value="{{ isset($character) ? 'Szerkesztés' : 'Létrehozás' }}" class="btn btn-success mb-3">
    </form>
@endsection

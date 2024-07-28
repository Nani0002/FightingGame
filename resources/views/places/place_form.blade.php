@extends('layouts.layout')

@section('title', isset($place) ? `$place szerkesztése` : 'Új helyszín')

@section('content')
    <form action="{{ isset($place) ? route('places.update', ['place' => $place->id]) : route('places.store') }}"
        method="post" enctype="multipart/form-data">
        @csrf
        @isset($place)
            @method('patch')
        @endisset
        @error('name')
            <div class="mt-2 text-danger">
                Név megadása kötelező!
            </div>
        @enderror
        <div class="my-3 form-floating">
            <input type="text" name="name" id="name" value="{{ old('name', $place->name ?? '') }}"
                class="form-control" placeholder="Név">
            <label for="name">Név</label>
        </div>
        @error('file')
            <div class="mt-2 text-danger">
                Fájl megadása kötelező!
            </div>
        @enderror
        @if ($errors->has('invalid_file'))
            <div class="mt-2 text-danger">
                {{ $errors->first('invalid_file') }}
            </div>
        @endif
        <div class="my-3">
            <label for="file form-label">Kép</label>
            <input class="form-control" type="file" name="file" id="file">
        </div>
        <button class="btn btn-success my-3">{{ isset($place) ? 'Szerkesztés' : 'Létrehozás' }}</button>
    </form>
@endsection

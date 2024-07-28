@extends('layouts.layout')

@section('title', 'Helyszínek')

@section('content')
    <a href="{{ route('places.create') }}" class="btn btn-success my-2">Új helyszín</a>
    <div class="row">
        @foreach ($places as $place)
            <div class="col-sm-4 mb-3 mt-4">
                <div class="card">
                    <img src="{{ Storage::url($place->imagename_hash) }}" class="card-img-top" alt="{{ $place->name }} helye">
                    <div class="card-body">
                        <h5 class="card-title">{{ $place->name }}</h5>
                        <p class="card-text"><a href="{{ route('places.edit', ['place' => $place->id]) }}"
                                class="btn btn-info">Szerkesztés</a>
                        <form action="{{ route('places.destroy', ['place' => $place->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger">Törlés</button>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

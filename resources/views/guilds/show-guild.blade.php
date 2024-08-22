@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">{{ $guild->name }}</h1>
        <p class="text-center">{{ $guild->description }}</p>

        <div class="container d-flex flex-column align-items-center">
            @if(auth()->user()->hasRole('Admin'))
                <a href=" {{--{{ route('channels.create', ['guild' => $guild->id]) }}--}}" class="btn btn-primary mb-2">Create new Channel</a>
            @endif
            <h3 class="mt-4">Channels</h3>
        </div>
    </div>
@endsection

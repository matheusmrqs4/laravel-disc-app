@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">{{ $guild->name }}</h1>
        <p class="text-center">{{ $guild->description }}</p>

        <div class="container d-flex flex-column align-items-center">
            @if(auth()->user()->hasRole('Admin'))
                <a href=" {{ route('channels.create', ['guild' => $guild->id]) }}" class="btn btn-primary mb-2">Create new Channel</a>
            @endif
            <h3 class="mt-4">Channels</h3>
        </div>
    </div>
    <ul class="list-group">
        <div class="row">
            @foreach($channels as $channel)
                <div class="col-3 text-center">
                    <div class="card shadow-sm m-1">
                        <div class="card-body">
                            <p>{{ $channel->name }}</p>
                            <div class="card-body">
                                <small>{{ $channel->description }}</small>
                                <div class="p-1 mt-2">
                                    <a href="{{ route('channels.show', ['guild' => $guild->id, 'channel' => $channel->id]) }}"
                                       class="btn btn-primary">
                                        Join Channel
                                    </a>
                                </div>
                                @if(auth()->user()->hasRole('Admin'))
                                    <div class="p-1 mt-2">
                                        <a href="{{ route('channels.edit', ['guild' => $guild->id, 'channel' => $channel->id]) }}"
                                           class="btn btn-primary mb-2">
                                            Update Channel
                                        </a>
                                    </div>
                                    <div>
                                        <form method="POST" action="{{ route('channels.delete', ['guild' => $guild->id, 'channel' => $channel->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger mb-2">Delete Channel</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </ul>
@endsection

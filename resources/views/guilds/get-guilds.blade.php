@extends('layouts.app')

@section('content')
    <div class="container d-flex flex-column align-items-center">
        <h1 class="my-4">Guilds</h1>
        <a href="{{ route('guilds.create') }}" class="btn btn-primary mb-2">Create new Guild</a>
        <ul class="list-group w-100">
            @foreach($guilds as $guild)
                <li class="list-group-item card shadow-sm m-1">
                    <p><h3>{{ $guild->name }}</h3></p>
                    <small>{{ $guild->description }}</small>
                    <div class="p-1 mt-2">
                        <a href="{{ route('guilds.show', $guild->id) }}" class="btn btn-primary">
                            Join Guild
                        </a>
                    </div>
                    @if(auth()->user()->hasRole('Admin'))
                        <div class="p-1 mt-2">
                            <a href="{{ route('guilds.edit', $guild->id) }}" class="btn btn-info">
                                Update Guild
                            </a>
                        </div>

                        <form action="{{ route('guilds.destroy', $guild->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete Guild
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection

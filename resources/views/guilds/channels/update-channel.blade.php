@extends('layouts.app')

@if(auth()->user()->hasRole('Admin'))
    @section('content')
        <div class="container flex-column align-items-center my-4">
            <h1 class="text-center ">Update Guild</h1>
            <form action="{{ route('channels.update', ['guild' => $guild->id, 'channel' => $channel->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Channel Name</label>
                    <input type="text" name="name" value="{{ old('name', $channel->name) }}" id="name" class="form-control">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $channel->description) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Update Channel</button>
            </form>
        </div>
    @endsection
@endif

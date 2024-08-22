@extends('layouts.app')

@if(auth()->user()->hasRole('Admin'))
    @section('content')
        <div class="container flex-column align-items-center my-4">
            <h1 class="text-center ">Create a New Channel</h1>
            <form action="{{ route('channels.store', ['guild' => $guild->id]) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Channel Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Create Channel</button>
            </form>
        </div>
    @endsection
@endif

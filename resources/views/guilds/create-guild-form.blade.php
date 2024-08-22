@extends('layouts.app')

@section('content')
    <div class="container flex-column align-items-center my-4">
        <h1 class="text-center ">Create a New Guild</h1>
        <form action="{{ route('guilds.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Guild Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Create Guild</button>
        </form>
    </div>
@endsection

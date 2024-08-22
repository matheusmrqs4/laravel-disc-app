@extends('layouts.app')
    @section('content')
        <div class="container flex-column align-items-center my-4">
            <h1 class="text-center ">Update Guild</h1>
            <form action="{{ route('guild.update', ['guild' => $guild->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Guild Name</label>
                    <input type="text" name="name" value="{{ old('name', $guild->name) }}" id="name" class="form-control">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $guild->description)
                    }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Update Guild</button>
            </form>
        </div>
    @endsection

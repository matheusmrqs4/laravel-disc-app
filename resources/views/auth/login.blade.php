@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-12 col-md-8 col-lg-6">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <fieldset>
                    <legend class="text-center">Login</legend>
                    <div class="mb-3">
                        <label for="email" class="form-label mt-4">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label mt-4">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
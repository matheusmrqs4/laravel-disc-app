@extends('layouts.app')

@section('content')
    <main>
        <input type="hidden" id="channelId" value="{{ $channel->getKey() }}">
        <input type="hidden" id="guildId" value="{{ $channel->guild_id }}">

        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">{{ $channel->name }}</h1>
                    <p class="lead text-body-secondary">
                        {{ $channel->description }}
                    </p>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="row vh-70">
                <div class="col-md-9 d-flex flex-column">
                    <div class="flex-grow-1 overflow-auto p-3" id="channelMessages">
                        <p>
                            <span>{{ date('H:i') }}</span>
                            <span>User joined Channel: {{ auth()->user()->name }}</span>
                        </p>
                    </div>
                    <div class="p-3 border-top">
                        {{-- chat form here --}}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

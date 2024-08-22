<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('channel.{channel}', function (User $user, $channel) {
    return $user->toArray();
});

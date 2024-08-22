<?php

namespace App\Events;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoinedChannelEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private readonly Channel $channel,
        private readonly User $user
    )
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('channel.' . $this->channel->getKey()),
        ];
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
        ];
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'user-joined';
    }
}

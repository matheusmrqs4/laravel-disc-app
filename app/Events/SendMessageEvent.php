<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private readonly Message $message
    ) {
        //
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('channel.' . $this->message->channel_id),
        ];
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'SendMessageEvent';
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->getKey(),
            'message' => $this->message->content,
            'user' => $this->message->member->user->name,
            'sent_at' => $this->message->created_at->format('H:i'),
            'messageId' => $this->message->id,
        ];
    }
}

<?php

namespace App\Services;

use App\Events\DeleteMessageEvent;
use App\Events\SendMessageEvent;
use App\Exceptions\MessageException;
use App\Models\Channel;
use App\Models\Guild;
use App\Models\Member;
use App\Models\Message;
use Illuminate\Support\Facades\Gate;

class MessageService
{
    /**
     * @param Guild $guild
     * @param Channel $channel
     * @param array $message
     * @return Message
     */
    public function sendMessage(Guild $guild, Channel $channel, array $message): Message
    {
        $user = auth()->user();

        $member = Member::where('user_id', $user->id)
                ->where('guild_id', $guild->id)
                ->firstOrFail();

        $payload = [
            'content' => $message['content'],
            'member_id' => $member->getKey(),
        ];

        $message = $channel->messages()->create($payload);

        event(new SendMessageEvent($message));

        return $message;
    }

    public function deleteMessage(Guild $guild, Channel $channel, int $messageId): void
    {
        $message = $channel->messages()->where('id', $messageId)->firstOrFail();

        $user = auth()->user();

        if ($message->member->user_id !== $user->id && !Gate::allows('manageMessages', $guild)) {
            throw MessageException::dontHavePermission();
        }

        event(new DeleteMessageEvent($message));

        $message->delete();
    }
}
<?php

namespace App\Services;

use App\Exceptions\ChannelException;
use App\Models\Channel;
use App\Models\Guild;
use Illuminate\Support\Facades\Gate;

class ChannelService
{
    /**
     * @param array $attributes
     * @param Guild $guild
     * @return Channel
     * @throws ChannelException
     */
    public function createChannel(array $attributes, Guild $guild): Channel
    {
        if (!Gate::allows('manageChannels', $guild)) {
            throw ChannelException::dontHavePermission();
        }

        $attributes['guild_id'] = $guild->id;

        $channel = Channel::create($attributes);
        $channel->save();

        return $channel;
    }

    /**
     * @param array $attributes
     * @param Channel $channel
     * @return Channel
     * @throws ChannelException
     */
    public function updateChannel(array $attributes, Channel $channel): Channel
    {
        $guild = $channel->guild;

        if (!Gate::allows('manageChannels', $guild)) {
            throw ChannelException::dontHavePermission();
        }

        $channel->update($attributes);

        return $channel;
    }

    /**
     * @param Channel $channel
     * @return void
     * @throws ChannelException
     */
    public function deleteChannel(Channel $channel): void
    {
        $guild = $channel->guild;

        if (!Gate::allows('manageChannels', $guild)) {
            throw ChannelException::dontHavePermission();
        }

        $channel->delete();
    }
}

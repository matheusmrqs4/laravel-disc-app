<?php

namespace App\Services;

use App\Enums\GuildMemberRole;
use App\Exceptions\GuildException;
use App\Models\Guild;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class GuildService
{
    /**
     * @return Collection
     */
    public function getAllGuilds(): Collection
    {
        return Guild::all();
    }

    /**
     * @param Guild $guild
     * @return Guild
     */
    public function getGuildById(Guild $guild): Guild
    {
        $userId = auth()->id();

        if(!$guild->members()->where('user_id', $userId)->exists()) {
            $guild->members()->attach($userId, ['role' => GuildMemberRole::Member]);
        }

        return $guild;
    }

    /**
     * @param Guild $guild
     * @return Collection
     * @throws GuildException
     */
    public function getAllGuildChannels(Guild $guild): Collection
    {
        if (!Gate::allows('view', $guild)) {
            throw GuildException::dontHavePermission();
        }

        return $guild->channels()->get();
    }

    /**
     * @param array $attributes
     * @return Guild
     */
    public function createGuild(array $attributes): Guild
    {
        $attributes['user_id'] = auth()->id();

        $guild = Guild::create($attributes);
        $guild->save();

        $guild->members()->attach(auth()->id(), ['role' => GuildMemberRole::Admin]);

        return $guild;
    }

    /**
     * @param Guild $guild
     * @param array $attributes
     * @return Guild
     * @throws GuildException
     */
    public function updateGuild(Guild $guild, array $attributes): Guild
    {
        if (!Gate::allows('updateAndDelete', $guild)) {
            throw GuildException::dontHavePermission();
        }

        $guild->update($attributes);

        return $guild;
    }

    /**
     * @param Guild $guild
     * @return void
     * @throws GuildException
     */
    public function deleteGuild(Guild $guild): void
    {
        if (!Gate::allows('updateAndDelete', $guild)) {
            throw GuildException::dontHavePermission();
        }

        $guild->delete();
    }
}

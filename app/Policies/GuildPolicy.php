<?php

namespace App\Policies;

use App\Enums\GuildMemberRole;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GuildPolicy
{
    use HandlesAuthorization;

    public function updateAndDelete(User $user, Guild $guild): bool
    {
        return $this->isAdmin($user, $guild);
    }

    public function manageChannels(User $user, Guild $guild): bool
    {
        return $this->isAdmin($user, $guild);
    }

    public function manageMessages(User $user, Guild $guild): bool
    {
        return $this->isAdmin($user, $guild);
    }

    public function view(User $user, Guild $guild): bool
    {
        return $guild->members->contains($user->id);
    }

    protected function isAdmin(User $user, Guild $guild): bool
    {
        return $guild->members()
            ->where('user_id', $user->id)
            ->where('role', GuildMemberRole::Admin)
            ->exists();
    }
}

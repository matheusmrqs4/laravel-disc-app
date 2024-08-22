<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return BelongsToMany
     */
    public function guilds(): BelongsToMany
    {
        return $this->belongsToMany(Guild::class, 'members')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * @param string $role
     * @param Guild|null $guild
     * @return bool
     */
    public function hasRole(string $role, Guild $guild = null): bool
    {
        $query = $this->guilds();

        if ($guild) {
            $query->where('guild_id', $guild->id);
        }

        return $query->wherePivot('role', $role)->exists();
    }

    public function isNotMemberOf(Guild $guild): bool
    {
        return !$this->guilds->contains($guild);
    }
}

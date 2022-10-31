<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slack_id',
        'name',
        'nickname',
        'avatar',
    ];

    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function votes(): BelongsToMany
    {
        return $this->belongsToMany(Poll::class, 'votes');
    }

    public function getAvatar(): string
    {
        return $this->avatar ?? 'https://www.gravatar.com/avatar/'
            .md5($this->name)
            .'?s=200'
            .'&d=robohash';
    }

    public function isAdmin(): bool
    {
        return in_array($this->slack_id, [
            'U0203LX28H0', // Nick Pratley - Dev
            'UTLF4FBEG', // Nick Pratley - beer4peer
        ]);
    }

    public function canAccessFilament(): bool
    {
        return $this->isAdmin();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getAvatar();
    }
}

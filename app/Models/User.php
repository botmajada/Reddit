<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isTrusted()
    {
        return $this->trusted;
    }

    public function user() {
        return $this->belongsToMany(CommunityLink::class, 'community_link_users');
    }

    public function vote() {
        return $this->belongsToMany(CommunityLink::class, 'community_link_users')->withTimestamps();
    }

    public function hasVotedFor(CommunityLink $communityLink)
    {
        return $this->communityLinks->contains($communityLink);
    }

    public function votedFor(CommunityLink $link) {
        return $this->vote->contains($link);
    }

    public function removeVote($user)
    {
        $this->vote()
             ->where('user_id', $user->id)
             ->delete();
        $this->decrement('votes_count');
    }
}
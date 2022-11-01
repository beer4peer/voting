<?php

namespace App\Models;

use App\Exceptions\DuplicateVoteException;
use App\Notifications\PollCreated;
use Cviebrock\EloquentSluggable\Sluggable;
use DivisionByZeroError;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

/**
 * @mixin IdeHelperPoll
 */
class Poll extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = [];
    protected $perPage = 10;

    protected $casts = [
        'ends_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'status_id',
        'ends_at'
    ];

    public static function booted()
    {
        // Set some sane defaults on create
        static::creating(function (Poll $poll) {
            $poll->votes_yes = 0;
            $poll->votes_no = 0;
        });

        static::created(function (Poll $poll) {
            // Send a notification to Slack.
            if($webhook = config('services.slack.notification_channel', false)) {
                Notification::route('slack', $webhook)->notify(new PollCreated($poll));
            }
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function votes()
    {
        return $this->belongsToMany(User::class, 'votes');
    }

    public function isVotedByUser(?User $user)
    {
        if (!$user) {
            return false;
        }

        return Vote::where('user_id', $user->id)
            ->where('poll_id', $this->id)
            ->exists();
    }

    public function voteYes(?User $user)
    {
        try {
            if($this->vote($user)) {
                $this->increment('votes_yes');
            }
        } catch (DuplicateVoteException $e) {
            //
        }
    }

    public function voteNo(?User $user)
    {
        try {
            if($this->vote($user)) {
                $this->increment('votes_no');
            }
        } catch (DuplicateVoteException $e) {
            //
        }
    }

    public function vote(?User $user): bool
    {
        // If we don't have a user, we can't vote on the poll
        if (!$user) {
            return false;
        }

        // We store that the user _has_ voted on this poll to stop duplicates,
        // but we do not store the actual vote against the user.

        if ($this->isVotedByUser($user)) {
            throw new DuplicateVoteException;
        }
        return \DB::transaction(function () use ($user) {
            /** @var Vote $vote */
            $vote = Vote::create([
                'poll_id' => $this->id,
                'user_id' => $user->id,
            ]);

            // This comment is used for the timeline.
            Comment::create([
                'poll_id' => $this->id,
                'user_id' => $user->id,
                'status_id' => $this->status_id,
                'body' => '-',
                'is_voting' => true,
            ]);

            return $vote->exists;
        });
    }

    public function asPercent(): float
    {
        try {
            return $this->votes_no / ($this->votes_yes + $this->votes_no) * 100;
        } catch (DivisionByZeroError $e) {
            return 0;
        }
    }

    public function openForVoting(): bool
    {
        // If the status is Upcoming then lets not allow voting
        if($this->status_id === Status::query()->where('name', 'Upcoming')->first()->id) {
            return false;
        }

        // If the status is Open, and the end date is in the future, we're open for voting
        if($this->ends_at && $this->ends_at->isFuture()) {
            return true;
        }

        if($this->status_id === Status::query()->where('name', 'Open')->first()->id) {
            return true;
        }

        return false;
    }
}

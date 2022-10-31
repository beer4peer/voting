<?php

namespace App\Http\Livewire;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
use App\Http\Livewire\Traits\WithAuthRedirects;
use App\Models\Poll;
use Livewire\Component;

class PollShow extends Component
{
    use WithAuthRedirects;

    public $poll;
    public $votesCount;
    public $hasVoted;

    protected $listeners = [
        'statusWasUpdated',
        'statusWasUpdatedError',
        'pollWasUpdated',
        'pollWasMarkedAsSpam',
        'pollWasMarkedAsNotSpam',
        'commentWasAdded',
        'commentWasDeleted',
    ];

    public function mount(Poll $poll, $votesCount)
    {
        $this->poll = $poll;
        $this->votesCount = $votesCount;
        $this->hasVoted = $poll->isVotedByUser(auth()->user());
    }

    public function statusWasUpdated()
    {
        $this->poll->refresh();
    }

    public function statusWasUpdatedError()
    {
        $this->poll->refresh();
    }

    public function pollWasUpdated()
    {
        $this->poll->refresh();
    }

    public function pollWasMarkedAsSpam()
    {
        $this->poll->refresh();
    }

    public function pollWasMarkedAsNotSpam()
    {
        $this->poll->refresh();
    }

    public function commentWasAdded()
    {
        $this->poll->refresh();
    }

    public function commentWasDeleted()
    {
        $this->poll->refresh();
    }

    public function voteYes()
    {
        $this->poll->voteYes(auth()->user());
        $this->votesCount++;
        $this->hasVoted = true;
    }
    public function voteNo()
    {
        $this->poll->voteNo(auth()->user());
        $this->votesCount++;
        $this->hasVoted = true;
    }

    public function render()
    {
        return view('livewire.poll-show');
    }
}

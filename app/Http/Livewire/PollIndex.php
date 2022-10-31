<?php

namespace App\Http\Livewire;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
use App\Http\Livewire\Traits\WithAuthRedirects;
use App\Models\Poll;
use Livewire\Component;

class PollIndex extends Component
{
    use WithAuthRedirects;

    public Poll $poll;
    public $votesCount;
    public $hasVoted;

    public function mount(Poll $poll, $votesCount)
    {
        $this->poll = $poll;
        $this->votesCount = $votesCount;
        $this->hasVoted = $poll->voted_by_user;
    }

    public function voteYes()
    {
        $this->poll->voteYes(auth()->user());
        $this->votesCount++;
        $this->hasVoted = true;
        $this->poll->refresh();
    }
    public function voteNo()
    {
        $this->poll->voteNo(auth()->user());
        $this->votesCount++;
        $this->hasVoted = true;
        $this->poll->refresh();
    }

    public function render()
    {
        return view('livewire.poll-index');
    }
}

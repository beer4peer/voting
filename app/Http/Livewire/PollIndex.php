<?php

namespace App\Http\Livewire;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
use App\Http\Livewire\Traits\WithAuthRedirects;
use App\Http\Livewire\Traits\WithVotes;
use App\Models\Poll;
use Livewire\Component;

class PollIndex extends Component
{
    use WithAuthRedirects;
    use WithVotes;

    public Poll $poll;
    public $votesCount;
    public $hasVoted;

    protected $listeners = ['refresh-screen' => '$refresh'];

    public function mount(Poll $poll, $votesCount)
    {
        $this->poll = $poll;
        $this->votesCount = $votesCount;
        $this->hasVoted = $poll->voted_by_user;
    }



    public function render()
    {
        return view('livewire.poll-index');
    }
}

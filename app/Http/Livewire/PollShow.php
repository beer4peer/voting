<?php

namespace App\Http\Livewire;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
use App\Http\Livewire\Traits\WithAuthRedirects;
use App\Http\Livewire\Traits\WithVotes;
use App\Models\Poll;
use Livewire\Component;

class PollShow extends Component
{
    use WithAuthRedirects;
    use WithVotes;

    public Poll $poll;
    public $votesCount;
    public $hasVoted;

    protected $listeners = ['refresh-screen' => '$refresh'];

    public function mount(Poll $poll)
    {
        $this->poll = $poll->loadCount('votes');
    }

    public function render()
    {
        $this->poll->refresh()->loadCount('votes');
        return view('livewire.poll-show');
    }
}

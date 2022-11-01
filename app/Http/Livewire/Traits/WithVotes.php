<?php

namespace App\Http\Livewire\Traits;

trait WithVotes
{
    public function voteYes()
    {
        $this->poll->voteYes(auth()->user());
        $this->votesCount++;
        $this->hasVoted = true;
        $this->poll->refresh();
        $this->emit('refresh-screen');
    }
    public function voteNo()
    {
        $this->poll->voteNo(auth()->user());
        $this->votesCount++;
        $this->hasVoted = true;
        $this->poll->refresh();
        $this->emit('refresh-screen');
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Poll;
use Illuminate\Http\Response;
use Livewire\Component;

class MarkPollAsNotSpam extends Component
{
    public $poll;

    public function mount(Poll $poll)
    {
        $this->poll = $poll;
    }

    public function markAsNotSpam()
    {
        if (auth()->guest() || ! auth()->user()->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->poll->spam_reports = 0;
        $this->poll->save();

        $this->emit('pollWasMarkedAsNotSpam', 'Spam Counter was reset!');
    }

    public function render()
    {
        return view('livewire.mark-poll-as-not-spam');
    }
}

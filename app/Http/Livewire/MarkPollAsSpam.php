<?php

namespace App\Http\Livewire;

use App\Models\Poll;
use Illuminate\Http\Response;
use Livewire\Component;

class MarkPollAsSpam extends Component
{
    public $poll;

    public function mount(Poll $poll)
    {
        $this->poll = $poll;
    }

    public function markAsSpam()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->poll->spam_reports++;
        $this->poll->save();

        $this->emit('pollWasMarkedAsSpam', 'Poll was marked as spam!');
    }

    public function render()
    {
        return view('livewire.mark-poll-as-spam');
    }
}

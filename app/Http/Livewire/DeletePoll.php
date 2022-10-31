<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Response;
use Livewire\Component;

class DeletePoll extends Component
{
    public $poll;

    public function mount(Poll $poll)
    {
        $this->poll = $poll;
    }

    public function deletePoll()
    {
        if (auth()->guest() || auth()->user()->cannot('delete', $this->poll)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        Poll::destroy($this->poll->id);

        session()->flash('success_message', 'Poll was deleted successfully!');

        return redirect()->route('poll.index');
    }

    public function render()
    {
        return view('livewire.delete-poll');
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Poll;
use Illuminate\Http\Response;
use Livewire\Component;

class SetStatus extends Component
{
    public $poll;
    public $status;
    public $comment;

    protected $listeners = ['refresh-screen' => '$refresh'];

    public function mount(Poll $poll)
    {
        $this->poll = $poll;
        $this->status = $this->poll->status_id;
    }

    public function setStatus()
    {
        if (auth()->guest() || ! auth()->user()->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if ($this->poll->status_id === (int) $this->status) {
            $this->emit('refresh-screen');

            return;
        }

        $this->poll->status_id = $this->status;
        $this->poll->save();

        Comment::create([
            'user_id' => auth()->id(),
            'poll_id' => $this->poll->id,
            'status_id' => $this->status,
            'body' => $this->comment ?? 'No comment was added.',
            'is_status_update' => true,
        ]);

        $this->reset('comment');

        $this->emit('refresh-screen');
    }

    public function render()
    {
        return view('livewire.set-status');
    }
}

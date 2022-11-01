<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Traits\WithAuthRedirects;
use App\Models\Comment;
use App\Models\Poll;
use App\Notifications\CommentAdded;
use Illuminate\Http\Response;
use Livewire\Component;

class AddComment extends Component
{
    use WithAuthRedirects;

    public $poll;
    public $comment;

    protected $listeners = ['refresh-screen' => '$refresh'];

    protected $rules = [
        'comment' => 'required|min:4',
    ];

    public function mount(Poll $poll)
    {
        $this->poll = $poll;
    }

    public function addComment()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate();

        $newComment = Comment::create([
            'user_id' => auth()->id(),
            'poll_id' => $this->poll->id,
            'status_id' => 1,
            'body' => $this->comment,
        ]);

        $this->reset('comment');

        $this->emit('refresh-screen');
    }

    public function render()
    {
        return view('livewire.add-comment');
    }
}

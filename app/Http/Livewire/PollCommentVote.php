<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Livewire\Component;

class PollCommentVote extends Component
{
    public Comment $comment;

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function render()
    {
        return view('livewire.poll-comment-vote');
    }
}

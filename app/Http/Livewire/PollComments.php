<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Poll;
use Livewire\Component;
use Livewire\WithPagination;

class PollComments extends Component
{
    use WithPagination;

    public $poll;

    protected $listeners = ['refresh-screen' => '$refresh'];

    public function commentWasAdded()
    {
        $this->poll->refresh();
        $this->goToPage($this->poll->comments()->paginate()->lastPage());
    }

    public function statusWasUpdated()
    {
        $this->poll->refresh();
        $this->goToPage($this->poll->comments()->paginate()->lastPage());
    }

    public function commentWasDeleted()
    {
        $this->poll->refresh();
        $this->goToPage(1);
    }

    public function mount(Poll $poll)
    {
        $this->poll = $poll;
    }

    public function render()
    {
        return view('livewire.poll-comments', [
            // 'comments' => $this->poll->comments()->paginate()->withQueryString(),
            'comments' => Comment::with(['user', 'status'])->where('poll_id', $this->poll->id)->paginate()->withQueryString(),
        ]);
    }
}

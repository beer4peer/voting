<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Traits\WithAuthRedirects;
use App\Models\Category;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Response;
use Livewire\Component;

class CreatePoll extends Component
{
    use WithAuthRedirects;

    public $title;
    public $category = 1;
    public $description;

    protected $rules = [
        'title' => 'required|min:4',
        'category' => 'required|integer|exists:categories,id',
        'description' => 'required|min:4',
    ];

    public function createPoll()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate();

        $poll = Poll::create([
            'user_id' => auth()->id(),
            'category_id' => $this->category,
            'status_id' => 1,
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $poll->vote(auth()->user());

        session()->flash('success_message', 'Poll was added successfully!');

        $this->reset();

        return redirect()->route('poll.index');
    }

    public function render()
    {
        return view('livewire.create-poll', [
            'categories' => Category::all(),
        ]);
    }
}

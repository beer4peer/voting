<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Poll;
use Illuminate\Http\Response;
use Livewire\Component;

class EditPoll extends Component
{
    public $poll;
    public $title;
    public $category;
    public $description;

    protected $rules = [
        'title' => 'required|min:4',
        'category' => 'required|integer|exists:categories,id',
        'description' => 'required|min:4',
    ];

    public function mount(Poll $poll)
    {
        $this->poll = $poll;
        $this->title = $poll->title;
        $this->category = $poll->category_id;
        $this->description = $poll->description;
    }

    public function updatePoll()
    {
        if (auth()->guest() || auth()->user()->cannot('update', $this->poll)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate();

        $this->poll->update([
            'title' => $this->title,
            'category_id' => $this->category,
            'description' => $this->description,
        ]);

        $this->emit('pollWasUpdated', 'Poll was updated successfully!');
    }

    public function render()
    {
        return view('livewire.edit-poll', [
            'categories' => Category::all(),
        ]);
    }
}

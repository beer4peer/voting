<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Traits\WithAuthRedirects;
use App\Models\Category;
use App\Models\Poll;
use App\Models\Status;
use App\Models\Vote;
use Livewire\Component;
use Livewire\WithPagination;

class PollsIndex extends Component
{
    use WithPagination, WithAuthRedirects;

    public $status;
    public $category;
    public $filter;
    public $search;

    protected $listeners = ['refresh-screen' => '$refresh'];

    protected $queryString = [
        'status' => ['except' => 'All'],
        'category' => ['except' => 'All Categories'],
        'filter' => ['except' => 'No Filter'],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->status = request()->status ?? 'All';
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        if ($this->filter === 'My Polls') {
            if (auth()->guest()) {
                return $this->redirectToLogin();
            }
        }
    }

    public function queryStringUpdatedStatus($newStatus)
    {
        $this->resetPage();
        $this->status = $newStatus;
    }

    public function render()
    {
        $statuses = Status::all()->pluck('id', 'name');
        $categories = Category::all();

        return view('livewire.polls-index', [
            'polls' => Poll::with('user', 'category', 'status')
                ->when($this->status && $this->status !== 'All', function ($query) use ($statuses) {
                    return $query->where('status_id', $statuses->get($this->status));
                })
                ->when($this->category && $this->category !== 'All Categories', function ($query) use ($categories) {
                    return $query->where('category_id', $categories->pluck('id', 'name')->get($this->category));
                })
                ->when($this->filter && $this->filter === 'Top Voted', function ( $query) {
                    return $query->orderByDesc('votes_count');
                })
                ->when(strlen($this->search) >= 3, function ($query) {
                    return $query->where('title', 'like', '%' . $this->search . '%');
                })
                ->withCount('votes')
                ->withCount('comments')
                ->orderBy('id', 'desc')
                ->simplePaginate()
                ->withQueryString(),

            'categories' => $categories,
        ]);
    }
}

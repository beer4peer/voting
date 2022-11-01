<?php

namespace App\Http\Livewire;

use App\Models\Poll;
use App\Models\Status;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class StatusFilters extends Component
{
    public $status;
    public $statusCount;

    protected $listeners = ['refresh-screen' => '$refresh'];

    public function mount()
    {
        $this->statusCount = Status::getCount();
        $this->status = request()->status ?? 'All';

        if (Route::currentRouteName() === 'poll.show') {
            $this->status = null;
        }
    }

    public function setStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->emit('refresh-screen');

        if ($this->getPreviousRouteName() === 'poll.show') {
            return redirect()->route('poll.index', [
                    'status' => $this->status,
                ]);
        }
    }

    public function render()
    {
        return view('livewire.status-filters');
    }

    public function getPreviousRouteName()
    {
        return app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
    }
}

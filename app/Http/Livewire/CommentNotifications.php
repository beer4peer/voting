<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Poll;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class CommentNotifications extends Component
{
    const NOTIFICATION_THRESHOLD = 20;
    public $notifications;
    public $notificationCount;
    public $isLoading;

    protected $listeners = ['refresh-screen' => '$refresh'];

    public function mount()
    {
        $this->notifications = collect([]);
        $this->isLoading = true;
    }


    public function render()
    {
        return view('livewire.comment-notifications');
    }
}

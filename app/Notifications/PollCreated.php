<?php

namespace App\Notifications;

use App\Models\Poll;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class PollCreated extends Notification
{
    use Queueable;

    public function __construct(public Poll $poll)
    {
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable): SlackMessage
    {
        $ends_at = $this->poll->ends_at?->toDateTimeString();
        $url = route('poll.show', $this->poll);

        return (new SlackMessage)
            ->success()
            ->content(
                "A new poll has been created " . "\r\n\r\n" .
                "*Title:* {$this->poll->title}" . "\r\n" .
                "*Category:* {$this->poll->category->name} " . "\r\n" .
                "*Ends At: *{$ends_at}" . "\r\n" .
                "{$this->poll->description}". "\r\n\r\n" .
                "Vote Here: {$url}" ."\r\n\r\n" .
                "_Reminder: You are required to login via Slack to vote. This is required to stop abuse and duplicate votes. Voting is anonymous and we only store your name and Slack ID which is unique to beer4peer. Therefore once your vote has been registered you cannot change it. The source code is hiding on GitHub (https://github.com/beer4peer/voting) if you don't believe me._"
            );
    }

}

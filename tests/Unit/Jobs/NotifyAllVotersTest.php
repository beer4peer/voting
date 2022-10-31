<?php

namespace Tests\Unit\Jobs;

use App\Jobs\NotifyAllVoters;
use App\Mail\PollStatusUpdatedMailable;
use App\Models\Category;
use App\Models\Poll;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyAllVotersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_an_email_to_all_voters()
    {
        $user = User::factory()->create([
            'email' => 'andre_madarang@hotmail.com',
        ]);

        $userB = User::factory()->create([
            'email' => 'user@user.com',
        ]);

        $poll = Poll::factory()->create();

        Vote::create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
        ]);

        Vote::create([
            'poll_id' => $poll->id,
            'user_id' => $userB->id,
        ]);

        Mail::fake();

        NotifyAllVoters::dispatch($poll);

        Mail::assertQueued(PollStatusUpdatedMailable::class, function ($mail) {
            return $mail->hasTo('andre_madarang@hotmail.com')
                && $mail->build()->subject === 'An poll you voted for has a new status';
        });

        Mail::assertQueued(PollStatusUpdatedMailable::class, function ($mail) {
            return $mail->hasTo('user@user.com')
                && $mail->build()->subject === 'An poll you voted for has a new status';
        });
    }
}

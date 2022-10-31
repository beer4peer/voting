<?php

namespace Tests\Unit;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
use App\Models\Category;
use App\Models\Poll;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PollTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_check_if_poll_is_voted_for_by_user()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $poll = Poll::factory()->create();

        Vote::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($poll->isVotedByUser($user));
        $this->assertFalse($poll->isVotedByUser($userB));
        $this->assertFalse($poll->isVotedByUser(null));
    }

    /** @test */
    public function user_can_vote_for_poll()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create();

        $this->assertFalse($poll->isVotedByUser($user));
        $poll->vote($user);
        $this->assertTrue($poll->isVotedByUser($user));
    }

    /** @test */
    public function voting_for_an_poll_thats_already_voted_for_throws_exception()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create();

        Vote::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
        ]);

        $this->expectException(DuplicateVoteException::class);

        $poll->vote($user);
    }

    /** @test */
    public function user_can_remove_vote_for_poll()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create();

        Vote::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($poll->isVotedByUser($user));
        $poll->removeVote($user);
        $this->assertFalse($poll->isVotedByUser($user));
    }

    /** @test */
    public function removing_a_vote_that_doesnt_exist_throws_exception()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create();

        $this->expectException(VoteNotFoundException::class);

        $poll->removeVote($user);
    }
}

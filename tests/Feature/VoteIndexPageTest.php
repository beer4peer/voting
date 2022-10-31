<?php

namespace Tests\Feature;

use App\Http\Livewire\PollIndex;
use App\Http\Livewire\PollsIndex;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class VoteIndexPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_contains_poll_index_livewire_component()
    {
        Poll::factory()->create();

        $this->get(route('poll.index'))
            ->assertSeeLivewire('poll-index');
    }

    /** @test */
    public function polls_index_livewire_component_correctly_receives_votes_count()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $poll = Poll::factory()->create();

        Vote::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
        ]);

        Vote::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $userB->id,
        ]);

        Livewire::test(PollsIndex::class)
            ->assertViewHas('polls', function ($polls) {
                return $polls->first()->votes_count == 2;
            });
    }

    /** @test */
    public function votes_count_shows_correctly_on_index_page_livewire_component()
    {
        $poll = Poll::factory()->create();

        Livewire::test(PollIndex::class, [
            'poll' => $poll,
            'votesCount' => 5,
        ])
        ->assertSet('votesCount', 5);
    }

    /** @test */
    public function user_who_is_logged_in_shows_voted_if_poll_already_voted_for()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        Vote::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
        ]);

        $poll->votes_count = 1;
        $poll->voted_by_user = 1;

        Livewire::actingAs($user)
            ->test(PollIndex::class, [
                'poll' => $poll,
                'votesCount' => 5,
            ])
            ->assertSet('hasVoted', true)
            ->assertSee('Voted');
    }

    /** @test */
    public function user_who_is_not_logged_in_is_redirected_to_login_page_when_trying_to_vote()
    {
        $poll = Poll::factory()->create();

        Livewire::test(PollIndex::class, [
                'poll' => $poll,
                'votesCount' => 5,
            ])
            ->call('vote')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_who_is_logged_in_can_vote_for_poll()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $this->assertDatabaseMissing('votes', [
            'user_id' => $user->id,
            'poll_id' => $poll->id,
        ]);

        Livewire::actingAs($user)
            ->test(PollIndex::class, [
                'poll' => $poll,
                'votesCount' => 5,
            ])
            ->call('vote')
            ->assertSet('votesCount', 6)
            ->assertSet('hasVoted', true)
            ->assertSee('Voted');

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'poll_id' => $poll->id,
        ]);
    }

    /** @test */
    public function user_who_is_logged_in_can_remove_vote_for_poll()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        Vote::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
        ]);

        $poll->votes_count = 1;
        $poll->voted_by_user = 1;

        Livewire::actingAs($user)
            ->test(PollIndex::class, [
                'poll' => $poll,
                'votesCount' => 5,
            ])
            ->call('vote')
            ->assertSet('votesCount', 4)
            ->assertSet('hasVoted', false)
            ->assertSee('Vote')
            ->assertDontSee('Voted');

        $this->assertDatabaseMissing('votes', [
            'user_id' => $user->id,
            'poll_id' => $poll->id,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Http\Livewire\DeletePoll;
use App\Http\Livewire\PollShow;
use App\Models\Comment;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

class DeletePollTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_delete_poll_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertSeeLivewire('delete-poll');
    }

    /** @test */
    public function does_not_show_delete_poll_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertDontSeeLivewire('delete-poll');
    }

    /** @test */
    public function deleting_an_poll_works_when_user_has_authorization()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(DeletePoll::class, [
                'poll' => $poll,
            ])
            ->call('deletePoll')
            ->assertRedirect(route('poll.index'));

        $this->assertEquals(0, Poll::count());
    }

    /** @test */
    public function deleting_an_poll_works_when_user_is_admin()
    {
        $user = User::factory()->admin()->create();

        $poll = Poll::factory()->create();

        Livewire::actingAs($user)
            ->test(DeletePoll::class, [
                'poll' => $poll,
            ])
            ->call('deletePoll')
            ->assertRedirect(route('poll.index'));

        $this->assertEquals(0, Poll::count());
    }

    /** @test */
    public function deleting_an_poll_with_votes_works_when_user_has_authorization()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        Vote::factory()->create([
            'user_id' => $user->id,
            'poll_id' => $poll->id,
        ]);

        Livewire::actingAs($user)
            ->test(DeletePoll::class, [
                'poll' => $poll,
            ])
            ->call('deletePoll')
            ->assertRedirect(route('poll.index'));

        $this->assertEquals(0, Vote::count());
        $this->assertEquals(0, Poll::count());
    }

    /** @test */
    public function deleting_an_poll_with_comments_works_when_user_has_authorization()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        Comment::factory()->create([
            'poll_id' => $poll->id,
        ]);

        Livewire::actingAs($user)
            ->test(DeletePoll::class, [
                'poll' => $poll,
            ])
            ->call('deletePoll')
            ->assertRedirect(route('poll.index'));

        $this->assertEquals(0, Poll::count());
    }

    /** @test */
    public function deleting_an_poll_does_not_work_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        Livewire::actingAs($user)
            ->test(DeletePoll::class, [
                'poll' => $poll,
            ])
            ->call('deletePoll')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function deleting_an_poll_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->create();

        $poll = Poll::factory()->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(PollShow::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertSee('Delete Poll');
    }

    /** @test */
    public function deleting_an_poll_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        Livewire::actingAs($user)
            ->test(PollShow::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertDontSee('Delete Poll');
    }
}

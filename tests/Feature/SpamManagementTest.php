<?php

namespace Tests\Feature;

use App\Http\Livewire\DeletePoll;
use App\Http\Livewire\PollIndex;
use App\Http\Livewire\PollShow;
use App\Http\Livewire\MarkPollAsNotSpam;
use App\Http\Livewire\MarkPollAsSpam;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

class SpamManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_mark_poll_as_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertSeeLivewire('mark-poll-as-spam');
    }

    /** @test */
    public function does_not_show_mark_poll_as_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $this->get(route('poll.show', $poll))
            ->assertDontSeeLivewire('mark-poll-as-spam');
    }

    /** @test */
    public function marking_an_poll_as_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        Livewire::actingAs($user)
            ->test(MarkPollAsSpam::class, [
                'poll' => $poll,
            ])
            ->call('markAsSpam')
            ->assertEmitted('pollWasMarkedAsSpam');

        $this->assertEquals(1, Poll::first()->spam_reports);
    }

    /** @test */
    public function marking_an_poll_as_spam_does_not_work_when_user_does_not_have_authorization()
    {
        $poll = Poll::factory()->create();

        Livewire::test(MarkPollAsSpam::class, [
                'poll' => $poll,
            ])
            ->call('markAsSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_an_poll_as_spam_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        Livewire::actingAs($user)
            ->test(PollShow::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertSee('Mark as Spam');
    }

    /** @test */
    public function marking_an_poll_as_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $poll = Poll::factory()->create();

        Livewire::test(PollShow::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertDontSee('Mark as Spam');
    }

    /** @test */
    public function shows_mark_poll_as_not_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $poll = Poll::factory()->create();

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertSeeLivewire('mark-poll-as-not-spam');
    }

    /** @test */
    public function does_not_show_mark_poll_as_not_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $poll = Poll::factory()->create();

        $this->get(route('poll.show', $poll))
            ->assertDontSeeLivewire('mark-poll-as-not-spam');
    }

    /** @test */
    public function marking_an_poll_as_not_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $poll = Poll::factory()->create([
            'spam_reports' => 4,
        ]);

        Livewire::actingAs($user)
            ->test(MarkPollAsNotSpam::class, [
                'poll' => $poll,
            ])
            ->call('markAsNotSpam')
            ->assertEmitted('pollWasMarkedAsNotSpam');

        $this->assertEquals(0, Poll::first()->spam_reports);
    }

    /** @test */
    public function marking_an_poll_as_not_spam_does_not_work_when_user_does_not_have_authorization()
    {
        $poll = Poll::factory()->create();

        Livewire::test(MarkPollAsNotSpam::class, [
                'poll' => $poll,
            ])
            ->call('markAsNotSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_an_poll_as_not_spam_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $poll = Poll::factory()->create([
            'spam_reports' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(PollShow::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertSee('Not Spam');
    }

    /** @test */
    public function marking_an_poll_as_not_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $poll = Poll::factory()->create();

        Livewire::test(PollShow::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertDontSee('Not Spam');
    }

    /** @test */
    public function spam_reports_count_shows_on_poll_index_page_if_logged_in_as_admin()
    {
        $user = User::factory()->admin()->create();
        $poll = Poll::factory()->create([
            'spam_reports' => 3,
        ]);

        Livewire::actingAs($user)
            ->test(PollIndex::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertSee('Spam Reports: 3');
    }

    /** @test */
    public function spam_reports_count_shows_on_poll_show_page_if_logged_in_as_admin()
    {
        $user = User::factory()->admin()->create();
        $poll = Poll::factory()->create([
            'spam_reports' => 3,
        ]);

        Livewire::actingAs($user)
            ->test(PollShow::class, [
                'poll' => $poll,
                'votesCount' => 4,
            ])
            ->assertSee('Spam Reports: 3');
    }
}

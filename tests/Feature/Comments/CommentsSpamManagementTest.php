<?php

namespace Tests\Feature\Comments;

use App\Http\Livewire\PollComment;
use App\Http\Livewire\MarkCommentAsNotSpam;
use App\Http\Livewire\MarkCommentAsSpam;
use App\Models\Comment;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

class CommentsSpamManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_mark_comment_as_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertSeeLivewire('mark-comment-as-spam');
    }

    /** @test */
    public function does_not_show_mark_comment_as_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        $this->get(route('poll.show', $poll))
            ->assertDontSeeLivewire('mark-comment-as-spam');
    }

    /** @test */
    public function marking_a_comment_as_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(MarkCommentAsSpam::class)
            ->call('setMarkAsSpamComment', $comment->id)
            ->call('markAsSpam')
            ->assertEmitted('commentWasMarkedAsSpam');

        $this->assertEquals(1, Comment::first()->spam_reports);
    }

    /** @test */
    public function marking_a_comment_as_spam_does_not_work_when_user_does_not_have_authorization()
    {
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::test(MarkCommentAsSpam::class)
            ->call('setMarkAsSpamComment', $comment->id)
            ->call('markAsSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_a_comment_as_spam_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(PollComment::class, [
                'comment' => $comment,
                'pollUserId' => $poll->user_id
            ])
            ->assertSee('Mark as Spam');
    }

    /** @test */
    public function marking_a_comment_as_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::test(PollComment::class, [
                'comment' => $comment,
                'pollUserId' => $poll->user_id
            ])
            ->assertDontSee('Mark as Spam');
    }

    /** @test */
    public function shows_mark_comment_as_not_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertSeeLivewire('mark-comment-as-not-spam');
    }

    /** @test */
    public function does_not_show_mark_comment_as_not_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertDontSeeLivewire('mark-comment-as-not-spam');
    }

    /** @test */
    public function marking_a_comment_as_not_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(MarkCommentAsNotSpam::class)
            ->call('setMarkAsNotSpamComment', $comment->id)
            ->call('markAsNotSpam')
            ->assertEmitted('commentWasMarkedAsNotSpam');

        $this->assertEquals(0, Comment::first()->spam_reports);
    }

    /** @test */
    public function marking_a_comment_as_not_spam_does_not_work_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(MarkCommentAsNotSpam::class)
            ->call('setMarkAsNotSpamComment', $comment->id)
            ->call('markAsNotSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function marking_a_comment_as_not_spam_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
            'spam_reports' => 1,
        ]);

        Livewire::actingAs($user)
            ->test(PollComment::class, [
                'comment' => $comment,
                'pollUserId' => $poll->user_id
            ])
            ->assertSee('Not Spam');
    }

    /** @test */
    public function marking_a_comment_as_not_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(PollComment::class, [
                'comment' => $comment,
                'pollUserId' => $poll->user_id
            ])
            ->assertDontSee('Not Spam');
    }
}

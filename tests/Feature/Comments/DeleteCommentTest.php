<?php

namespace Tests\Feature\Comments;

use App\Http\Livewire\DeleteComment;
use App\Http\Livewire\EditComment;
use App\Http\Livewire\EditPoll;
use App\Http\Livewire\PollComment;
use App\Http\Livewire\PollShow;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_delete_comment_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $this->actingAs($user)
            ->get(route('poll.show', $poll))
            ->assertSeeLivewire('delete-comment');
    }

    /** @test */
    public function does_not_show_delete_comment_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $this->get(route('poll.show', $poll))
            ->assertDontSeeLivewire('delete-comment');
    }

    /** @test */
    public function delete_comment_is_set_correctly_when_user_clicks_it_from_menu()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(DeleteComment::class)
            ->call('setDeleteComment', $comment->id)
            ->assertEmitted('deleteCommentWasSet');
    }

    /** @test */
    public function deleting_a_comment_works_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(DeleteComment::class)
            ->call('setDeleteComment', $comment->id)
            ->call('deleteComment')
            ->assertEmitted('commentWasDeleted');

        $this->assertEquals(0, Comment::count());
    }

    /** @test */
    public function deleting_a_comment_does_not_work_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(DeleteComment::class)
            ->call('setDeleteComment', $comment->id)
            ->call('deleteComment')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function deleting_a_comment_shows_on_menu_when_user_has_authorization()
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
                'pollUserId' => $poll->user_id,
            ])
            ->assertSee('Delete Comment');
    }

    /** @test */
    public function deleting_a_comment_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $poll = Poll::factory()->create();

        $comment = Comment::factory()->create([
            'poll_id' => $poll->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(PollComment::class, [
                'comment' => $comment,
                'pollUserId' => $poll->user_id,
            ])
            ->assertDontSee('Delete Comment');
    }
}
